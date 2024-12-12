<?php

namespace Src\Models;

use Src\Collections\AbstractCollection;
use Src\Database\DB;

class CategoryModel extends BaseModel
{
    public static function getCategories(): AbstractCollection
    {
        return DB::connect()
            ->query("SELECT * FROM categories")
            ->fetchAssoc();
    }

    public static function getCategoryById(int $id): AbstractCollection
    {
        return DB::connect()
            ->query("SELECT * FROM categories WHERE id = :id")
            ->bindParams([
                [':id', $id, \PDO::PARAM_INT]
            ])
            ->fetchSingle();
    }
}
