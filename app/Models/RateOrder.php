<?php
namespace App\Models;

use PDO;

class RateOrder extends Model
{
    protected string $table = 'ratings';

    public function create(int $orderId, int $raterId, int $rateeId, int $rating): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table}
              (order_id, rater_id, ratee_id, rating, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$orderId, $raterId, $rateeId, $rating]);
        return (int)$this->db->lastInsertId();
    }
}
