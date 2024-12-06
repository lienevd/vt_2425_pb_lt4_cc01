<?php

namespace Src\Controllers;

use Src\Enums\ResponseTypeEnum;
use Src\Http\Response;
use Src\Models\HintModel;
use Src\Models\ImageModel;
use Tests\Http\ResponseTests;

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
            array_push($images, ['image' => $image['image'], 'id' => $image['id']]);
        }

        return new Response(ResponseTypeEnum::OK, json_encode($images));
    }

    public function getHint(string $category): Response
    {
        $hintModel = new HintModel();
        $hint = ucfirst($hintModel->getHint($category));

        return new Response(ResponseTypeEnum::OK, $hint);
    }

    public function addHint(): Response
    {

        $hint = $_POST['hint'];
        $category = $_POST['category'];

        if (!isset($hint) || !isset($category)) {
            return new Response(ResponseTypeEnum::BAD_REQUEST, 'Vul alle velden in.');
        }

        $hintModel = new HintModel();

        $response = $hintModel->addHint($hint, $category) ? ResponseTypeEnum::OK : ResponseTypeEnum::BAD_REQUEST;

        return new Response($response);
    }
}
