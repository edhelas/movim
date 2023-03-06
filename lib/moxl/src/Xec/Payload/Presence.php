<?php

namespace Moxl\Xec\Payload;

use Movim\Session;
use App\Presence as DBPresence;
use App\PresenceBuffer;

class Presence extends Payload
{
    public function handle(?\SimpleXMLElement $stanza = null, ?\SimpleXMLElement $parent = null)
    {
        $jid = explodeJid($stanza->attributes()->from);
        if (\App\User::me()->hasBlocked($jid['jid'])) {
            return;
        }

        // Subscribe request
        if ((string)$stanza->attributes()->type == 'subscribe') {
            $session = Session::start();
            $notifs = $session->get('activenotifs', []);

            $notifs[(string)$stanza->attributes()->from] = 'sub';
            $session->set('activenotifs', $notifs);

            $this->event('subscribe', (string)$stanza->attributes()->from);
        } elseif((string)$stanza->attributes()->type === 'error'
            && isset($stanza->attributes()->id)) {
            // Let's drop errors with an id, useless for us
        } else {
            $presence = DBPresence::findByStanza($stanza);
            $presence->set($stanza);

            PresenceBuffer::getInstance()->append($presence, function () use ($presence, $stanza) {
                if ($presence->muc) {
                    if ($presence->mucjid == \App\User::me()->id) {
                        // Spectrum2 specific bug, we can receive two self-presences, one with several caps items
                        $cCount = 0;
                        foreach ($stanza->children() as $key => $content) {
                            if ($key == 'c') $cCount++;
                        }

                        if ($cCount > 1) {
                            $presence->delete();
                        }
                        // So we drop it

                        if ($presence->value != 5 && $presence->value != 6) {
                            $this->method('muc_handle');
                            $this->pack([$presence, false]);
                        } elseif ($presence->value == 5) {
                            $this->method('unavailable_handle');
                            $this->pack($presence->jid);
                        }

                        $this->deliver();
                    }
                } else {
                    $this->pack($presence->roster);

                    if ($presence->value == 5 && !empty($presence->resource)) {
                        $presence->delete();
                    }
                }

                $this->deliver();
            });
        }
    }
}
