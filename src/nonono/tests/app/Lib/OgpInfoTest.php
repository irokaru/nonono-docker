<?php

namespace Tests\App\Lib;

use App\Lib\OgpInfo;

use Tests\TestCase;

class OgpInfoTest extends TestCase
{
    public function test_get_original_title()
    {
        $suites = [
            // expect, ogp
            ['title',         ['title', null, null, null]],
            ['title##',       ['title##', 'hoge', null, null]],
            ['',              ['', 'hoge', 'aaa', null]],
            ['before##after', ['before##after', 'hoge', 'aaa', 'hogehoge']],
        ];

        foreach ($suites as $suite) {
            $ogp_info = new OgpInfo(static::_p(...$suite[1]));
            $this->assertEquals($suite[0], $ogp_info->getOriginalTitle());
        }
    }

    public function test_get_title()
    {
        $suites = [
            // expect, ogp
            ['title', ['title', null, null, null]],
            ['',      ['method##', 'hoge', null, null]],
            ['title', ['##title', 'hoge', null, null]],
            ['',      ['', 'hoge', 'aaa', null]],
            ['after', ['before##after', 'hoge', 'aaa', 'hogehoge']],
        ];

        foreach ($suites as $suite) {
            $ogp_info = new OgpInfo(static::_p(...$suite[1]));
            $this->assertEquals($suite[0], $ogp_info->getTitle());
        }
    }

    public function test_method_in_title()
    {
        $suites = [
            // expect, ogp
            ['',       ['title', null, null, null]],
            ['method', ['method##', 'hoge', null, null]],
            ['',       ['##title', 'hoge', null, null]],
            ['',       ['', 'hoge', 'aaa', null]],
            ['before', ['before##after', 'hoge', 'aaa', 'hogehoge']],
        ];

        foreach ($suites as $suite) {
            $ogp_info = new OgpInfo(static::_p(...$suite[1]));
            $this->assertEquals($suite[0], $ogp_info->getMethodInTitle());
        }
    }

    public function test_has_method_in_title()
    {
        $suites = [
            // expect, ogp
            [false, ['title', null, null, null]],
            [true,  ['method##', 'hoge', null, null]],
            [false, ['##title', 'hoge', null, null]],
            [false, ['', 'hoge', 'aaa', null]],
            [true,  ['before##after', 'hoge', 'aaa', 'hogehoge']],
        ];

        foreach ($suites as $suite) {
            $ogp_info = new OgpInfo(static::_p(...$suite[1]));
            $this->assertEquals($suite[0], $ogp_info->hasMethodInTitle());
        }
    }

    public function test_get_original_description()
    {
        $suites = [
            // expect, ogp
            ['desc',       ['title', 'desc', null, null]],
            ['hoge##',     ['title##', 'hoge##', null, null]],
            ['##fuga',     ['title##', '##fuga', null, null]],
            ['',           ['title', '', 'aaa', null]],
            ['hoge##fuga', ['before##after', 'hoge##fuga', 'aaa', 'hogehoge']],
        ];

        foreach ($suites as $suite) {
            $ogp_info = new OgpInfo(static::_p(...$suite[1]));
            $this->assertEquals($suite[0], $ogp_info->getOriginalDescription(), json_encode([$suite, $ogp_info]));
        }
    }

    public function test_get_description()
    {
        $suites = [
            // expect, ogp
            ['desc', ['title', 'desc', null, null]],
            ['',     ['title##', 'hoge##', null, null]],
            ['fuga', ['title##', '##fuga', null, null]],
            ['',     ['title', '', 'aaa', null]],
            ['fuga', ['before##after', 'hoge##fuga', 'aaa', 'hogehoge']],
        ];

        foreach ($suites as $suite) {
            $ogp_info = new OgpInfo(static::_p(...$suite[1]));
            $this->assertEquals($suite[0], $ogp_info->getDescription());
        }
    }


    public function test_method_in_description()
    {
        $suites = [
            // expect, ogp
            ['',     ['title', 'desc', null, null]],
            ['hoge', ['title##', 'hoge##', null, null]],
            ['',     ['title##', '##fuga', null, null]],
            ['',     ['title', '', 'aaa', null]],
            ['hoge', ['before##after', 'hoge##fuga', 'aaa', 'hogehoge']],
        ];

        foreach ($suites as $suite) {
            $ogp_info = new OgpInfo(static::_p(...$suite[1]));
            $this->assertEquals($suite[0], $ogp_info->getMethodInDescription());
        }
    }

    public function test_has_method_in_description()
    {
        $suites = [
            // expect, ogp
            [false, ['title', 'desc', null, null]],
            [true,  ['title##', 'hoge##', null, null]],
            [false,  ['title##', '##fuga', null, null]],
            [false, ['title', '', 'aaa', null]],
            [true,  ['before##after', 'hoge##fuga', 'aaa', 'hogehoge']],
        ];

        foreach ($suites as $suite) {
            $ogp_info = new OgpInfo(static::_p(...$suite[1]));
            $this->assertEquals($suite[0], $ogp_info->hasMethodInDescription());
        }
    }

    public function test_get_original_thumbnail()
    {
        $suites = [
            // expect, ogp
            ['thumb',    ['title', 'desc', 'thumb', null]],
            ['aaa##',    ['title##', 'hoge##', 'aaa##', null]],
            ['',         ['title##', '##fuga', '', null]],
            ['aaa##bbb', ['title', '', 'aaa##bbb', null]],
            ['##aaa',    ['before##after', 'hoge##fuga', '##aaa', 'hogehoge']],
        ];

        foreach ($suites as $suite) {
            $ogp_info = new OgpInfo(static::_p(...$suite[1]));
            $this->assertEquals($suite[0], $ogp_info->getOriginalThumbnail());
        }
    }

