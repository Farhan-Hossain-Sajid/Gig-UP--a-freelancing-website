<?php
namespace App\Models;

use PDO;

class Coupon
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /** Fetch all currently valid coupons */
    public function allActive(): array
    {
        $stmt = $this->db->query("
            SELECT * FROM coupons
             WHERE start_date <= CURDATE()
               AND end_date   >= CURDATE()
             ORDER BY created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Fetch all coupons issued to a particular user */
    public function allByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM coupons
             WHERE issued_to = ?
             ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Find one coupon by its ID */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM coupons WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** Find one coupon by its title (code) */
    public function findByTitle(string $title): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM coupons
             WHERE title = ?
        ");
        $stmt->execute([$title]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** Create a new coupon */
    public function create(string $title, int $discount, string $startDate, string $endDate, ?int $issuedTo = null): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO coupons
              (title, discount_percent, start_date, end_date, issued_to)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$title, $discount, $startDate, $endDate, $issuedTo]);
        return (int)$this->db->lastInsertId();
    }
}
