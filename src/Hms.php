<?php

namespace Jujiang\JJpush;

use GuzzleHttp\Client;

class Hms extends BasePush
{

    function pushAndroid()
    {
        $client = new Client();
        $client->post('https://push-api.cloud.huawei.com/v1/appid/messages:send', [
           'header' => [
               'Content-Type' => 'application/json',
               'Authorization' => $this->getToken()
           ]
        ]);
    }

    private function getToken() {
        $client = new Client();
        $client->post('https://oauth-login.cloud.huawei.com/oauth2/v3/token', [
            'header' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'body' => [
                'grant_type' => 'client_credentials',
                'client_id' => '',
                'client_secret' => '',
            ]
        ]);
    }
}
