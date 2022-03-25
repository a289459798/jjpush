<?php
require __DIR__ . '/vendor/autoload.php';

use \Jujiang\JJpush\PushManage;


$push = new PushManage([
//        'hms' => [
//        'appid' => '105796035',
//        'client_id' => '105796035',
//        'client_secret' => '525c240ede2166fca2ee7e7f927bee034f4c5f65e6d2f754bca0bcc1c1932d9b',
//    ],
//    'xm' => [
//        'android' => [
//            'package' => 'com.jujiangtest.push',
//            'app_secret' => '4Xr7RjIYmcYvM4AXhCmn1A=='
//        ],
//        'ios' => [
//            'package' => 'com.ichong.push',
//            'app_secret' => 'iHY9Y4BVCdZYHT+jRKe1UQ=='
//        ]
//    ],
//    'vivo' => [
//        'appid' => '105549256',
//        'app_key' => '9a550c8362bad05200d9e132b51520ee',
//        'app_secret' => 'd26419be-a730-47e4-84f4-807d1a675fd0',
//        'env' => 1, // 0 正式  1测试
//    ],
    'oppo' => [
        'app_key' => '56bdcf4739314192a921cd60a16e38e1',
        'app_secret' => '97e42b573e974c52abe3865345a6a229',
    ]
]);


//$push->setTitle('测试标题')->setContent('测试内容')->setExtra(['a' => 1, 'b' => 2])->push();
//$push->setTitle('您收到一条消息')->setContent('今天天气真好')->setExtra(['a' => 444, 'b' => 55555])->pushAlias('aaaaaaa');
$push->setTitle('测试标题22')->setContent('测试内容33')->setExtra(['a' => 1, 'b' => 2])->pushTag('aaaaaaa');
//$push->setAlias("aaaaaaa", "CN_d3f9b29f401457966009a3feb9849e0f", 'oppo');
//$push->unsetAlias("aaaaaaa", 'oppo');
//$push->setTag("aaaaaaa", 'CN_d3f9b29f401457966009a3feb9849e0f', 'oppo');
//$push->unsetTag("aaaaaaa", 'CN_d3f9b29f401457966009a3feb9849e0f', 'oppo');
