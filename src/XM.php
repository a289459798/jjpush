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
        $res = $sender->broadcastAll($this->buildMessage());
        print_r($res);
    }

    public function pushIos()
    {
        Constants::setBundleId($this->config['ios']['package']);
        Constants::setSecret($this->config['ios']['app_secret']);
        $sender = new Sender();
        $res = $sender->broadcastAll($this->buildIosMessage());
        print_r($res);
    }

    function pushAndroidAlias($alias)
    {
        Constants::setPackage($this->config['android']['package']);
        Constants::setSecret($this->config['android']['app_secret']);
        $sender = new Sender();
        $res = $sender->sendToAlias($this->buildMessage(), $alias);
        print_r($res);
    }

    public function pushIosAlias($alias)
    {
        Constants::setBundleId($this->config['ios']['package']);
        Constants::setSecret($this->config['ios']['app_secret']);
        $sender = new Sender();
        $res = $sender->sendToAlias($this->buildIosMessage(), $alias);
        print_r($res);
    }

    function pushAndroidTag($tag)
    {
        Constants::setPackage($this->config['android']['package']);
        Constants::setSecret($this->config['android']['app_secret']);
        $sender = new Sender();
        $res = $sender->broadcast($this->buildMessage(), $tag);
        print_r($res);
    }

    public function pushIosTag($tag)
    {
        Constants::setBundleId($this->config['ios']['package']);
        Constants::setSecret($this->config['ios']['app_secret']);
        $sender = new Sender();
        $res = $sender->broadcast($this->buildIosMessage(), $tag);
        print_r($res);
    }

    private function buildMessage() {
        $message = new Builder();
        $message->title($this->title);  // 通知栏的title
        $message->description($this->content); // 通知栏的descption
        $message->passThrough(0);  // 这是一条通知栏消息，如果需要透传，把这个参数设置成1,同时去掉title和descption两个参数
        $message->extra('title', $this->title);
        $message->extra('body', $this->content);
        foreach ($this->extra as $k => $v) {
            $message->extra($k, $v);
        }
        $message->extra(Builder::notifyForeground, 1); // 应用在前台是否展示通知，如果不希望应用在前台时候弹出通知，则设置这个参数为0
        $message->notifyId(2); // 通知类型。最多支持0-4 5个取值范围，同样的类型的通知会互相覆盖，不同类型可以在通知栏并存
        $message->build();
        return $message;
    }

    private function buildIosMessage() {
        $message = new IOSBuilder();
        $message->title($this->title);  // 通知栏的title
        $message->body($this->content);
        $message->extra('title', $this->title);
        $message->extra('body', $this->content);
        foreach ($this->extra as $k => $v) {
            $message->extra($k, $v);
        }
        $message->extra(Builder::notifyForeground, 1); // 应用在前台是否展示通知，如果不希望应用在前台时候弹出通知，则设置这个参数为0
        $message->build();
        return $message;
    }
}
