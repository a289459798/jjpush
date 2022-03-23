<?php

namespace Jujiang\JJpush;

use Psr\Http\Message\ResponseInterface;

abstract class BasePush implements IPush
{
    protected $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function push($title, $content, array $extra)
    {
        $this->pushAndroid($title, $content, $extra);
        $this->pushIos($title, $content, $extra);
    }

    abstract function pushAndroid($title, $content, array $extra);

    function pushIos($title, $content, array $extra)
    {

    }

    public function pushAlias($alias, $title, $content, array $extra)
    {
        $this->pushAndroidAlias($alias, $title, $content, $extra);
        $this->pushIosAlias($alias, $title, $content, $extra);
    }

    abstract function pushAndroidAlias($alias, $title, $content, array $extra);

    function pushIosAlias($alias, $title, $content, array $extra)
    {

    }

    public function pushTag($tag, $title, $content, array $extra)
    {
        $this->pushAndroidTag($tag, $title, $content, $extra);
        $this->pushIosTag($tag, $title, $content, $extra);
    }

    abstract function pushAndroidTag($tag, $title, $content, array $extra);

    function pushIosTag($tag, $title, $content, array $extra)
    {

    }

    protected function parseBody(ResponseInterface $res)
    {
        return json_decode($res->getBody()->getContents(), true);
    }
}
