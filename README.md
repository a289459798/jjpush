# jujiang/push

## Getting started

`$ composer require jujiang/jjpush`

## Usage

```php
// 目前支持 华为，小米，vivo，oppo，如果只用部分，config删除对应配置
$push = new PushManage([
        'hms' => [
        'appid' => 'xxxx',
        'client_id' => 'xxxx',
        'client_secret' => 'xxxx',
    ],
    'xm' => [
        'android' => [
            'package' => 'xxx',
            'app_secret' => 'xxxx'
        ],
        'ios' => [
            'package' => 'cxxxx',
            'app_secret' => 'xxxx'
        ]
    ],
    'vivo' => [
        'appid' => 'xxxxx',
        'app_key' => 'xxxx',
        'app_secret' => 'xxxxxx',
        'env' => 1, // 0 正式  1测试
    ],
    'oppo' => [
        'app_key' => 'xxxx',
        'app_secret' => 'xxxxxx',
    ]
]);

// 广播
$push->setTitle('测试标题')->setContent('测试内容')->setExtra(['a' => 1, 'b' => 2])->push();

// 别名发送
$push->setTitle('您收到一条消息')->setContent('今天天气真好')->setExtra(['a' => 444, 'b' => 55555])->pushAlias('aaaaaaa');

// 标签发送
$push->setTitle('测试标题22')->setContent('测试内容33')->setExtra(['a' => 1, 'b' => 2])->pushTag('aaaaaaa');

// 设置别名 OPPO
$push->setAlias("aaaaaaa", "CN_d3f9b29f401457966009a3feb9849e0f", 'oppo');

// 设置tag OPPO
$push->unsetAlias("aaaaaaa", 'oppo');

// 取消别名 OPPO
$push->setTag("aaaaaaa", 'CN_d3f9b29f401457966009a3feb9849e0f', 'oppo');

// 取消tag OPPO
$push->unsetTag("aaaaaaa", 'CN_d3f9b29f401457966009a3feb9849e0f', 'oppo');

```
