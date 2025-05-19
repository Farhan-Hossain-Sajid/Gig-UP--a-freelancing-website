<?php
namespace App\Models;

use PDO;

class MyRatings extends Model
{
    protected string $table = 'ratings';

    /**
     * Compute average + count of ratings for a seller.
     */
    public function forSeller(int $sellerId): array
    {
        $stmt = $this->db->prepare("
            SELECT 
              ROUND(AVG(r.rating),2) AS avg_rating,
              COUNT(r.rating)       AS total_ratings
              FROM orders o
              JOIN {$this->table} r
                ON r.order_id = o.id
             WHERE o.seller_id = ?
               AND o.status     = 'completed'
        ");
        $stmt->execute([$sellerId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['avg_rating'=>0,'total_ratings'=>0];
    }
}
