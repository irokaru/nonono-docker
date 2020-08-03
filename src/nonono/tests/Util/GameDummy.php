<?php

namespace Tests\Util;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\Util\TestTool;

class GameDummy
{
    /**
     * NGの全パターン
     * @return array
     */
    public static function getNgPatterns()
    {
        return TestTool::combine(
            static::titleNgPatterns(),
            static::releaseDateNgPatterns(),
            static::releaseFlagNgPatterns(),
            static::thumbnailNgPatterns(),
            static::thumbnailNameNgPatterns(),
            static::infomationNgPatterns(),
            static::urlNgPattens()
        );
    }

    // ==============================================================

    protected static function titleNgPatterns()
    {
        return [
            '',
            Str::random(66),
        ];
    }

    protected static function releaseDateNgPatterns()
    {
        return [
            '',
            'hoge',
            '12345',
        ];
    }

    protected static function releaseFlagNgPatterns()
    {
        return [
            '',
            'aaa',
            12345,
        ];
    }

    protected static function thumbnailNgPatterns()
    {
        return [
            [UploadedFile::fake()->image('bigest.png', 1000, 1000)->size(2049)],
            [UploadedFile::fake()->create('document.pdf', 1000)],
            '',
            123,
        ];
    }

    protected static function thumbnailNameNgPatterns()
    {
        return [
            '',
            Str::random(66),
        ];
    }

    protected static function categoryNgPatterns()
    {
        return [
            '',
            Str::random(66),
        ];
    }

    protected static function infomationNgPatterns()
    {
        return [
            Str::random(258),
        ];
    }

    protected static function urlNgPattens()
    {
        return [
            '',
            Str::random(258),
        ];
    }
}
