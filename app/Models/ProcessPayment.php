<?php
namespace App\Models;

use PDO;

class ProcessPayment extends Model
{
    protected string $table = 'transactions';

    /**
     * Create an order + transaction record.
     * Returns the new order ID.
     */
    public function execute(array $data): int
    {
        // 1) Insert order
        $o = $this->db->prepare("
            INSERT INTO orders
              (gig_id, buyer_id, seller_id, order_code, status, created_at)
            VALUES (?,?,?,?, 'confirmed', NOW())
        ");
        $o->execute([
            $data['gig_id'],
            $data['buyer_id'],
            $data['seller_id'],
            $data['order_code']
        ]);
        $orderId = (int)$this->db->lastInsertId();

        // 2) Insert transaction
        $t = $this->db->prepare("
            INSERT INTO {$this->table}
              (order_id, buyer_id, seller_id, amount, platform_fee, payment_method, coupon_id, is_bonus)
            VALUES (?, ?, ?, ?, ?, ?, ?, 0)
        ");
        $t->execute([
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
