<?php

namespace Src\Http\Routing;

use Src\Http\Response;

class Middleware
{
    public function __construct(
        private string $method,
        private array $params = []
    ) {}

    public function getMethod(): string
    {
        return $this->method;
    }

    public function execute(): Response
    {

        return call_user_func_array([$this, $this->method], $this->params);
    }
}
