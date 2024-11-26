<?php

namespace Src\Models;

use Src\Collections\ImageCollection;

class ImageModel extends BaseModel
{
    public function AddImage(string $image, string $category): bool
    {
        $this->db->query('INSERT INTO images (image, category) VALUES (:image, :category);');

        $this->db->bindParams([
            ['image', $image, \PDO::PARAM_LOB],
            ['category', $category, \PDO::PARAM_STR]
        ]);
        return $this->db->execute();
    }

    public function getImages(string $category): ?ImageCollection
    {
        return $this->db->query('SELECT * FROM images WHERE category = :category')
            ->bindParams([
                [':category', $category, \PDO::PARAM_STR]
            ])
            ->fetchAssoc((new ImageCollection())->deactivateModifier('image'));
    }
}
