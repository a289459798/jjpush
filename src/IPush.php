<?php

namespace Jujiang\JJpush;

interface IPush
{
    function push($title, $content, array $extra);
    function pushAlias($alias, $title, $content, array $extra);
    function pushTag($alias, $title, $content, array $extra);
    function setAlias($alias, $regId);
    function unsetAlias($alias);
    function setTag($tag, $regId);
    function unsetTag($tag, $regId);
}
