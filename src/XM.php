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
        Constants::setBundleId($this->config['ios']['package']);
        Constants::setSecret($this->config['ios']['app_secret']);
        $sender = new Sender();
        $data = $sender->broadcastAll($this->buildIosMessage());
        $res = $this->parseRes($data);
        $res['platform'] = 'ios';
        return $res;
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
        Constants::setBundleId($this->config['ios']['package']);
        Constants::setSecret($this->config['ios']['app_secret']);
        $sender = new Sender();
        $data = $sender->sendToAlias($this->buildIosMessage(), $alias);
        $res = $this->parseRes($data);
        $res['platform'] = 'ios';
        return $res;
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
        Constants::setBundleId($this->config['ios']['package']);
        Constants::setSecret($this->config['ios']['app_secret']);
        $sender = new Sender();
        $data = $sender->broadcast($this->buildIosMessage(), $tag);
        $res = $this->parseRes($data);
        $res['platform'] = 'android';
        return $res;
    }

    private function buildMessage() {
        $message = new Builder();
        $message->title($this->title);  // ????????????title
        $message->description($this->content); // ????????????descption
        $message->passThrough(0);  // ???????????????????????????????????????????????????????????????????????????1,????????????title???descption????????????
        $message->extra('title', $this->title);
        $message->extra('body', $this->content);
        $message->extra(Builder::soundUri, 'default');
        foreach ($this->extra as $k => $v) {
            $message->extra($k, $v);
        }
        $message->extra('notify_effect', '2');
        $message->extra('intent_uri', 'jujiangpush://push?' . http_build_query($this->extra));
        $message->extra(Builder::notifyForeground, 1); // ???????????????????????????????????????????????????????????????????????????????????????????????????????????????0
        $message->notifyId(rand(0,4)); // ???????????????????????????0-4 5????????????????????????????????????????????????????????????????????????????????????????????????
        $message->build();
        return $message;
    }

    private function buildIosMessage() {
        $message = new IOSBuilder();
        $message->title($this->title);  // ????????????title
        $message->body($this->content);
        $message->soundUrl('default');
        $message->extra('title', $this->title);
        $message->extra('body', $this->content);
        foreach ($this->extra as $k => $v) {
            $message->extra($k, $v);
        }
        $message->extra(Builder::notifyForeground, 1); // ???????????????????????????????????????????????????????????????????????????????????????????????????????????????0
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
