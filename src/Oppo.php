<?php

namespace Jujiang\JJpush;

use GuzzleHttp\Client;

class Oppo extends BasePush
{

    function pushAndroid($title, $content, array $extra)
    {
        $client = new Client();
        $res = $client->post('https://api.push.oppomobile.com/server/v1/message/notification/broadcast', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'auth_token' => $this->getToken()
            ],
            'form_params' => [
                'message_id' => $this->buildMessage($title, $content, $extra),
                'target_type' => 1,
            ]
        ]);
        print_r($this->parseBody($res));
    }

    function pushAndroidAlias($alias, $title, $content, array $extra)
    {
        $client = new Client();
        $res = $client->post('https://api.push.oppomobile.com/server/v1/message/notification/broadcast', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'auth_token' => $this->getToken()
            ],
            'form_params' => [
                'message_id' => $this->buildMessage($title, $content, $extra),
                'target_type' => 2,
                'target_value' => $alias
            ]
        ]);
        print_r($this->parseBody($res));
    }

    function pushAndroidTag($tag, $title, $content, array $extra)
    {
    }

    private function buildMessage($title, $content, array $extra)
    {
        $client = new Client();
        $res = $client->post('https://api.push.oppomobile.com/server/v1/message/notification/save_message_content', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'auth_token' => $this->getToken()
            ],
            'form_params' => [
                'title' => ($title),
                'sub_title' => '',
                'content' => ($content),
                'action_parameters' => json_encode(array_merge([
                    'title' => ($title),
                    'body' => ($content),
                ], $extra), JSON_UNESCAPED_UNICODE)
            ]
        ]);
        $body = $this->parseBody($res);
        return $body['data']['message_id'];
    }

    private function getToken()
    {
        $client = new Client();
        $time = time() * 1000;
        $res = $client->post('https://api.push.oppomobile.com/server/v1/auth', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => [
                'app_key' => $this->config['app_key'],
                'timestamp' => $time,
                'sign' => hash('sha256', $this->config['app_key'] . $time . $this->config['app_secret'])
            ]
        ]);
        $body = $this->parseBody($res);
        return $body['data']['auth_token'];
    }
}
