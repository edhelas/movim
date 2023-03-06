<?php
/*
 * SPDX-FileCopyrightText: 2010 Jaussoin Timothée
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace Movim\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Respect\Validation\Validator;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

use Movim\Daemon\Core;
use Movim\Daemon\Api;
use App\User;

use Phinx\Migration\Manager;
use Phinx\Config\Config;
use Symfony\Component\Console\Output\NullOutput;

use React\EventLoop\Loop;
use React\Socket\SocketServer;

class DaemonCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('start')
            ->setDescription('Start the daemon')
            ->addOption(
                'debug',
                'd',
                InputOption::VALUE_NONE,
                'Output XMPP logs'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new Config(require(DOCUMENT_ROOT . '/phinx.php'));
        $manager = new Manager($config, $input, new NullOutput);

        if ($manager->printStatus('movim')['hasDownMigration']) {
            $output->writeln('<comment>The database needs to be migrated before running the daemon</comment>');
            $output->writeln('<info>To migrate the database run</info>');
            $output->writeln('<info>php vendor/bin/phinx migrate</info>');
            exit;
        }

        $loop = Loop::get();

        if (config('daemon.url') && Validator::url()->notEmpty()->validate(config('daemon.url'))) {
            $baseuri = rtrim(config('daemon.url'), '/') . '/';
        } elseif (file_exists(CACHE_PATH . 'baseuri')) {
            $baseuri = file_get_contents(CACHE_PATH . 'baseuri');
        } else {
            $output->writeln('<comment>Please load the login page once before starting the daemon to cache the public URL</comment>');
            $output->writeln('<comment>or configure DAEMON_URL in .env</comment>');
            exit;
        }

        if (User::where('admin', true)->count() == 0) {
            $output->writeln('<comment>Please set at least one user as an admin once its account is logged in</comment>');

            $output->writeln('<info>To set an existing user admin</info>');
            $output->writeln('<info>php daemon.php setAdmin {jid}</info>' . "\n");
        }

        $compileLanguages = new \React\ChildProcess\Process('exec php daemon.php compileLanguages');
        $compileLanguages->start($loop);
        $compileLanguages->on('exit', fn ($out) => $output->writeln('<info>Compiled po files</info>'));

        $compileStickers = new \React\ChildProcess\Process('exec php daemon.php compileStickers');
        $compileStickers->start($loop);
        $compileStickers->on('exit', fn ($out) => $output->writeln('<info>Stickers compiled</info>'));

        $output->writeln('<info>Movim daemon launched</info>');
        $output->writeln('<info>Base URL: ' . $baseuri . '</info>');

        if ($input->getOption('debug')) {
            $output->writeln("\n" . '<comment>Debug is enabled, check the logs in syslog or ' . DOCUMENT_ROOT . '/log/</comment>');
        }

        if (isOpcacheEnabled()) {
            $compileOpcache = new \React\ChildProcess\Process('exec php daemon.php compileOpcache');
            $compileOpcache->start($loop);
            $compileOpcache->on('exit', fn ($out) => $output->writeln('<info>Files compiled in Opcache</info>'));
        } else {
            $output->writeln('<error>Opcache is disabled, it is strongly advised to enable it in PHP CLI php.ini</error>');
            $output->writeln('Set opcache.enable=1 and opcache.enable_cli=1 in the PHP CLI ini file');
        }


        $core = new Core($loop, $baseuri);
        $app  = new HttpServer(new WsServer($core));

        $socket = new SocketServer(
            config('daemon.interface') . ':' . config('daemon.port')
        );

        $socketApi = new SocketServer('unix://' . API_SOCKET);
        new Api($socketApi, $core);

        (new IoServer($app, $socket, $loop))->run();

        return 0;
    }
}
