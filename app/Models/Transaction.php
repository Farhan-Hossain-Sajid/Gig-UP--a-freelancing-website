<?php
namespace App\Models;

use PDO;

class Transaction
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /** Record a new transaction */
    public function create(
        int $orderId,
        int $buyerId,
        int $sellerId,
        float $amount,
        float $platformFee,
        string $method,
        ?int $couponId = null,
        bool $isBonus = false
    ): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO transactions
              (order_id, buyer_id, seller_id, amount, platform_fee, payment_method, coupon_id, is_bonus)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $orderId,
            $buyerId,
            $sellerId,
            $amount,
            $platformFee,
            $method,
            $couponId,
            $isBonus ? 1 : 0
        ]);
        return (int)$this->db->lastInsertId();
    }

    /** Fetch all transactions made by a buyer */
    public function allByBuyer(int $buyerId): array
    {
        $stmt = $this->db->prepare("
            SELECT t.*, g.title
              FROM transactions t
              JOIN gigs        g ON g.id = t.order_id
             WHERE t.buyer_id = ?
             ORDER BY t.created_at DESC
        ");
        $stmt->execute([$buyerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Fetch all transactions received by a seller */
    public function allBySeller(int $sellerId): array
    {
        $stmt = $this->db->prepare("
            SELECT t.*, g.title
              FROM transactions t
              JOIN gigs        g ON g.id = t.order_id
             WHERE t.seller_id = ?
             ORDER BY t.created_at DESC
        ");
        $stmt->execute([$sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Fetch all transactions (admin view) */
    public function allForAdmin(): array
    {
        return $this->db
            ->query("
                SELECT t.*, o.order_code, g.title AS gig_title,
                       bu.name AS buyer_name, se.name AS seller_name
                  FROM transactions t
                  JOIN orders o ON o.id        = t.order_id
                  JOIN gigs   g ON g.id        = o.gig_id
                  JOIN users  bu ON bu.id      = t.buyer_id
                  JOIN users  se ON se.id      = t.seller_id
                 ORDER BY t.created_at DESC
            ")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Fetch only bonus‐type transactions */
    public function allBonuses(): array
    {
        $stmt = $this->db->query("
            SELECT t.*, se.name AS seller_name
              FROM transactions t
              JOIN users se ON se.id = t.seller_id
             WHERE t.is_bonus = 1
             ORDER BY t.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
