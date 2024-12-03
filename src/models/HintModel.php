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
            $this->db->query('SELECT hintText FROM hints WHERE category = :category ORDER BY RAND() LIMIT 1;');
            $this->db->bindParams([
                [':category', $category, \PDO::PARAM_STR]
            ]);

            // Fetch single result
            $result = $this->db->fetchSingle(new collection(options: [
                "single_array" => true
            ])) ;

            return $result->getItems()['hintText'] ?? null;
        
        } catch (\Exception $e) {
            // Log and handle exceptions gracefully
            error_log('Error fetching hint: ' . $e->getMessage());
            return null;
        }
    }

    public function addHint(string $hint, string $category): bool
    {
        try {
            
            $this->db->query('INSERT INTO hints (hintText, category) VALUES (:hint, :category);');
            $this->db->bindParams([
                [':hint', $hint, \PDO::PARAM_STR],
                [':category', $category, \PDO::PARAM_STR]
            ]);

            return $this->db->execute();

        } catch (\Exception $e) {
            return false;
        }
    }
    
}
    