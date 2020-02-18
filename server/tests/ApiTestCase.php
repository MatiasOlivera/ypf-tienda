<?php

namespace Tests;

use Tests\TestCase;
use Illuminate\Routing\Middleware\ThrottleRequests;

abstract class ApiTestCase extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->withoutMiddleware(ThrottleRequests::class);
    }
}
