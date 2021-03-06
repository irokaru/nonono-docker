<?php

namespace App\Http\Controllers;

use App\Lib\OgpInfo;

use Tests\TestCase;
use Tests\Util\TestTool;

class OgpControllerTest extends TestCase
{
    public function test_get_title()
    {
        $suites = [
            // expect, url
            ['ののの茶屋', '/'],
            ['ののの茶屋', '/?query'],
            ['ののの茶屋', '/dummy'],

            ['ぷろだくと - ののの茶屋', '/products'],
            ['ぷろだくと - ののの茶屋', '/products?test'],

            ['にっき - ののの茶屋', '/blog'],
            ['にっき - ののの茶屋', '/blog/1'],
            ['にっき - ののの茶屋', '/blog/123'],

            // [' - ののの茶屋', '/blog/post/1'],

            ['ののの茶屋', '/blog/aaa'],

            ['あああのにっき一覧 - ののの茶屋', '/blog/category/あああ'],
            ['testのにっき一覧 - ののの茶屋',   '/blog/category/test/2222'],

            ['ののの茶屋', '/blog/category/test/hogehoge/2222'],
            ['ののの茶屋', '/blog/category/test/a'],
        ];

        $this->simpleSuitesCheck($suites, OgpController::class . '::getTitle');
    }

    public function test_get_description()
    {
        $suites = [
            // expect, url
            ['ゲームづくりの甘味処', '/'],
            ['ゲームづくりの甘味処', '/?query'],
            ['ゲームづくりの甘味処', '/dummy'],

            ['ゲームづくりの甘味処', '/products'],
            ['ゲームづくりの甘味処', '/products?test'],

            ['ゲームづくりの甘味処', '/blog'],
            ['ゲームづくりの甘味処', '/blog/1'],
            ['ゲームづくりの甘味処', '/blog/123'],

            // [' - ののの茶屋', '/blog/post/1'],

            ['ゲームづくりの甘味処', '/blog/aaa'],

            ['ゲームづくりの甘味処', '/blog/category/あああ'],
            ['ゲームづくりの甘味処', '/blog/category/test/2222'],

            ['ゲームづくりの甘味処', '/blog/category/test/hogehoge/2222'],
            ['ゲームづくりの甘味処', '/blog/category/test/a'],
        ];

        $this->simpleSuitesCheck($suites, OgpController::class . '::getDescription');
    }

    public function test_get_thumbnail()
    {
        $suites = [
            // expect, url
            ['/img/thumbnail.png', '/'],
            ['/img/thumbnail.png', '/?query'],
            ['/img/thumbnail.png', '/dummy'],

            ['/img/thumbnail.png', '/products'],
            ['/img/thumbnail.png', '/products?test'],

            ['/img/thumbnail.png', '/blog'],
            ['/img/thumbnail.png', '/blog/1'],
            ['/img/thumbnail.png', '/blog/123'],

            // [' - ののの茶屋', '/blog/post/1'],

            ['/img/thumbnail.png', '/blog/aaa'],

            ['/img/thumbnail.png', '/blog/category/あああ'],
            ['/img/thumbnail.png', '/blog/category/test/2222'],

            ['/img/thumbnail.png', '/blog/category/test/hogehoge/2222'],
            ['/img/thumbnail.png', '/blog/category/test/a'],
        ];

        $this->simpleSuitesCheck($suites, OgpController::class . '::getThumbnail');
    }

    public function test_get_card_type_for_twitter()
    {
        $suites = [
            // expect, url
            ['summary', '/'],
            ['summary', '/?query'],
            ['summary', '/dummy'],

            ['summary', '/products'],
            ['summary', '/products?test'],

            ['summary', '/blog'],
            ['summary', '/blog/1'],
            ['summary', '/blog/123'],

            // ['summary_large_image', '/blog/post/1'],

            ['summary', '/blog/aaa'],

            ['summary', '/blog/category/あああ'],
            ['summary', '/blog/category/test/2222'],

            ['summary', '/blog/category/test/hogehoge/2222'],
            ['summary', '/blog/category/test/a'],
        ];

        $this->simpleSuitesCheck($suites, OgpController::class . '::getCardTypeForTwitter');
    }

