<?php

namespace Src;

use Src\Controllers\Controller;
use Src\Http\Routing\Router;

class Kernel
{
    public function run(): void
    {
        Router::get('/', [Controller::class, 'index']);

        $response = Router::run();

        http_response_code($response->getResponse()->value);
        echo $response->getContent();
    }
}
