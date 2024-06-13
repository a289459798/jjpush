<?php

namespace Jujiang\JJpush;

class PushManage
{
    private $pushLst = [];

    public function __construct(array $config, $schema = '')
    {
        foreach ($config as $key => $value) {
            if ($key == 'hms') {
                $this->pushLst[$key] = new Hms($value, $schema);
            } elseif ($key == 'xm') {
                $this->pushLst[$key] = new XM($value, $schema);
            } elseif ($key == 'vivo') {
                $this->pushLst[$key] = new Vivo($value, $schema);
            } elseif ($key == 'oppo') {
                $this->pushLst[$key] = new Oppo($value, $schema);
            } elseif ($key == 'umeng') {
                $this->pushLst[$key] = new UMeng($value, $schema);
            }
        }

    }

    public function setTitle(string $title){
        foreach ($this->pushLst as $push) {
            /**
             * @var BasePush $push
             */
            $push->setTitle($title);
        }
        return $this;
    }

    public function setContent(string $content){
        foreach ($this->pushLst as $push) {
            /**
             * @var BasePush $push
             */
            $push->setContent($content);
        }
        return $this;
    }

    public function setExtra(array $extra){
        foreach ($this->pushLst as $push) {
            /**
             * @var BasePush $push
             */
            $push->setExtra($extra);
        }
        return $this;
    }

    public function push()
    {
        $res = [];
        foreach ($this->pushLst as $k => $push) {
            /**
             * @var IPush $push
             */
            $res[$k] = $push->push();
        }
        return $res;
    }

    public function pushAlias($alias)
    {
        $res = [];
        foreach ($this->pushLst as $k => $push) {
            /**
             * @var IPush $push
             */
            $res[$k] = $push->pushAlias($alias);
        }
        return $res;
    }

    public function pushTag($tag)
    {
        $res = [];
        foreach ($this->pushLst as $k => $push) {
            /**
             * @var IPush $push
             */
            $res[$k] = $push->pushTag($tag);
        }
        return $res;
    }

    /**
     * @param $alias
     * @param $regId
     * @param $brand 品牌
     */
    public function setAlias($alias, $regId, $brand = 'xm')
    {
        if (isset($this->pushLst[$brand])) {
            $this->pushLst[$brand]->setAlias($alias, $regId);
        } elseif(isset($this->pushLst['xm'])) {
            $this->pushLst['xm']->setAlias($alias, $regId);
        }
    }

    public function unsetAlias($alias, $brand)
    {
        if (isset($this->pushLst[$brand])) {
            $this->pushLst[$brand]->unsetAlias($alias);
        } elseif(isset($this->pushLst['xm'])) {
            $this->pushLst['xm']->unsetAlias($alias);
        }
    }

    public function setTag($tag, $regId, $brand)
    {
        if (isset($this->pushLst[$brand])) {
            $this->pushLst[$brand]->setTag($tag, $regId);
        } elseif(isset($this->pushLst['xm'])) {
            $this->pushLst['xm']->setTag($tag, $regId);
        }
    }

    public function unsetTag($tag, $regId, $brand)
    {
        if (isset($this->pushLst[$brand])) {
            $this->pushLst[$brand]->unsetTag($tag, $regId);
        } elseif(isset($this->pushLst['xm'])) {
            $this->pushLst['xm']->unsetTag($tag, $regId);
        }
    }
}
