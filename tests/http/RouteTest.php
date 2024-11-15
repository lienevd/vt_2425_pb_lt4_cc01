<?php

namespace Tests\Http;
use PHPUnit\Framework\TestCase;
use Src\Enums\ResponseTypeEnum;
use Src\Http\Response;
use Src\Http\Routing\Route;

class RouteTest extends TestCase
{
    private Route $routeCallbackArray;
    private Route $routeCallbackCallable;

    protected function setUp(): void
    {
        parent::setUp();

        $class = new class {
            public function index(): Response
            {
                return new Response(ResponseTypeEnum::OK);
            }
        };

        $this->routeCallbackArray = new Route('POST', '/test', [$class::class, 'index']);
        $this->routeCallbackCallable = new Route('POST', '/test', function (): Response {
            return new Response(ResponseTypeEnum::OK);
        });
    }

    public function testGetMethod(): void
    {
        $method1 = $this->routeCallbackArray->getMethod();
        $this->assertIsString($method1);
        $this->assertEquals('POST', $method1);

        $method2 = $this->routeCallbackCallable->getMethod();
        $this->assertIsString($method2);
        $this->assertEquals('POST', $method2);
    }

    public function testGetUri(): void
    {
        $uri1 = $this->routeCallbackArray->getUri();
        $this->assertIsString($uri1);
        $this->assertEquals('/test', $uri1);

        $uri2 = $this->routeCallbackCallable->getUri();
        $this->assertIsString($uri2);
        $this->assertEquals('/test', $uri2);
    }

    public function testGetCallback(): void
    {
        $callbackArray = $this->routeCallbackArray->getCallback();
        $this->assertIsArray($callbackArray);

        $callbackArrayInstance = new $callbackArray[0];
        $response1 = call_user_func([$callbackArrayInstance, $callbackArray[1]]);
        $this->assertInstanceOf(Response::class, $response1);
        $this->assertEquals(ResponseTypeEnum::OK, $response1->getResponse());

        $callbackCallable = $this->routeCallbackCallable->getCallback();
        $this->assertIsCallable($callbackCallable);

        $response2 = call_user_func($callbackCallable);
        $this->assertInstanceOf(Response::class, $response2);
        $this->assertEquals(ResponseTypeEnum::OK, $response2->getResponse());
    }
}