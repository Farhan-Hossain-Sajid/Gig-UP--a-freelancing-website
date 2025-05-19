<?php
namespace App\Models;

use PDO;

class CreateRequest extends Model
{
    protected string $table = 'buyer_requests';

    public function create(array $d): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table}
              (buyer_id, title, description, category, price, job_type, location, maps_link, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $d['buyer_id'],
            $d['title'],
            $d['description'],
            $d['category'],
            $d['price'],
            $d['job_type'],
            $d['location'] ?? null,
            $d['maps_link'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }
}
