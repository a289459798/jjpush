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
        $res = [];
        $android = $this->pushAndroid();
        $ios = $this->pushIos();
        if (!empty($android)) {
            $res[] = $android;
        }
        if (!empty($ios)) {
            $res[] = $ios;
        }
        return $res;
    }

    abstract function pushAndroid();

    function pushIos()
    {

    }

    public function pushAlias($alias)
    {
        $res = [];
        $android = $this->pushAndroidAlias($alias);
        $ios = $this->pushIosAlias($alias);
        if (!empty($android)) {
            $res[] = $android;
        }
        if (!empty($ios)) {
            $res[] = $ios;
        }
        return $res;
    }

    abstract function pushAndroidAlias($alias);

    function pushIosAlias($alias)
    {

    }

    public function pushTag($tag)
    {
        $res = [];
        $android = $this->pushAndroidTag($tag);
        $ios = $this->pushIosTag($tag);
        if (!empty($android)) {
            $res[] = $android;
        }
        if (!empty($ios)) {
            $res[] = $ios;
        }
        return $res;
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
