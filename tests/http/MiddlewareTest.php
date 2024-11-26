<?php

namespace Tests\Http;

use PHPUnit\Framework\TestCase;
use Src\Enums\ResponseTypeEnum;
use Src\Http\Response;
use Src\Http\Routing\Middleware;

class MiddlewareTest extends TestCase
{
    private Middleware $middleware;

    protected function setUp(): void
    {
        $class = new Class {
            public function index(): Response
            {
                return new Response(ResponseTypeEnum::OK);
            }
        };

        $this->middleware = new Middleware('index');
    }

    public function testMethod(): void
    {
        $this->assertIsString($this->middleware->getMethod());
    }

    public function testExecute(): void
    {
        $this->assertInstanceOf(Response::class, $this->middleware->execute());
    }
}