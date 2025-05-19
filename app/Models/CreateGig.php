<?php
namespace App\Models;

use PDO;

class CreateGig extends Model
{
    protected string $table = 'gigs';

    public function create(array $d): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table}
              (seller_id, title, description, category, price, delivery_days, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $d['seller_id'],
            $d['title'],
            $d['description'],
            $d['category'],
            $d['price'],
            $d['delivery_days']
        ]);
        return (int)$this->db->lastInsertId();
    }
}
