<?php

namespace Jujiang\JJpush;

use GuzzleHttp\Client;

class Hms extends BasePush
{
    function pushAndroid()
    {
        $token = $this->getToken();
        $data = $this->execPush($token, 'all-member');
        $this->execPushBackground($token, 'all-member');
        return $this->parseRes($data);
    }

    function pushAndroidAlias($alias)
    {
        $token = $this->getToken();
        $data = $this->execPush($token, $alias);
        $this->execPushBackground($token, $alias);
        return $this->parseRes($data);
    }

    function pushAndroidTag($tag)
    {
        $token = $this->getToken();
        $data = $this->execPush($token, $tag);
        $this->execPushBackground($token, $tag);
        return $this->parseRes($data);
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
                        'category' => $this->extra['hms_category'] ?? '',
                        'notification' => [
                            'title' => $this->title,
                            'body' => $this->content,
                            'click_action' => [
                                'type' => 1,
                                'intent' => $this->schema . '?' . http_build_query($this->extra)
                            ],
                        ]
                    ],
                    'topic' => $topic
                ]
            ]
        ]);
        return $this->parseBody($res);
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
                    'android' => [
                        'category' => $this->extra['hms_category'] ?? '',
                    ],
                    'topic' => $topic
                ]
            ]
        ]);
        return $this->parseBody($res);
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

    private function parseRes($data)
    {
        $res = [];
        $res['platform'] = 'android';
        $res['result'] = json_encode($data, JSON_UNESCAPED_UNICODE);
        if ($data['code'] == '80000000') {
            $res['code'] = 0;
            $res['id'] = $data['requestId'];
        } else {
            $res['code'] = -1;
        }
        return $res;
    }
}
