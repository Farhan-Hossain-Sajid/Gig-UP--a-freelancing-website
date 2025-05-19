<?php
namespace App\Models;

use PDO;

class AdminPost
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // —— Coupon methods —— //

    /**
     * Create a new coupon.
     */
    public function createCoupon(string $title, string $start, string $end, int $discount): void
    {
        $stmt = $this->db->prepare("
          INSERT INTO coupons
            (title, start_date, end_date, discount_percent)
          VALUES
            (?, ?, ?, ?)
        ");
        $stmt->execute([$title, $start, $end, $discount]);
    }

    /**
     * List all coupons.
     */
    public function listCoupons(): array
    {
        return $this->db
            ->query("SELECT * FROM coupons ORDER BY created_at DESC", PDO::FETCH_ASSOC);
    }

    // —— Alert methods —— //

    /**
     * Create a new alert.
     */
    public function createAlert(string $message): void
    {
        $stmt = $this->db->prepare("
          INSERT INTO alerts (message)
          VALUES (?)
        ");
        $stmt->execute([$message]);
    }

    /**
     * List all alerts.
     */
    public function listAlerts(): array
    {
        return $this->db
            ->query("SELECT * FROM alerts ORDER BY created_at DESC", PDO::FETCH_ASSOC);
    }
}
