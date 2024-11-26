<?php

namespace Src\Http\Routing;

use Src\Http\Routing\Middleware;
use Src\Enums\ResponseTypeEnum;
use Src\Http\Response;

class Route
{
    private array $middleware = [];
    private array $expectedParams = [];

    public function __construct(
        private string $method,
        private string $uri,
        private $callback,
    ) {
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getCallback()
    {
        return $this->callback;
    }

    public function addMiddleware(Middleware ...$middleware): self
    {
        $this->middleware = $middleware;
        return $this;
    }

    public function executeMiddleware(): array
    {
        $response = [];

        foreach ($this->middleware as $middleware) {
            $response[] = $middleware->execute();
        }

        return $response;
    }

    public function setExpectedParams(string ...$params): void
    {
        $this->expectedParams = $params;
    }

    public function getExpectedParams(): array
    {
        return $this->executeMiddleware();
    }
}