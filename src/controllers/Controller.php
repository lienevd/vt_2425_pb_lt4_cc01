<?php

namespace Src\Controllers;

use Src\Enums\ResponseTypeEnum;
use Src\Http\Response;

final class Controller
{
    public function index(): Response
    {
        return view('index');
    }

    public function getImages(string $category): Response
    {
        echo $category;
        return new Response(ResponseTypeEnum::OK);
    }
}
