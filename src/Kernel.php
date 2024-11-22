<?php

namespace Src;

use Src\Controllers\AdminController;
use Src\Controllers\Controller;
use Src\Enums\ResponseTypeEnum;
use Src\Http\Response;
use Src\Http\Routing\Router;

class Kernel
{
    public function run(): void
    {
        Router::get('/', [Controller::class, 'index']);

        Router::get('/admin', [AdminController::class, 'index']);
        Router::post('/admin', [AdminController::class, 'index']);

        Router::get('/get-images/{category}', [Controller::class, 'getImages']);

        $response = Router::run();

        http_response_code($response->getResponse()->value);
        echo $response->getContent();
    }
}
