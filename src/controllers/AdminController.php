<?php

namespace Src\Controllers;

use Src\Enums\ResponseTypeEnum;
use Src\Http\Response;
use Src\Models\ImageModel;

final class AdminController
{
    public function index(): Response
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {

            $image_types = ['image/jpeg', 'image/png', 'image/webp'];

            $category = $_POST['category'];
            $image = $_FILES['image']['tmp_name'];

            // Check of er wel een image EN category is ingevuld
            if (!isset($image) || !isset($category)) {
                return new Response(ResponseTypeEnum::BAD_REQUEST, 'Vul alle velden in.');
            }

            $type = $_FILES['image']['type'];

            // Check of het bestand wel een afbeelding is
            if (!in_array($type, $image_types)) {
                return new Response(ResponseTypeEnum::BAD_REQUEST, 'Dit bestandstype is niet toegestaan.');
            }

            $imageData = file_get_contents($image);
            $base64 = base64_encode($imageData);

            $model = new ImageModel();
            $response = $model->AddImage($base64, $category);
            
            return new Response($response, 'Afbeelding toegevoegd.');

        }

        return view('admin/index');
    }
}