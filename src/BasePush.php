<?php

namespace Jujiang\JJpush;

abstract class BasePush implements IPush
{
    public function push()
    {
        $this->pushAndroid();
        $this->pushIos();
    }

    abstract function pushAndroid();

    function pushIos()
    {

    }
}
