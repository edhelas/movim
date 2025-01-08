<?php

namespace Moxl\Xec\Payload;

use App\MujiCallParticipant;
use Carbon\Carbon;

class CallInviteLeft extends Payload
{
    public function handle(?\SimpleXMLElement $stanza = null, ?\SimpleXMLElement $parent = null)
    {
        if ($parent->{'stanza-id'} && $parent->{'stanza-id'}->attributes()->xmlns == 'urn:xmpp:sid:0') {
            $muji = \App\User::me()->session->mujiCalls()->where('id', (string)$stanza->attributes()->id)->first();

            if ($muji) {
                MujiCallParticipant::firstOrCreate([
                    'muji_call_id' => (string)$stanza->attributes()->id,
                    'jid' => (string)$parent->attributes()->from,
                    'left_at' => Carbon::now(),
                ]);

                $this->pack($muji);
                $this->deliver();
            }
        }
    }
}
