<?php

namespace Jujiang\JJpush;

class PushManage
{
    private $pushLst = [];

    public function __construct(array $config)
    {
        foreach ($config as $key => $value) {
            if ($key == 'hms') {
                $this->pushLst[] = new Hms($value);
            } elseif ($key == 'xm') {
                $this->pushLst[] = new XM($value);
            }
        }

    }

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
}
