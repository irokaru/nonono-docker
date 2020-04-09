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
}
