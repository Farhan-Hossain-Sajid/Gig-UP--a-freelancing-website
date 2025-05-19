<?php
namespace App\Models;

use PDO;

class Mybids extends Model
{
    protected string $table = 'bids';

    /**
     * Get all bids placed by a given buyer.
     */
    public function forBuyer(int $buyerId): array
    {
        $stmt = $this->db->prepare("
            SELECT b.*, g.title AS gig_title, u.name AS seller_name
              FROM {$this->table} b
              JOIN gigs   g ON g.id       = b.gig_id
              JOIN users  u ON u.id       = g.seller_id
             WHERE b.buyer_id = ?
             ORDER BY b.created_at DESC
        ");
        $stmt->execute([$buyerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
