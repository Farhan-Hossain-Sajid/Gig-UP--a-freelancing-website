<?php
namespace App\Models;

use PDO;

class RequestForOrder extends Model
{
    protected string $table = 'seller_requests';

    /**
     * @return int The new record ID.
     */
    public function create(int $buyerRequestId, int $sellerId): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table}
              (buyer_request_id, seller_id)
            VALUES
              (?, ?)
        ");
        $stmt->execute([$buyerRequestId, $sellerId]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Fetch all incoming requests for a given seller.
     */
    public function forSeller(int $sellerId): array
    {
        $stmt = $this->db->prepare("
            SELECT br.*, u.name AS buyer_name
              FROM {$this->table} br
              JOIN users u ON u.id = br.buyer_request_id
             WHERE br.seller_id = ?
             ORDER BY br.id DESC
        ");
        $stmt->execute([$sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
