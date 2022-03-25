<?php

namespace Jujiang\JJpush;

use GuzzleHttp\Client;

class Oppo extends BasePush
{

    function pushAndroid()
    {
        $client = new Client();
        $res = $client->post('https://api.push.oppomobile.com/server/v1/message/notification/broadcast', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'auth_token' => $this->getToken()
            ],
            'form_params' => [
                'message_id' => $this->buildMessage(),
                'target_type' => 1,
            ]
        ]);
        print_r($this->parseBody($res));
    }

    function pushAndroidAlias($alias)
    {
        $client = new Client();
        $res = $client->post('https://api.push.oppomobile.com/server/v1/message/notification/broadcast', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'auth_token' => $this->getToken()
            ],
            'form_params' => [
                'message_id' => $this->buildMessage(),
                'target_type' => 5,
                'target_value' => $alias
            ]
        ]);
        print_r($this->parseBody($res));
    }

    function pushAndroidTag($tag)
    {
        $client = new Client();
        $res = $client->post('https://api.push.oppomobile.com/server/v1/message/notification/broadcast', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'auth_token' => $this->getToken()
            ],
            'form_params' => [
                'message_id' => $this->buildMessage(),
                'target_type' => 6,
                'target_value' => '{ "and" : ["' . $tag . '"]}'
            ]
        ]);
        print_r($this->parseBody($res));
    }

    public function setAlias($alias, $regId)
    {
        $client = new Client();
        $res = $client->post('https://api-device.push.heytapmobi.com/server/v1/device/set_alias', [
            'headers' => [
                'Content-Type' => 'application/json',
                'auth_token' => $this->getToken()
            ],
            'json' => [
                'registration_id' => $regId,
                'alias' => $alias
            ]
        ]);
        print_r($this->parseBody($res));
    }

    public function unsetAlias($alias)
    {
        $client = new Client();
        $res = $client->post('https://api-device.push.heytapmobi.com/server/v1/device/delete_alias', [
            'headers' => [
                'Content-Type' => 'application/json',
                'auth_token' => $this->getToken()
            ],
            'json' => [
                'alias' => $alias
            ]
        ]);
        print_r($this->parseBody($res));
    }

    public function setTag($tag, $regId)
    {
        $client = new Client();
        $res = $client->post('https://api-device.push.heytapmobi.com/server/v1/device/subscribe_tags', [
            'headers' => [
                'Content-Type' => 'application/json',
                'auth_token' => $this->getToken()
            ],
            'json' => [
                'registration_id' => $regId,
                'tags' => $tag
            ]
        ]);
        print_r($this->parseBody($res));
    }

    public function unsetTag($tag, $regId)
    {
        $client = new Client();
        $res = $client->post('https://api-device.push.heytapmobi.com/server/v1/device/unsubscribe_tags', [
            'headers' => [
                'Content-Type' => 'application/json',
                'auth_token' => $this->getToken()
            ],
            'json' => [
                'registration_id' => $regId,
                'tags' => $tag
            ]
        ]);
        print_r($this->parseBody($res));
    }

    private function buildMessage()
    {
        $client = new Client();
        $res = $client->post('https://api.push.oppomobile.com/server/v1/message/notification/save_message_content', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'auth_token' => $this->getToken()
            ],
            'form_params' => [
                'title' => ($this->title),
                'sub_title' => '',
                'content' => ($this->content),
                'click_action_type' => 5,
                'click_action_url' => 'jujiangpush://push?' . http_build_query($this->extra),
                'action_parameters' => json_encode(array_merge([
                    'title' => ($this->title),
                    'body' => ($this->content),
                ], $this->extra), JSON_UNESCAPED_UNICODE)
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