    public function test_get_thumbnail()
    {
        $suites = [
            // expect, ogp
            ['thumb', ['title', 'desc', 'thumb', null]],
            ['',      ['title##', 'hoge##', 'aaa##', null]],
            ['',      ['title##', '##fuga', '', null]],
            ['bbb',   ['title', '', 'aaa##bbb', null]],
            ['aaa',   ['before##after', 'hoge##fuga', '##aaa', 'hogehoge']],
        ];

        foreach ($suites as $suite) {
            $ogp_info = new OgpInfo(static::_p(...$suite[1]));
            $this->assertEquals($suite[0], $ogp_info->getThumbnail());
        }
    }

    public function test_method_in_thumbnail()
    {
        $suites = [
            // expect, ogp
            ['',    ['title', 'desc', 'thumb', null]],
            ['aaa', ['title##', 'hoge##', 'aaa##', null]],
            ['',    ['title##', '##fuga', '', null]],
            ['aaa', ['title', '', 'aaa##bbb', null]],
            ['',    ['before##after', 'hoge##fuga', '##aaa', 'hogehoge']],
        ];

        foreach ($suites as $suite) {
            $ogp_info = new OgpInfo(static::_p(...$suite[1]));
            $this->assertEquals($suite[0], $ogp_info->getMethodInThumbnail());
        }
    }

    public function test_has_method_in_thumbnail()
    {
        $suites = [
            // expect, ogp
            [false, ['title', 'desc', 'thumb', null]],
            [true,  ['title##', 'hoge##', 'aaa##', null]],
            [false, ['title##', '##fuga', '', null]],
            [true,  ['title', '', 'aaa##bbb', null]],
            [false, ['before##after', 'hoge##fuga', '##aaa', 'hogehoge']],
        ];

        foreach ($suites as $suite) {
            $ogp_info = new OgpInfo(static::_p(...$suite[1]));
            $this->assertEquals($suite[0], $ogp_info->hasMethodInThumbnail());
        }
    }

    public function test_get_params()
    {
        $suites = [
            // expect, ogp
            [['a'], ['title', 'desc', 'thumb', ['a']]],
            [[],  ['title##', 'hoge##', 'aaa##', null]],
            [[], ['title##', '##fuga', '', []]],
        ];

        foreach ($suites as $suite) {
            $ogp_info = new OgpInfo(static::_p(...$suite[1]));
            $this->assertEquals($suite[0], $ogp_info->getParams(), json_encode([$ogp_info, $ogp_info->getParams()]));
        }
    }

    public function test_replace_title_with_param()
    {
        $suites = [
            // expect, ogp
            ['title',         ['title', null, null, ['value' => 'hoge']]],
            ['',              ['', null, null, ['value' => 'hoge']]],
            ['tihogetle',     ['ti{{value}}tle', null, null, ['value' => 'hoge']]],
            ['ti{value}}tle', ['ti{value}}tle', null, null, ['value' => 'hoge']]],
            ['ti{{value}tle', ['ti{{value}tle', null, null, ['value' => 'hoge']]],
            ['ti{value}tle',  ['ti{value}tle', null, null, ['value' => 'hoge']]],
        ];

        foreach ($suites as $suite) {
            $ogp_info = new OgpInfo(static::_p(...$suite[1]));
            $this->assertEquals($suite[0], $ogp_info->replaceTitleWithParams());
        }
    }

    public function test_replace_descrpition_with_param()
    {
        $suites = [
            // expect, ogp
            ['title',         [null, 'title', null, ['value' => 'hoge']]],
            ['',              [null, '', null, ['value' => 'hoge']]],
            ['tihogetle',     [null, 'ti{{value}}tle', null, ['value' => 'hoge']]],
            ['ti{value}}tle', [null, 'ti{value}}tle', null, ['value' => 'hoge']]],
            ['ti{{value}tle', [null, 'ti{{value}tle', null, ['value' => 'hoge']]],
            ['ti{value}tle',  [null, 'ti{value}tle', null, ['value' => 'hoge']]],
        ];

        foreach ($suites as $suite) {
            $ogp_info = new OgpInfo(static::_p(...$suite[1]));
            $this->assertEquals($suite[0], $ogp_info->replaceDescriptionWithParams());
        }
    }

    public function test_replace_thumbnail_with_param()
    {
        $suites = [
            // expect, ogp
            ['title',         [null, null, 'title', ['value' => 'hoge']]],
            ['',              [null, null, '', ['value' => 'hoge']]],
            ['tihogetle',     [null, null, 'ti{{value}}tle', ['value' => 'hoge']]],
            ['ti{value}}tle', [null, null, 'ti{value}}tle', ['value' => 'hoge']]],
            ['ti{{value}tle', [null, null, 'ti{{value}tle', ['value' => 'hoge']]],
            ['ti{value}tle',  [null, null, 'ti{value}tle', ['value' => 'hoge']]],
        ];

        foreach ($suites as $suite) {
            $ogp_info = new OgpInfo(static::_p(...$suite[1]));
            $this->assertEquals($suite[0], $ogp_info->replaceThumbnailWithParams());
        }
    }

    // ======================================================================

    protected static function _p($title = '', $description = '', $thumbnail = '', $params = [])
    {
        $ret = [];

        if ($title) {
            $ret['title'] = $title;
        }

        if ($description) {
            $ret['description'] = $description;
        }

        if ($thumbnail) {
            $ret['thumbnail'] = $thumbnail;
        }

        if ($params) {
            $ret['params'] = $params;
        }

        return $ret;
    }
}
