<?php
require __DIR__ . '/vendor/autoload.php';

use \Jujiang\JJpush\PushManage;

//    'hms' => [
//        'appid' => '105796035',
//        'client_id' => '105796035',
//        'client_secret' => '525c240ede2166fca2ee7e7f927bee034f4c5f65e6d2f754bca0bcc1c1932d9b',
//    ],
$push = new PushManage([
    'xm' => [
        'package' => 'com.jujiangtest.push',
        'app_secret' => '4Xr7RjIYmcYvM4AXhCmn1A=='
    ],
]);


//$push->push('测试标题', '测试内容', ['a' => 1, 'b' => 2]);
$push->pushAlias('qweasdzxc', '测试标题11', '测试内容22', ['a' => 6, 'b' => 3]);
//$push->pushTag('qweasdzxc', '测试标题22', '测试内容33', ['a' => 77, 'b' => 88]);
