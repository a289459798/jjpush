<?php

namespace Jujiang\JJpush;

use GuzzleHttp\Client;

class Hms extends BasePush
{
    function pushAndroid()
    {
        $token = $this->getToken();
        $this->execPush($token, 'all-member');
        $this->execPushBackground($token, 'all-member');
    }

    function pushAndroidAlias($alias)
    {
        $token = $this->getToken();
        $this->execPush($token, $alias);
        $this->execPushBackground($token);
    }

    function pushAndroidTag($tag)
    {
        $token = $this->getToken();
        $this->execPush($token, $tag);
        $this->execPushBackground($token);
    }

    public function setAlias($alias, $regId)
    {
        $client = new Client();
        $res = $client->post('https://push-api.cloud.huawei.com/v1/' . $this->config['appid'] . '/topic:subscribe', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getToken()
            ],
            'json' => [
                'topic' => $alias,
                'tokenArray' => [$regId]
            ]
        ]);
        print_r($this->parseBody($res));
    }

    public function unsetAlias($alias)
    {
        $client = new Client();
        $res = $client->post('https://push-api.cloud.huawei.com/v1/' . $this->config['appid'] . '/topic:truncate', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getToken()
            ],
            'json' => [
                'topic' => $alias,
            ]
        ]);
        print_r($this->parseBody($res));
    }

    public function setTag($tag, $regId)
    {
        $client = new Client();
        $res = $client->post('https://push-api.cloud.huawei.com/v1/' . $this->config['appid'] . '/topic:subscribe', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getToken()
            ],
            'json' => [
                'topic' => $tag,
                'tokenArray' => [$regId]
            ]
        ]);
        print_r($this->parseBody($res));
    }

    public function unsetTag($tag, $regId)
    {
        $client = new Client();
        $res = $client->post('https://push-api.cloud.huawei.com/v1/' . $this->config['appid'] . '/topic:unsubscribe', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getToken()
            ],
            'json' => [
                'topic' => $tag,
                'tokenArray' => [$regId]
            ]
        ]);
        print_r($this->parseBody($res));
    }

    private function execPush($token, $topic)
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
                            'title' => $this->title,
                            'body' => $this->content,
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

    private function execPushBackground($token, $topic)
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
                        'title' => $this->title,
                        'body' => $this->content,
                    ], $this->extra), JSON_UNESCAPED_UNICODE),
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
