<?php

namespace Jujiang\JJpush;

use GuzzleHttp\Client;

class Hms extends BasePush
{
    function pushAndroid($title, $content, array $extra)
    {
        $token = $this->getToken();
        $this->execPush($token, 'all-member', $title, $content, $extra);
        $this->execPushBackground($token, 'all-member', $title, $content, $extra);
    }

    function pushAndroidAlias($alias, $title, $content, array $extra)
    {
        $token = $this->getToken();
        $this->execPush($token, $alias, $title, $content, $extra);
        $this->execPushBackground($token, $alias, $title, $content, $extra);
    }

    function pushAndroidTag($tag, $title, $content, array $extra)
    {
        $token = $this->getToken();
        $this->execPush($token, $tag, $title, $content, $extra);
        $this->execPushBackground($token, $tag, $title, $content, $extra);
    }

    private function execPush($token, $topic, $title, $content, array $extra)
    {
        $client = new Client();
        $res = $client->post('https://push-api.cloud.huawei.com/v1/' . $this->config['appid'] . '/messages:send', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ],
            'json' => [
                'message' => [
                    'android' => [
                        'notification' => [
                            'title' => $title,
                            'body' => $content,
                            'sound' => $extra['sound'] ?? '',
                            'click_action' => [
                                'type' => 3
                            ],
                        ]
                    ],
                    'topic' => $topic
                ]
            ]
        ]);
        print_r($this->parseBody($res));
    }

    private function execPushBackground($token, $topic, $title, $content, array $extra)
    {
        $client = new Client();
        $res = $client->post('https://push-api.cloud.huawei.com/v1/' . $this->config['appid'] . '/messages:send', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ],
            'json' => [
                'message' => [
                    'data' => json_encode(array_merge([
                        'title' => $title,
                        'body' => $content,
                    ], $extra), JSON_UNESCAPED_UNICODE),
                    'topic' => $topic
                ]
            ]
        ]);
        print_r($this->parseBody($res));
    }

    private function getToken()
    {
        $client = new Client();
        $res = $client->post('https://oauth-login.cloud.huawei.com/oauth2/v3/token', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => $this->config['client_id'],
                'client_secret' => $this->config['client_secret'],
            ]
        ]);
        $body = $this->parseBody($res);
        return $body['access_token'];
    }
}
