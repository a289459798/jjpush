<?php

namespace Jujiang\JJpush;

use GuzzleHttp\Client;

class Vivo extends BasePush
{

    function pushAndroid()
    {
        return $this->pushAndroidTag('all-member');
    }

    function pushAndroidAlias($alias)
    {
        $client = new Client();
        $res = $client->post('https://api-push.vivo.com.cn/message/send', [
            'headers' => [
                'authToken' => $this->getToken(),
                'Content-Type' => 'application/json'
            ],
            'json' => array_merge($this->getData(), ['alias' => $alias])
        ]);
        $data = $this->parseBody($res);
        return $this->parseRes($data);
    }

    function pushAndroidTag($tag)
    {
        $client = new Client();
        $res = $client->post('https://api-push.vivo.com.cn/message/tagPush', [
            'headers' => [
                'authToken' => $this->getToken(),
                'Content-Type' => 'application/json'
            ],
            'json' => array_merge($this->getData(), ['tagExpression' => ['andTags' => [$tag]]])
        ]);
        $data = $this->parseBody($res);
        return $this->parseRes($data);
    }

    private function getData()
    {
        return [
            'notifyType' => 3,
            'title' => $this->title,
            'content' => $this->content,
            'skipType' => 4,
            'category' => 'DEVICE_REMINDER',
            'skipContent' => $this->schema . '?' . http_build_query($this->extra),
            'clientCustomMap' => array_merge([
                'title' => $this->title,
                'body' => $this->content,
            ], $this->extra),
            'pushMode' => $this->config['env'],
            'requestId' => uniqid()
        ];
    }

    private function getToken()
    {
        $client = new Client();
        $time = time() * 1000;
        $res = $client->post('https://api-push.vivo.com.cn/message/auth', [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'appId' => $this->config['appid'],
                'appKey' => $this->config['app_key'],
                'timestamp' => $time,
                'sign' => $this->getSign($time)
            ]
        ]);
        $body = $this->parseBody($res);
        return $body['authToken'];
    }

    private function getSign($time)
    {
        return strtolower(md5(trim($this->config['appid'] . $this->config['app_key'] . $time . $this->config['app_secret'])));
    }

    private function parseRes($data)
    {
        $res = [];
        $res['platform'] = 'android';
        $res['result'] = json_encode($data, JSON_UNESCAPED_UNICODE);
        if ($data['result'] == '0') {
            $res['code'] = 0;
            $res['id'] = $data['taskId'];
        } else {
            $res['code'] = -1;
        }
        return $res;
    }
}
