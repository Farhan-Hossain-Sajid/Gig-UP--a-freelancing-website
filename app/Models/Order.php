<?php
namespace App\Models;

use PDO;

class Order
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /** Create a new order (status = pending/confirmed externally) */
    public function create(int $gigId, int $buyerId, int $sellerId, string $orderCode, string $status = 'pending'): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO orders
              (gig_id, buyer_id, seller_id, order_code, status, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$gigId, $buyerId, $sellerId, $orderCode, $status]);
        return (int)$this->db->lastInsertId();
    }

    /** Fetch all orders placed by a buyer */
    public function allByBuyer(int $buyerId): array
    {
        $stmt = $this->db->prepare("
            SELECT o.*, g.title AS gig_title
              FROM orders o
              JOIN gigs    g ON g.id = o.gig_id
             WHERE o.buyer_id = ?
             ORDER BY o.created_at DESC
        ");
        $stmt->execute([$buyerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Fetch all orders received by a seller */
    public function allBySeller(int $sellerId): array
    {
        $stmt = $this->db->prepare("
            SELECT o.*, g.title AS gig_title
              FROM orders o
              JOIN gigs    g ON g.id = o.gig_id
             WHERE o.seller_id = ?
             ORDER BY o.created_at DESC
        ");
        $stmt->execute([$sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Mark an order as completed (set completed_at) */
    public function markComplete(int $orderId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE orders
               SET status = 'completed',
                   completed_at = NOW()
             WHERE id = ?
        ");
        return $stmt->execute([$orderId]);
    }

    /** Count how many orders a buyer has (for referral first-order logic) */
    public function countByBuyer(int $buyerId): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM orders WHERE buyer_id = ?
        ");
        $stmt->execute([$buyerId]);
        return (int)$stmt->fetchColumn();
    }
}
