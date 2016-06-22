<?php

namespace EM\CssCompiler\Handler;

use Composer\EventDispatcher\Event;
use EM\CssCompiler\ScriptHandler as Handler;

/**
 * @deprecated
 */
class ScriptHandler extends Handler
{
    public static function compileCSS(Event $event)
    {
        static::generateCSS($event);
    }
}
