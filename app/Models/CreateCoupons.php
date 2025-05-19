<?php
namespace App\Models;

use PDO;

class CreateCoupons extends Model
{
    protected string $table = 'coupons';

    /**
     * Issue a new coupon.
     */
    public function create(string $title, int $discount, string $start, string $end, ?int $issuedTo = null): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table}
              (title, discount_percent, start_date, end_date, issued_to)
            VALUES
              (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$title, $discount, $start, $end, $issuedTo]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Fetch history of all coupons.
     */
    public function history(): array
    {
        return $this->db
            ->query("SELECT * FROM {$this->table} ORDER BY created_at DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}
