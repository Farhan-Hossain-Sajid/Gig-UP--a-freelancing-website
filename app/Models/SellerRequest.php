<?php
namespace App\Models;

use PDO;

class SellerRequest
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /** Fetch all seller responses for a given buyer request */
    public function allByBuyerRequest(int $requestId): array
    {
        $stmt = $this->db->prepare("
            SELECT sr.*, u.name AS seller_name
              FROM seller_requests sr
              JOIN users u ON u.id = sr.seller_id
             WHERE sr.buyer_request_id = ?
             ORDER BY sr.created_at DESC
        ");
        $stmt->execute([$requestId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Create a new seller response to a buyer request */
    public function create(int $requestId, int $sellerId): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO seller_requests
              (buyer_request_id, seller_id)
            VALUES (?, ?)
        ");
        $stmt->execute([$requestId, $sellerId]);
        return (int)$this->db->lastInsertId();
    }
}
