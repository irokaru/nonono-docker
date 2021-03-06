<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // API 叩く制限解除
        $this->withoutMiddleware(
            \Illuminate\Routing\Middleware\ThrottleRequests::class
        );
    }

    /**
     * suitesを使ったシンプルなチェック
     * @param array $suites
     * @param string $method
     * @return void
     */
    public function simpleSuitesCheck($suites, $method)
    {
        foreach ($suites as $suite) {
            $expect = array_shift($suite);
            $this->assertEquals($expect, call_user_func($method, ...$suite), json_encode([$expect, $suite]));
        }
    }
}
