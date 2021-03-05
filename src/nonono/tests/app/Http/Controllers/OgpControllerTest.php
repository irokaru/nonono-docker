<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Util\TestTool;

class OgpControllerTest extends TestCase
{
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
