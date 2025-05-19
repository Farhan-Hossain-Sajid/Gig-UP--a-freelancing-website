<?php
namespace App\Models;

use PDO;

class Sellerbids extends Model
{
    protected string $table = 'bids';

    public function forSeller(int $sellerId): array
    {
        $stmt = $this->db->prepare("
            SELECT b.*, g.title AS gig_title, u.name AS buyer_name
              FROM {$this->table} b
              JOIN gigs g   ON g.id    = b.gig_id
              JOIN users u  ON u.id    = b.buyer_id
             WHERE g.seller_id = ?
             ORDER BY b.created_at DESC
        ");
        $stmt->execute([$sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
