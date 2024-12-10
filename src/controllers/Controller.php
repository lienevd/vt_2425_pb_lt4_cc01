<?php

namespace Src\Controllers;

use Src\Enums\ResponseTypeEnum;
use Src\Http\Response;
use Src\Models\HintModel;
use Src\Models\ImageModel;

final class Controller
{
    public function index(): Response
    {
        return view('index');
    }

    public function getImages(int $hint_id, string $category, int $amount): Response
    {
        $imageModel = new ImageModel;

        return new Response(ResponseTypeEnum::OK, $imageModel->getImages($hint_id, $category, $amount));
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
        $images = $_POST['imageIds'];

        if (count($images) < 2) {
            return new Response(ResponseTypeEnum::BAD_REQUEST, 'Voeg minstens één afbeelding toe.');
        }

        if (!isset($hint) || !isset($category)) {
            return new Response(ResponseTypeEnum::BAD_REQUEST, 'Vul alle velden in.');
        }

        $hintModel = new HintModel();

        $response = $hintModel->addHint($hint, $category, $images) ? ResponseTypeEnum::OK : ResponseTypeEnum::BAD_REQUEST;

        $content = '';

        if ($response === ResponseTypeEnum::BAD_REQUEST) {
            $content = 'Er is iets misgegaan bij het toevoegen van de hint.';
        }

        return new Response($response, $content);
    }

    public function restartHint(): Response
    {
        $hintModel = new HintModel();
        $newHint = ucfirst($hintModel->getHint($_POST['category']));
        $_SESSION['game_data']['current_hint'] = $newHint;
        return new Response(ResponseTypeEnum::OK, json_encode(['hint' => $newHint]));
    }
}
