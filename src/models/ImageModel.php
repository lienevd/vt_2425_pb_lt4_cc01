<?php

namespace Src\Models;

use Src\Collections\ImageCollection;

class ImageModel extends BaseModel
{
    public static function AddImage(string $image, string $category): void
    {
        $categoryName = CategoryModel::getCategoryById($category)->getItems()[0]['name'];

        self::db()->query('INSERT INTO images (image, category, category_id) VALUES (:image, :category, :category_id);')
            ->bindParams([
                [':image', base64_encode($image), \PDO::PARAM_LOB],
                [':category', $categoryName, \PDO::PARAM_LOB],
                [':category_id', $category, \PDO::PARAM_INT]
            ])
            ->execute();
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
