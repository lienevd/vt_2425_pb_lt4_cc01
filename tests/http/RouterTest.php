<?php

namespace Tests\Http;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Src\Enums\ResponseTypeEnum;
use Src\Http\Response;
use Src\Http\Routing\Router;

class RouterTest extends TestCase
{
    private Router $router;
    private array $routes;
    private ReflectionClass $routerReflection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->router = new Router;
        $this->router::get('/test', function (): Response {
            return new Response(ResponseTypeEnum::OK);
        });
        $this->router::post('/test', function (): Response {
            return new Response(ResponseTypeEnum::OK);
        });

        $routerReflection = new ReflectionClass(Router::class);
        $this->routerReflection = $routerReflection;
        $routes = $routerReflection->getProperty('routes');
        $routes->setAccessible(true);
        $this->routes = $routes->getValue();
    }
    public function testMethodFunctions(): void
    {
        $methods = [
            'GET', 'POST', 'DELETE', 'PUT'
        ];
        foreach ($this->routes as $route) {
            $this->assertContains($route->getMethod(), $methods);
        }
    }

    public function testResolveUri(): void
    {
        $resolveUri = $this->routerReflection->getMethod('resolveUri');
        $resolveUri->setAccessible(true);
        $responseParams = $resolveUri->invoke(null, '/test', '/test');
        $this->assertIsArray($responseParams);
        $this->assertEquals(ResponseTypeEnum::OK, $responseParams['code']);
        $this->assertEmpty($responseParams['params']);

        $responseParams = $resolveUri->invoke(null, '/test1', '/test');
        $this->assertIsArray($responseParams);
        $this->assertEquals(ResponseTypeEnum::NOT_FOUND, $responseParams['code']);
        $this->assertEmpty($responseParams['params']);

        $getMethod = $this->routerReflection->getMethod('get');
        $getMethod->invoke(null, '/test/{param}', function () {
            return new Response(ResponseTypeEnum::OK);
        });
        $resolveUri = $this->routerReflection->getMethod('resolveUri');
        $responseParams = $resolveUri->invoke(null, '/test1', '/test/{param}');
        $this->assertIsArray($responseParams);
        $this->assertEquals(ResponseTypeEnum::NOT_FOUND, $responseParams['code']);
        $this->assertEmpty($responseParams['params']);

        $responseParams = $resolveUri->invoke(null, '/test/1', '/test/{param}');
        $this->assertIsArray($responseParams);
        $this->assertEquals(ResponseTypeEnum::OK, $responseParams['code']);
        $this->assertIsArray($responseParams['params']);
        $this->assertIsString($responseParams['params']['param']);
        $this->assertEquals('1', $responseParams['params']['param']);
    }

    public function testRunMethod(): void
    {
        $_SERVER['REQUEST_URI'] = '/test';
        $_SERVER['REQUEST_METHOD'] = "GET";
        $response = $this->router::run();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(ResponseTypeEnum::OK, $response->getResponse());

        $_SERVER['REQUEST_METHOD'] = "PATCH";
        $response = $this->router::run();
        $this->assertEquals(ResponseTypeEnum::NOT_FOUND, $response->getResponse());

        $_SERVER['REQUEST_URI'] = '/test1';
        $_SERVER['REQUEST_METHOD'] = "GET";
        $response = $this->router::run();
        $this->assertEquals(ResponseTypeEnum::NOT_FOUND, $response->getResponse());
    }
}