<?php

declare(strict_types=1);

/**
 * UMeng.php
 *
 * @author     zhangzy
 * @copyright  Copyright (c) 2022 Shanghai Yu Chong Network Technology Co., Ltd.
 * @version    2022/11/16 上午9:52
 */

namespace Jujiang\JJpush;


use GuzzleHttp\Client;

class UMeng extends BasePush
{
    public function pushIos()
    {
        $res = $this->parseRes($this->pushMessage('broadcast'));
        $res['platform'] = 'ios';
        return $res;
    }

    public function pushIosAlias($alias)
    {
        $res = $this->parseRes($this->pushMessage('alias', $alias));
        $res['platform'] = 'ios';
        return $res;
    }

    public function pushIosTag($tag)
    {
        $res = $this->parseRes($this->pushMessage('tag'));
        $res['platform'] = 'ios';
        return $res;
    }

    private function pushMessage($type, $params)
    {
        $data = [
            'appkey' => $this->config['app_key'],
            'timestamp' => time() * 1000,
        ];

        if ($type == 'alias') {
            $data['type'] = 'customizedcast';
            $data['alias_type'] = 'default';
            $data['alias'] = $params;
        } elseif ($type == 'tag') {
            $data['type'] = 'groupcast';
            $data['filter'] = [
                'where' => [
                    'and' => [
                        ['tag' => $params]
                    ]
                ]
            ];
        } else {
            $data['type'] = 'broadcast';
        }
        $data['payload'] = array_merge([
            'aps' => [
                'alert' => [
                    'title' => $this->title,
                    'subtitle' => '',
                    'body' => $this->content,
                ]
            ],
            'badge' => 1,
        ], $this->extra);
        $data['production_mode'] = !$this->config['env'];
        $appSecret = $this->config['app_secret'];
        $sign = md5('POSThttps://msgapi.umeng.com/api/send' . json_encode($data) . $appSecret);
        $client = new Client();
        $res = $client->post('https://msgapi.umeng.com/api/send?sign=' . $sign, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'json' => $data
        ]);
        return $this->parseBody($res);
    }

    private function parseRes($data)
    {
        $res = [];
        $res['platform'] = 'android';
        $res['result'] = json_encode($data, JSON_UNESCAPED_UNICODE);
        if ($data['ret'] == 'SUCCESS') {
            $res['code'] = 0;
            $res['id'] = $data['data']['task_id'];
        } else {
            $res['code'] = -1;
        }
        return $res;
    }

    function pushAndroid()
    {
    }

    function pushAndroidAlias($alias)
    {
    }

    function pushAndroidTag($tag)
    {
    }
}
