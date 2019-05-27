<?php

namespace Moxl\Xec\Payload;

use App\Contact;

class Vcard4 extends Payload
{
    public function handle($stanza, $parent = false)
    {
        $from = explodeJid((string)$parent->attributes()->from)['jid'];

        $contact = Contact::firstOrNew(['id' => $from]);
        $contact->setVcard4($stanza->items->item->vcard);
        $contact->save();

        $this->event('vcard', $contact);
    }
}
