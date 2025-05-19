<?php
namespace App\Models;

use PDO;

class Gig
{
    protected PDO $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    /** fetch all gigs, with optional filters */
    public function all(array $filters = []): array
    {
        $sql    = "SELECT g.*, u.name AS seller_name
                     FROM gigs g
                     JOIN users u ON u.id = g.seller_id
                    WHERE 1 ";
        $params = [];

        if (!empty($filters['category'])) {
            $sql .= " AND g.category = ?";
            $params[] = $filters['category'];
        }
        if (!empty($filters['max_price'])) {
            $sql .= " AND g.price <= ?";
            $params[] = $filters['max_price'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** find one by ID */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT g.*, u.name AS seller_name
              FROM gigs g
              JOIN users u ON u.id = g.seller_id
             WHERE g.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** insert new */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
          INSERT INTO gigs
            (seller_id, title, description, category, price, delivery_days)
          VALUES
            (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['seller_id'],
            $data['title'],
            $data['description'],
            $data['category'],
            $data['price'],
            $data['delivery_days'],
        ]);
        return (int)$this->db->lastInsertId();
    }
}
