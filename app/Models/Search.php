<?php
namespace App\Models;

use PDO;

class Search
{
    private PDO $db;

    public function __construct(PDO $pdo) { $this->db = $pdo; }

    /**
     * Find sellers by name + optional rating filter.
     */
    public function sellers(string $name = '', float $minRating = 0): array
    {
        $sql = "
          SELECT u.id, u.name,
                 ROUND(AVG(r.rating),2) AS avg_rating
            FROM users u
            LEFT JOIN orders o   ON o.seller_id = u.id AND o.status='completed'
            LEFT JOIN ratings r  ON r.order_id = o.id
           WHERE u.role='seller'
        ";
        $params = [];
        if ($name !== '') {
            $sql .= " AND u.name LIKE ?";
            $params[] = "%{$name}%";
        }
        $sql .= " GROUP BY u.id";
        if ($minRating > 0) {
            $sql .= " HAVING avg_rating >= ?";
            $params[] = $minRating;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find gigs by title/category/price/delivery filters.
     */
    public function gigs(array $f = []): array
    {
        $sql = "SELECT g.*, u.name AS seller_name FROM gigs g JOIN users u ON u.id=g.seller_id WHERE 1";
        $p = [];
        if (!empty($f['title'])) {
            $sql .= " AND g.title LIKE ?"; $p[] = "%{$f['title']}%";
        }
        if (!empty($f['category'])) {
            $sql .= " AND g.category = ?"; $p[] = $f['category'];
        }
        if (!empty($f['min_price'])) {
            $sql .= " AND g.price >= ?"; $p[] = $f['min_price'];
        }
        if (!empty($f['max_price'])) {
            $sql .= " AND g.price <= ?"; $p[] = $f['max_price'];
        }
        if (!empty($f['max_delivery'])) {
            $sql .= " AND g.delivery_days <= ?"; $p[] = $f['max_delivery'];
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($p);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
