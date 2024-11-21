<?php

namespace Src\Models;

class ImageModel extends BaseModel
{
    public function AddImage($image, $category): bool
    {
        $this->db->query('INSERT INTO images (image, category) VALUES (:image, :category);');

        $this->db->bindParams([
            ['image', $image, \PDO::PARAM_LOB],
            ['category', $category, \PDO::PARAM_STR]
        ]);
        return $this->db->execute();
    }
}