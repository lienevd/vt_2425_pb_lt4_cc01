<?php

namespace Src\Models;

use Src\Enums\ResponseTypeEnum;

class ImageModel extends BaseModel
{
    public function AddImage($image, $category): ResponseTypeEnum
    {
        $this->db->query('INSERT INTO images (image, category) VALUES (:image, :category);');
        $data = array();

        $data[0][0] = 'image';
        $data[0][1] = $image;
        $data[0][2] = \PDO::PARAM_LOB;

        $data[1][0] = 'category';
        $data[1][1] = $category;
        $data[1][2] = \PDO::PARAM_STR;

        $this->db->bindParams($data);
        $this->db->execute();

        return ResponseTypeEnum::OK;
    }
}