<?php
namespace App\Models;

use PDO;

class DashboardSeller extends Model
{
    protected string $table = 'transactions';

    /**
     * Get all transactions for a given seller.
     */
    public function transactionsFor(int $sellerId): array
    {
        $stmt = $this->db->prepare("
            SELECT t.*, o.order_code, g.title AS gig_title
              FROM {$this->table} t
              JOIN orders o ON o.id    = t.order_id
              JOIN gigs   g ON g.id    = o.gig_id
             WHERE t.seller_id = ?
             ORDER BY t.created_at DESC
        ");
        $stmt->execute([$sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
