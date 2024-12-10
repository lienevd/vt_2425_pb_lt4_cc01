<?php

namespace Src\Models;

use Src\Collections\Collection;
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

    public function getImages(int $hint_id, string $category, int $amount): ?string
    {
        $this->db->query('SELECT * FROM hint_image AS hi JOIN images AS i ON hi.image_id = i.id WHERE hint_id = :hint_id AND i.category = :category LIMIT 4;');
        $this->db->bindParams([
            [':hint_id', $hint_id, \PDO::PARAM_INT],
            [':category', $category, \PDO::PARAM_STR]
        ]);
        
        $results = [];
        $image_ids = [];

        foreach ($this->db->fetchAssoc()->getItems() as $image) {
            array_push($results, ['image' => $image['image'], 'id' => $image['id']]);
            array_push($image_ids, $image['id']);
        }

        $amountLeft = $amount - $this->db->rowCount();

        $queryNot = "";

        foreach ($image_ids as $image_id) {
            $queryNot .= " AND NOT id = $image_id";
        }

        $this->db->query('SELECT * FROM images WHERE category = :category'.$queryNot.' ORDER BY RAND() LIMIT :amount;');
        $this->db->bindParams([
            [':category', $category, \PDO::PARAM_STR],
            [':amount', $amountLeft, \PDO::PARAM_INT]
        ]);

        foreach ($this->db->fetchAssoc()->getItems() as $image) {
            array_push($results, ['image' => $image['image'], 'id' => $image['id']]);
        }

        shuffle($results);

        return json_encode($results);
    }
}
