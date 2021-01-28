<?php
/*
 * SPDX-FileCopyrightText: 2010 Jaussoin Timothee 
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace Movim;

class Firebase
{
    private $_key;
    private $_token;

    public function __construct(string $key, string $token)
    {
        $this->_key = $key;
        $this->_token = $token;
    }

    public function notify(string $title, string $body = null, string $image = null, string $action = null)
    {
        $fields = [
            'to' => $this->_token,
            'data' => [
                'title' => $title,
                'body' => $body,
                'image' => $image,
                'action' => $action
            ]
        ];

        $this->request($fields);
    }

    public function clear(string $action)
    {
        $fields = [
            'to' => $this->_token,
            'data' => [
                'clear' => true,
                'action' => $action
            ]
        ];

        $this->request($fields);
    }

    private function request($fields)
    {
        $headers = ['Authorization:key='.$this->_key,'Content-Type:application/json'];
        requestURL('https://fcm.googleapis.com/fcm/send', 10, json_encode($fields), true, $headers);
    }
}