    public function test_format_url()
    {
        $suites = [
            // expect, url
            ['',      ''],
            ['/test', '/test'],
            ['/test', '/test?hoge'],
            ['/test', '/test' . '/'],
        ];

        $ctrl = new OgpController();

        foreach ($suites as $suite) {
            $result = TestTool::getProtectedMethod($ctrl, 'formatUrl')->invoke($ctrl, $suite[1]);
            $this->assertEquals($suite[0], $result, json_encode($suite));
        }
    }

    public function test_make_ogp_info_as_url()
    {
        $suites = [
            // expect, url
            [[], '/'],
            [[], '/aaaaa'],
            [['ぷろだくと'], '/products'],

            [['にっき'], '/blog'],
            [['にっき'], '/blog/123'],
            [[], '/blog/1２3'],

            [['getPostTitle##{{title}}', 'getPostDescription##{{description}}', 'getPostThumbnail##{{thumbnail}}', ['id' => '123']], '/blog/post/123'],
            [['getPostTitle##{{title}}', 'getPostDescription##{{description}}', 'getPostThumbnail##{{thumbnail}}', ['id' => '1２3']], '/blog/post/1２3'],

            [['{{category}}のにっき一覧', null, null, ['category' => '{{test}}']], '/blog/category/{{test}}'],
            [['{{category}}のにっき一覧', null, null, ['category' => 'hogehoge']], '/blog/category/hogehoge'],
            [['{{category}}のにっき一覧', null, null, ['category' => 'hogehoge']], '/blog/category/hogehoge/123'],

            [[], '/blog/category/hogehoge/1２3'],
        ];

        $ctrl = new OgpController();

        foreach ($suites as $suite) {
            $result = TestTool::getProtectedMethod($ctrl, 'makeOgpInfoAsUrl')->invoke($ctrl, $suite[1]);
            $this->assertEquals(static::makeOgpInfo(...$suite[0]), $result, json_encode($suite));
        }
    }

    public function test_compare_url()
    {
        $suites = [
            // expect, target, url
            [['result' => true,  'params' => []], '/',     '/'],
            [['result' => true,  'params' => []], '/hoge', '/hoge'],

            [['result' => true,  'params' => ['aaa' => 'usa']], '/hoge/usa',     '/hoge/{{aaa}}'],
            [['result' => true,  'params' => ['bbb' => 'usi']], '/hoge/usi/123', '/hoge/{{bbb}}/123'],
            [['result' => true,  'params' => ['bbb' => 'usi']], '/hoge/usi/123', '/hoge/{{bbb}}/\d+'],

            [['result' => false, 'params' => []], '/',     '/hoge'],
            [['result' => false, 'params' => []], '/hoge', '/hoge/fuga'],

            [['result' => false, 'params' => []],               '/hoge/usa',      '/hoge/{aaa}}'],
            [['result' => false, 'params' => []],               '/hoge/usi/123',  '/hoge/{{bbb}/123'],
            [['result' => false, 'params' => ['bbb' => 'usi']], '/hoge/usi/1２3', '/hoge/{{bbb}}/\d+'],
        ];

        $ctrl = new OgpController();

        foreach ($suites as $suite) {
            $result = TestTool::getProtectedMethod($ctrl, 'compareUrl')->invoke($ctrl, $suite[1], $suite[2]);
            $this->assertEquals($suite[0], $result, json_encode($suite));
        }
    }

    // ======================================================================

    protected static function makeOgpInfo($title = '', $description = '', $thumbnail = '', $params = []): \App\Lib\OgpInfo
    {
        return new OgpInfo([
            'title'       => $title ?? '',
            'description' => $description ?? '',
            'thumbnail'   => $thumbnail ?? '',
            'params'      => $params ?? [],
        ]);
    }
}
