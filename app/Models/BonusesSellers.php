<?php
namespace App\Models;

use PDO;

class BonusesSellers
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Get all bonus‐type transactions awarded to a given seller.
     */
    public function allForSeller(int $sellerId): array
    {
        $stmt = $this->db->prepare("
            SELECT 
              t.id         AS txn_id,
              COALESCE(o.order_code, '—') AS order_code,
              t.amount,
              t.created_at
            FROM transactions t
            LEFT JOIN orders o ON o.id = t.order_id
            WHERE t.seller_id = ?
              AND t.is_bonus   = 1
            ORDER BY t.created_at DESC
        ");
        $stmt->execute([$sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Award a new bonus to a seller.
     * Returns the new transaction ID.
     */
    public function create(int $sellerId, float $amount): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO transactions
              (order_id, buyer_id, seller_id, amount, platform_fee, payment_method, is_bonus)
            VALUES
              (NULL,      NULL,       ?,         ?,      0,            'bonus',     1)
        ");
        $stmt->execute([$sellerId, $amount]);
        return (int)$this->db->lastInsertId();
    }
}
