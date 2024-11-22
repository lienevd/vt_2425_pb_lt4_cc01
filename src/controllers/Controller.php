<?php

namespace Src\Controllers;

use Src\Enums\ResponseTypeEnum;
use Src\Http\Response;
use Src\Models\ImageModel;

final class Controller
{
    public function index(): Response
    {
        return view('index');
    }

    public function getImages(string $category): Response
    {
        $imageModel = new ImageModel;
        $images = [];
        foreach ($imageModel->getImages($category) as $image) {
            $images[] = $image['image'];
        }

        return new Response(ResponseTypeEnum::OK, json_encode($images));
    }
}
