<?php

namespace Moxl\Xec\Action\Presence;

use Moxl\Xec\Action;
use Moxl\Stanza\Presence;
use Movim\Session;
use App\PresenceBuffer;

class Muc extends Action
{
    public static $mucId = 'MUC_ID';

    protected $_to;
    protected $_nickname;
    protected $_mam = false;
    protected $_mam2 = false;
    protected $_create = false;

    // Disable the event
    protected $_notify = true;

    public function request()
    {
        $session = Session::start();

        if (empty($this->_nickname)) {
            $this->_nickname = $session->get('username');
        }

        if ($this->_mam == false && $this->_mam2 == false) {
            \App\User::me()->messages()->where('jidfrom', $this->_to)->delete();
        }

        $this->store(); // Set stanzaId

        /**
         * Some servers doesn't return the ID, so save it in another session key-value
         * and use the to and nickname as a key ¯\_(ツ)_/¯
         */
        $session->set(self::$mucId . $this->_to . '/' . $this->_nickname, $this->stanzaId);

        Presence::muc($this->_to, $this->_nickname, $this->_mam);
    }

    public function enableCreate()
    {
        $this->_create = true;
        return $this;
    }

    public function enableMAM()
    {
        $this->_mam = true;
        return $this;
    }

    public function enableMAM2()
    {
        $this->_mam2 = true;
        return $this;
    }

    public function noNotify()
    {
        $this->_notify = false;
        return $this;
    }

    public function handle(?\SimpleXMLElement $stanza = null, ?\SimpleXMLElement $parent = null)
    {
        $presence = \App\Presence::findByStanza($stanza);
        $presence->set($stanza);

        if ($stanza->attributes()->to) {
            $presence->mucjid = baseJid((string)$stanza->attributes()->to);
        }

        if ($this->_mam) {
            $message = \App\User::me()->messages()
                ->where('jidfrom', $this->_to)
                ->whereNull('subject')
                ->orderBy('published', 'desc')
                ->first();

            $g = new \Moxl\Xec\Action\MAM\Get;
            $g->setTo($this->_to)
                ->setLimit(300);

            if (
                !empty($message)
                && strtotime($message->published) > strtotime('-3 days')
            ) {
                $g->setStart(strtotime($message->published));
            } else {
                $g->setStart(strtotime('-3 days'));
            }

            if (!$this->_mam2) {
                $g->setVersion('1');
            }

            $g->request();
        }

        if ($this->_create) {
            $presence->save();

            $this->method('create_handle');
            $this->pack($presence);
            $this->deliver();
        } else {
            PresenceBuffer::getInstance()->append($presence, function () use ($presence) {
                $this->pack([$presence, $this->_notify]);
                $this->deliver();
            });
        }
    }

    public function errorRegistrationRequired(string $errorId, ?string $message = null)
    {
        $this->pack($this->_to);
        $this->deliver();
    }

    public function errorRemoteServerNotFound(string $errorId, ?string $message = null)
    {
        $this->pack($this->_to);
        $this->deliver();
    }

    public function errorNotAuthorized(string $errorId, ?string $message = null)
    {
        $this->pack($this->_to);
        $this->deliver();
    }

    public function errorGone(string $errorId, ?string $message = null)
    {
        $this->pack($this->_to);
        $this->deliver();
    }

    public function errorNotAllowed(string $errorId, ?string $message = null)
    {
        $this->pack($this->_to);
        $this->deliver();
    }

    public function errorItemNotFound(string $errorId, ?string $message = null)
    {
        $this->pack($this->_to);
        $this->deliver();
    }

    public function errorJidMalformed(string $errorId, ?string $message = null)
    {
        $this->pack($this->_to);
        $this->deliver();
    }

    public function errorNotAcceptable(string $errorId, ?string $message = null)
    {
        $this->pack($this->_to);
        $this->deliver();
    }

    public function errorServiceUnavailable(string $errorId, ?string $message = null)
    {
        $this->pack($this->_to);
        $this->deliver();
    }

    public function errorForbidden(string $errorId, ?string $message = null)
    {
        $this->pack($this->_to);
        $this->deliver();
    }

    public function errorRemoteServerTimeout(string $errorId, ?string $message = null)
    {
        $this->pack($this->_to);
        $this->deliver();
    }

    public function errorConflict(string $errorId, ?string $message = null)
    {
        if (substr_count($this->_nickname, '_') > 5) {
            $this->deliver();
        } else {
            $this->setNickname($this->_nickname . '_');
            $this->request();
        }
    }
}
