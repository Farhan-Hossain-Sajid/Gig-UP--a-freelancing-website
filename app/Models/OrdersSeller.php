<?php
namespace App\Models;

use PDO;

class OrdersSeller extends Model
{
    protected string $table = 'orders';

    public function withTransactions(int $sellerId): array
    {
        $stmt = $this->db->prepare("
            SELECT o.*, t.amount, t.payment_method, t.platform_fee, g.title AS gig_title
              FROM {$this->table} o
              JOIN transactions t ON t.order_id = o.id
              JOIN gigs         g ON g.id       = o.gig_id
             WHERE o.seller_id = ?
             ORDER BY o.created_at DESC
        ");
        $stmt->execute([$sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
