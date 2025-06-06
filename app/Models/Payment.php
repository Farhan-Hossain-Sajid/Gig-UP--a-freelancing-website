<?php
namespace App\Models;

use PDO;

class Payment extends Model
{
    protected string $table = 'transactions';

    /**
     * Create an order + transaction in one go.
     */
    public function process(array $data): int
    {
        // 1) Insert order
        $oStmt = $this->db->prepare("
            INSERT INTO orders
              (gig_id, buyer_id, seller_id, order_code, status, created_at)
            VALUES (?, ?, ?, ?, 'confirmed', NOW())
        ");
        $oStmt->execute([
            $data['gig_id'],
            $data['buyer_id'],
            $data['seller_id'],
            $data['order_code']
        ]);
        $orderId = (int)$this->db->lastInsertId();

        // 2) Insert transaction
        $tStmt = $this->db->prepare("
            INSERT INTO {$this->table}
              (order_id, buyer_id, seller_id, amount, platform_fee, payment_method, coupon_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $tStmt->execute([
            $orderId,
            $data['buyer_id'],
            $data['seller_id'],
            $data['amount'],
            $data['platform_fee'],
            $data['payment_method'],
            $data['coupon_id'] ?? null
        ]);

        return $orderId;
    }
}
