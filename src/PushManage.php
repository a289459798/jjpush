<?php

namespace Jujiang\JJpush;

class PushManage
{
    private $pushLst = [];

    public function __construct(array $config)
    {
        foreach ($config as $key => $value) {
            if ($key == 'hms') {
                $this->pushLst[$key] = new Hms($value);
            } elseif ($key == 'xm') {
                $this->pushLst[$key] = new XM($value);
            } elseif ($key == 'vivo') {
                $this->pushLst[$key] = new Vivo($value);
            } elseif ($key == 'oppo') {
                $this->pushLst[$key] = new Oppo($value);
            }
        }

    }

    /**
     * @param $title
     * @param $content
     * @param array $extra 扩展字段，客户端受到后自定义处理
     */
    public function push($title, $content, array $extra)
    {
        foreach ($this->pushLst as $push) {
            /**
             * @var IPush $push
             */
            $push->push($title, $content, $extra);
        }
    }

    public function pushAlias($alias, $title, $content, array $extra)
    {
        foreach ($this->pushLst as $push) {
            /**
             * @var IPush $push
             */
            $push->pushAlias($alias, $title, $content, $extra);
        }
    }

    public function pushTag($tag, $title, $content, array $extra)
    {
        foreach ($this->pushLst as $push) {
            /**
             * @var IPush $push
             */
            $push->pushTag($tag, $title, $content, $extra);
        }
    }

    /**
     * @param $alias
     * @param $regId
     * @param $brand 品牌
     */
    public function setAlias($alias, $regId, $brand)
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
