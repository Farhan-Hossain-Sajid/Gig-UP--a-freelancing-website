<?php
namespace App\Models;

use PDO;

class TopSellers extends Model
{
    public function list(): array
    {
        // identical to TopRankedSellers—kept for semantic naming
        return (new TopRankedSellers($this->db))->list();
    }
}
