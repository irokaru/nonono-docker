<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        foreach ($suites as $suite) {
            $this->assertEquals($suite[0], OgpController::getTitle($suite[1]), json_encode($suite));
        }
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

        foreach ($suites as $suite) {
            $this->assertEquals($suite[0], OgpController::getDescription($suite[1]), json_encode($suite));
        }
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

        foreach ($suites as $suite) {
            $this->assertEquals($suite[0], OgpController::getThumbnail($suite[1]), json_encode($suite));
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

        $controller = new OgpController();

        foreach ($suites as $suite) {
            $result = TestTool::getProtectedMethod(OgpController::class, 'compareUrl')->invoke($controller, $suite[1], $suite[2]);
            $this->assertEquals($suite[0], $result, json_encode($suite));
        }
    }
}
