<?php

namespace Jujiang\JJpush;

interface IPush
{
    function push($title, $content, array $extra);
    function pushAlias($alias, $title, $content, array $extra);
    function pushTag($alias, $title, $content, array $extra);
}
