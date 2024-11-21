<?php

namespace Src\Models;

use Src\Enums\ResponseTypeEnum;

class ImageModel extends BaseModel
{
    public function AddImage($image, $category): bool
    {
        $this->db->query('INSERT INTO images (image, category) VALUES (:image, :category);');
        $data = array();

        $this->db->bindParams([
            ['image', $image, \PDO::PARAM_LOB],
            ['category', $category, \PDO::PARAM_STR]
        ]);
        return $this->db->execute();
    }
}