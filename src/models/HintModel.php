<?php

namespace Src\Models;

use Src\Collections\Collection;

use Src\Collections\HintCollection;

class HintModel extends BaseModel
{
    private $conn;

    // public function getHint() {
    //     $sql = "SELECT hintText FROM hints WHERE id = 1;";
    //     $result = $this->db->query($sql);
    //     return $this->db-> rowCount();

    //     if ($this->db->rowCount() > 0) {
    //         $row = $result->fetch_assoc();
    //         return $row["hintText"];
    //     } else {
    //         return "No hints found";
    //     }
    // }

    public function getHint(string $category): ?string
    {
        try {
            // Prepare and bind query
            $this->db->query('SELECT id, hintText FROM hints WHERE category = :category ORDER BY RAND() LIMIT 1;');
            $this->db->bindParams([
                [':category', $category, \PDO::PARAM_STR]
            ]);

            // Fetch single result
            $result = $this->db->fetchSingle(new collection(options: [
                "single_array" => true
            ])) ;

            return json_encode($result->getItems()) ?? null;
        
        } catch (\Exception $e) {
            // Log and handle exceptions gracefully
            error_log('Error fetching hint: ' . $e->getMessage());
            return null;
        }
    }

    public function addHint(string $hint, string $category, array $images): bool
    {
        try {
            
            $this->db->query('INSERT INTO hints (hintText, category, category_id) VALUES (:hint, :category, 0);');
            $this->db->bindParams([
                [':hint', $hint, \PDO::PARAM_STR],
                [':category', $category, \PDO::PARAM_STR]
            ]);

            $this->db->execute();
            $hint_id = $this->db->getLastInsertId();

            foreach ($images as $image) {

                $this->db->query('INSERT INTO hint_image (hint_id, image_id) VALUES (:hint_id, :image_id);');
                $this->db->bindParams([
                    [':hint_id', $hint_id, \PDO::PARAM_INT],
                    [':image_id', $image, \PDO::PARAM_INT]
                ]);

                $this->db->execute();

            }

            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

    public function getCorrectImageIds(int $hint_id): ?array
    {
        try {
            $this->db->query('SELECT image_id FROM hint_image WHERE hint_id = :hint_id;');
            $this->db->bindParams([[':hint_id', $hint_id, \PDO::PARAM_INT]]);
            $correct_image_ids = $this->db->fetchAssoc();

            echo "test";

            return array_column($correct_image_ids->getItems(), 'image_id');
        } catch (\Exception $e) {
            error_log('Error fetching correct image IDs: ' . $e->getMessage());
            return null;
        }
    }

    public function validateSelection(int $hint_id, array $selected_image_ids): bool {
    try {
        // Fetch correct image IDs for the hint
        $this->db->query('SELECT image_id FROM hint_image WHERE hint_id = :hint_id;');
            $this->db->bindParams([[':hint_id', $hint_id, \PDO::PARAM_INT]]);
        $correct_image_ids = $this->db->fetchAssoc();
        $correct_image_ids = array_column($correct_image_ids->getItems(), 'image_id');
        

        // Compare sorted arrays
        sort($correct_image_ids);
        sort($selected_image_ids);

        return $correct_image_ids === $selected_image_ids;
    } catch (\Exception $e) {
        error_log('Error validating selection: ' . $e->getMessage());
        return false;
    }
    }
}
    