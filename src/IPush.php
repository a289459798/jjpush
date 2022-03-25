<?php

namespace Jujiang\JJpush;

interface IPush
{
    function push();
    function pushAlias($alias);
    function pushTag($alias);
    function setAlias($alias, $regId);
    function unsetAlias($alias);
    function setTag($tag, $regId);
    function unsetTag($tag, $regId);
}
