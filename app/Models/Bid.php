<?php
namespace App\Models;

use PDO;

class Bid
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /** Fetch all bids placed by one buyer */
    public function allByBuyer(int $buyerId): array
    {
        $stmt = $this->db->prepare("
            SELECT b.*, g.title AS gig_title
              FROM bids b
              JOIN gigs g ON g.id = b.gig_id
             WHERE b.buyer_id = ?
             ORDER BY b.created_at DESC
        ");
        $stmt->execute([$buyerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Fetch all bids received on one gig */
    public function allByGig(int $gigId): array
    {
        $stmt = $this->db->prepare("
            SELECT b.*, u.name AS buyer_name
              FROM bids b
              JOIN users u ON u.id = b.buyer_id
             WHERE b.gig_id = ?
             ORDER BY b.created_at DESC
        ");
        $stmt->execute([$gigId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Create a new bid */
    public function create(int $gigId, int $buyerId, float $amount): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO bids
              (gig_id, buyer_id, amount)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$gigId, $buyerId, $amount]);
        return (int)$this->db->lastInsertId();
    }
}
