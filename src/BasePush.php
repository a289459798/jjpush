<?php

namespace Jujiang\JJpush;

use Psr\Http\Message\ResponseInterface;

abstract class BasePush implements IPush
{
    protected $config = [];
    protected $title;
    protected $content;
    protected $extra = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function setExtra(array $extra)
    {
        $this->extra = $extra;
    }

    public function push()
    {
        $this->pushAndroid();
        $this->pushIos();
    }

    abstract function pushAndroid();

    function pushIos()
    {

    }

    public function pushAlias($alias)
    {
        $this->pushAndroidAlias($alias);
        $this->pushIosAlias($alias);
    }

    abstract function pushAndroidAlias($alias);

    function pushIosAlias($alias)
    {

    }

    public function pushTag($tag)
    {
        $this->pushAndroidTag($tag);
        $this->pushIosTag($tag);
    }

    abstract function pushAndroidTag($tag);

    public function pushIosTag($tag)
    {

    }

    public function setAlias($alias, $regId)
    {
        echo '暂不支持';
    }

    public function unsetAlias($alias)
    {
        echo '暂不支持';
    }

    public function setTag($tag, $regId)
    {
        echo '暂不支持';
    }

    public function unsetTag($tag, $regId)
    {
        echo '暂不支持';
    }

    protected function parseBody(ResponseInterface $res)
    {
        return json_decode($res->getBody()->getContents(), true);
    }
}
