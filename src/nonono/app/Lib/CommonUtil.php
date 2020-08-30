<?php

namespace App\Lib;

class CommonUtil
{
    /**
     * 現在がテスト中かどうかを返す
     * @return bool
     */
    public static function isTesting(): bool
    {
        return getenv('APP_ENV') === 'testing';
    }

    /**
     * アクセス元のIPを返す
     * @return string
     */
    public static function requestOriginalIp(): string
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR'] ?: '';
    }
}
