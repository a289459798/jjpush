<?php

namespace Jujiang\JJpush;

use GuzzleHttp\Client;

class Vivo extends BasePush
{

    function pushAndroid($title, $content, array $extra)
    {
        $this->pushAndroidTag('all-member', $title, $content, $extra);
    }

    function pushAndroidAlias($alias, $title, $content, array $extra)
    {
        $client = new Client();
        $res = $client->post('https://api-push.vivo.com.cn/message/send', [
            'headers' => [
                'authToken' => $this->getToken(),
                'Content-Type' => 'application/json'
            ],
            'json' => array_merge($this->getData($title, $content, $extra), ['regId' => $alias])
        ]);
        print_r($this->parseBody($res));
    }

    function pushAndroidTag($tag, $title, $content, array $extra)
    {
        $client = new Client();
        $res = $client->post('https://api-push.vivo.com.cn/message/tagPush', [
            'headers' => [
                'authToken' => $this->getToken(),
                'Content-Type' => 'application/json'
            ],
            'json' => array_merge($this->getData($title, $content, $extra), ['tagExpression' => ['andTags' => [$tag]]])
        ]);
        print_r($this->parseBody($res));
    }

    private function getData($title, $content, array $extra)
    {
        return [
            'notifyType' => 3,
            'title' => $title,
            'content' => $content,
            'skipType' => 4,
            'skipContent' => json_encode(array_merge([
                'title' => $title,
                'body' => $content,
            ], $extra), JSON_UNESCAPED_UNICODE),
            'clientCustomMap' => array_merge([
                'title' => $title,
                'body' => $content,
            ], $extra),
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
}
