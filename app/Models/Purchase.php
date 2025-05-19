<?php
namespace App\Models;

use PDO;
use Exception;

class Purchase
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Creates an order for a buyer on a gig, generates a unique code.
     * @throws Exception on invalid gig
     * @return int New order ID
     */
    public function createOrder(int $buyerId, int $gigId): int
    {
        // ensure gig exists and fetch seller
        $stmt = $this->db->prepare("SELECT seller_id FROM gigs WHERE id = ?");
        $stmt->execute([$gigId]);
        $sellerId = $stmt->fetchColumn();
        if (!$sellerId) {
            throw new Exception("Invalid gig ID");
        }

        // generate unique order_code
        do {
            $code = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
            $chk  = $this->db->prepare("SELECT id FROM orders WHERE order_code = ?");
            $chk->execute([$code]);
        } while ($chk->fetch());

        // insert order
        $ins = $this->db->prepare("
            INSERT INTO orders (gig_id, buyer_id, seller_id, order_code)
            VALUES (?, ?, ?, ?)
        ");
        $ins->execute([$gigId, $buyerId, $sellerId, $code]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Records the payment transaction.
     * @return int New transaction ID
     */
    public function recordTransaction(
        int $orderId,
        int $buyerId,
        int $sellerId,
        float $amount,
        float $platformFee,
        string $method,
        ?int $couponId = null
    ): int {
        $stmt = $this->db->prepare("
            INSERT INTO transactions
              (order_id, buyer_id, seller_id, amount, platform_fee, payment_method, coupon_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $orderId,
            $buyerId,
            $sellerId,
            $amount,
            $platformFee,
            $method,
            $couponId
        ]);
        return (int)$this->db->lastInsertId();
    }
}
