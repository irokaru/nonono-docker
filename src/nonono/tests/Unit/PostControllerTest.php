<?php

namespace Tests\Unit;

use App\Http\Controllers\PostController;

use Tests\Util\TestTool;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    public function test_format_post()
    {
        $_p = function($array) {
            $req = new \Illuminate\Http\Request();
            $req->merge($array);
            return $req;
        };

        $suites = [
            // [
            //     [input],
            //     [expect],
            // ],
            [
                ['title' => 'hogehoge', 'date' => '2020/1/10',  'detail' => 'test', 'categories' => ['a', 'b']],
                ['title' => 'hogehoge', 'date' => '2020-01-10', 'detail' => 'test', 'categories' => ['a', 'b']],
            ],
            [
                ['id' => 10, 'title' => 'hogehoge', 'date' => '2020-1-1',   'detail' => 'test', 'categories' => ['a', 'b']],
                ['id' => 10, 'title' => 'hogehoge', 'date' => '2020-01-01', 'detail' => 'test', 'categories' => ['a', 'b']],
            ],
            [
                ['title' => 'hogehoge', 'hogehoge' => 'will delete'],
                ['title' => 'hogehoge'],
            ],
        ];

        $controller = new PostController();

        foreach ($suites as $suite) {
            $request = $_p($suite[0]);
            $result = TestTool::getProtectedMethod(PostController::class, 'formatPost')->invoke($controller, $request);
            $this->assertEquals($suite[1], $result);
        }
    }
}
