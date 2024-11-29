<?php

namespace Src\Models;

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

    public function getHint(int $id = 1): ?string
    {
        try {
        // Prepare and bind query
        $this->db->query('SELECT hintText FROM hints WHERE id = :id');
        $this->db->bindParams([
            [':id', $id, \PDO::PARAM_INT]
        ]);

        // Fetch single result
        $result = $this->db->fetchSingle();

        // Check if result exists
        if ($result !== null) {
            return $result['hintText'] ?? null;
        }

        return null; // No hints found
    } catch (\Exception $e) {
        // Log and handle exceptions gracefully
        error_log('Error fetching hint: ' . $e->getMessage());
        return null;
    }
    }
    
}
    