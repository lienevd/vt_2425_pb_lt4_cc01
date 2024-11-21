<?php

namespace Src\Models;

use Src\Database\DB;

abstract class BaseModel
{
    protected DB $db;

    public function __construct()
    {
        $this->db = new DB();
    }

}