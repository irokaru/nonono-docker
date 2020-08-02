<?php

namespace App\Lib;

class CommonUtil
{
    public static function isTesting(): bool
    {
        return getenv('APP_ENV') === 'testint';
    }
}
