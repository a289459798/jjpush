<?php

namespace Jujiang\JJpush;

use Jujiang\JJpush\xmpush\Builder;
use Jujiang\JJpush\xmpush\Constants;
use Jujiang\JJpush\xmpush\IOSBuilder;
use Jujiang\JJpush\xmpush\Sender;

class XM extends BasePush
{
    public function __construct(array $config)
    {
        parent::__construct($config);

    }

    function pushAndroid()
    {
        Constants::setPackage($this->config['android']['package']);
        Constants::setSecret($this->config['android']['app_secret']);
        $sender = new Sender();
        $data = $sender->broadcastAll($this->buildMessage());
        $res = $this->parseRes($data);
        $res['platform'] = 'android';
        return $res;
    }

    public function pushIos()
    {
//        Constants::setBundleId($this->config['ios']['package']);
//        Constants::setSecret($this->config['ios']['app_secret']);
//        $sender = new Sender();
//        $data = $sender->broadcastAll($this->buildIosMessage());
//        $res = $this->parseRes($data);
//        $res['platform'] = 'ios';
//        return $res;
    }

    function pushAndroidAlias($alias)
    {
        Constants::setPackage($this->config['android']['package']);
        Constants::setSecret($this->config['android']['app_secret']);
        $sender = new Sender();
        $data = $sender->sendToAlias($this->buildMessage(), $alias);
        $res = $this->parseRes($data);
        $res['platform'] = 'android';
        return $res;
    }

    public function pushIosAlias($alias)
    {
//        Constants::setBundleId($this->config['ios']['package']);
//        Constants::setSecret($this->config['ios']['app_secret']);
//        $sender = new Sender();
//        $data = $sender->sendToAlias($this->buildIosMessage(), $alias);
//        $res = $this->parseRes($data);
//        $res['platform'] = 'ios';
//        return $res;
    }

    function pushAndroidTag($tag)
    {
        Constants::setPackage($this->config['android']['package']);
        Constants::setSecret($this->config['android']['app_secret']);
        $sender = new Sender();
        $data = $sender->broadcast($this->buildMessage(), $tag);
        $res = $this->parseRes($data);
        $res['platform'] = 'android';
        return $res;
    }

    public function pushIosTag($tag)
    {
//        Constants::setBundleId($this->config['ios']['package']);
//        Constants::setSecret($this->config['ios']['app_secret']);
//        $sender = new Sender();
//        $data = $sender->broadcast($this->buildIosMessage(), $tag);
//        $res = $this->parseRes($data);
//        $res['platform'] = 'android';
//        return $res;
    }

    private function buildMessage() {
        $message = new Builder();
        $message->title($this->title);  // 通知栏的title
        $message->description($this->content); // 通知栏的descption
        $message->passThrough(0);  // 这是一条通知栏消息，如果需要透传，把这个参数设置成1,同时去掉title和descption两个参数
        $message->extra('title', $this->title);
        $message->extra('body', $this->content);
        $message->extra(Builder::soundUri, 'default');
        foreach ($this->extra as $k => $v) {
            $message->extra($k, $v);
        }
        $message->extra('notify_effect', '2');
        $message->extra('intent_uri', 'jujiangpush://push?' . http_build_query($this->extra));
        $message->extra(Builder::notifyForeground, 1); // 应用在前台是否展示通知，如果不希望应用在前台时候弹出通知，则设置这个参数为0
        $message->notifyId(rand(0,4)); // 通知类型。最多支持0-4 5个取值范围，同样的类型的通知会互相覆盖，不同类型可以在通知栏并存
        $message->build();
        return $message;
    }

    private function buildIosMessage() {
        $message = new IOSBuilder();
        $message->title($this->title);  // 通知栏的title
        $message->body($this->content);
        $message->soundUrl('default');
        $message->extra('title', $this->title);
        $message->extra('body', $this->content);
        foreach ($this->extra as $k => $v) {
            $message->extra($k, $v);
        }
        $message->extra(Builder::notifyForeground, 1); // 应用在前台是否展示通知，如果不希望应用在前台时候弹出通知，则设置这个参数为0
        $message->build();
        return $message;
    }

    private function parseRes($data) {
        $res = [];
        $res['platform'] = 'android';
        if ($data->getErrorCode() == 0) {
            $res['code'] = 0;
            $res['id'] = $data->getRaw()['trace_id'];
        } else {
            $res['code'] = -1;
        }
        $res['result'] = json_encode($data->getRaw(), JSON_UNESCAPED_UNICODE);
        return $res;
    }
}
