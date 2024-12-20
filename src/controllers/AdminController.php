<?php

namespace Src\Controllers;

use Src\Enums\ResponseTypeEnum;
use Src\Http\Response;
use Src\Models\CategoryModel;
use Src\Models\ImageModel;
use ZipArchive;

final class AdminController
{
    private ImageModel $imageModel;

    public function __construct()
    {
        $this->imageModel = new ImageModel();
    }
    public function index(): Response
    {
        $data = [
            'categories' => CategoryModel::getCategories()
        ];

        return view('admin/add-image-set', $data);
    }

    public function addImgSet(): Response
    {
        $category = $_POST['category'];
        if ($category === '') {
            return new Response(ResponseTypeEnum::BAD_REQUEST, 'Category cannot be empty');
        }

        if (!isset($_FILES['img_set']) || $_FILES['img_set']['error'] !== UPLOAD_ERR_OK) {
            return new Response(ResponseTypeEnum::BAD_REQUEST, 'There was an error trying to upload the file');
        }

        $imgSetFile = $_FILES['img_set']['tmp_name'];
        $zip = new ZipArchive();

        if (!$zip->open($imgSetFile)) {
            return new Response(ResponseTypeEnum::BAD_REQUEST, 'There was an error trying to process the ZIP file');
        }

        $imageArray = [];
        $supportedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $fileName = $zip->getNameIndex($i);
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($fileExtension, $supportedExtensions)) {
                continue;
            }

            $fileContent = $zip->getFromIndex($i);
            if ($fileContent === false) {
                $zip->close();
                return new Response(ResponseTypeEnum::BAD_REQUEST, 'There was an error extracting one of the image files');
            }

            $imageArray[] = [
                'image' => $fileContent,
                'category' => $category
            ];
        }

        $zip->close();

        if (empty($imageArray)) {
            return new Response(ResponseTypeEnum::BAD_REQUEST, 'No valid image files were found in the ZIP archive');
        }

        foreach ($imageArray as $image) {
            ImageModel::AddImage($image['image'], $image['category']);
        }

        return new Response(ResponseTypeEnum::OK, 'Successfully uploaded and processed the image set');
    }
}
