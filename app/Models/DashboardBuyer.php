<?php
namespace App\Models;

use PDO;

class DashboardBuyer extends Model
{
    protected string $table = 'transactions';

    /**
     * Get all transactions for a given buyer.
     */
    public function transactionsFor(int $buyerId): array
    {
        $stmt = $this->db->prepare("
            SELECT t.*, o.order_code, g.title AS gig_title
              FROM {$this->table} t
              JOIN orders o       ON o.id    = t.order_id
              JOIN gigs   g       ON g.id    = o.gig_id
             WHERE t.buyer_id = ?
             ORDER BY t.created_at DESC
        ");
        $stmt->execute([$buyerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
