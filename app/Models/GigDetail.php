<?php
namespace App\Models;

use PDO;

class GigDetail
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Fetch a single gig with seller info by its ID.
     */
    public function find(int $gigId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT 
                g.*,
                u.name         AS seller_name,
                u.portfolio_attachment
            FROM gigs g
            JOIN users u ON u.id = g.seller_id
            WHERE g.id = ?
        ");
        $stmt->execute([$gigId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
