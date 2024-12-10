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
        Router::post('/game/restartHint', [Controller::class, 'restartHint']);

        Router::get('/admin', [AdminController::class, 'index']);
        Router::post('/add-img-set', [AdminController::class, 'addImgSet']);

        Router::get('/get-images/{hint_id}/{category}/{amount}', [Controller::class, 'getImages']);
        Router::get('/get-hint/{category}', [Controller::class, 'getHint']);
        Router::post('/add-hint', [Controller::class, 'addhint']);

        $response = Router::run();

        http_response_code($response->getResponse()->value);
        echo $response->getContent();
    }
}
