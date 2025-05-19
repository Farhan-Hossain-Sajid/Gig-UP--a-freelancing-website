<?php
namespace App\Models;

use PDO;

class TopRankedSellers extends Model
{
    public function list(): array
    {
        return $this->db
            ->query("
                SELECT u.id, u.name,
                       ROUND(AVG(r.rating),2) AS avg_rating,
                       COUNT(r.rating)        AS total_ratings
                  FROM users u
                  JOIN orders o   ON o.seller_id = u.id
                  JOIN ratings r  ON r.order_id  = o.id
                 WHERE u.role = 'seller' AND o.status = 'completed'
                 GROUP BY u.id
                 HAVING avg_rating >= 4.8
                 ORDER BY avg_rating DESC
            ")->fetchAll(PDO::FETCH_ASSOC);
    }
}
