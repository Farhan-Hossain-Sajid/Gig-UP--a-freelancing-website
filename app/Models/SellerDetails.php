<?php
namespace App\Models;

use PDO;

class SellerDetails extends Model
{
    public function profile(int $sellerId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT id, name, email, created_at
              FROM users
             WHERE id = ? AND role = 'seller'
        ");
        $stmt->execute([$sellerId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function completedOrdersWithRatings(int $sellerId): array
    {
        $stmt = $this->db->prepare("
            SELECT o.order_code, g.title, r.rating, r.created_at as rated_at
              FROM orders o
              JOIN gigs    g ON g.id   = o.gig_id
              JOIN ratings r ON r.order_id = o.id AND r.ratee_id = ?
             WHERE o.seller_id = ? AND o.status = 'completed'
             ORDER BY o.completed_at DESC
        ");
        $stmt->execute([$sellerId, $sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
