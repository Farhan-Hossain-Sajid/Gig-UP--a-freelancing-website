<?php
namespace App\Models;

use PDO;

class Subscription
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /** Fetch all subscriptions for a given user */
    public function allByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM subscriptions
             WHERE user_id = ?
             ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Fetch the currently active subscription (if any) */
    public function findActiveByUser(int $userId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM subscriptions
             WHERE user_id = ?
               AND end_date > NOW()
             ORDER BY created_at DESC
             LIMIT 1
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** Create a new subscription */
    public function create(int $userId, float $price, string $startDate, string $endDate, string $method): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO subscriptions
              (user_id, price, start_date, end_date, payment_method)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$userId, $price, $startDate, $endDate, $method]);
        return (int)$this->db->lastInsertId();
    }

    /** Cancel (expire) a subscription immediately */
    public function cancel(int $id): bool
    {
        $stmt = $this->db->prepare("
            UPDATE subscriptions
               SET end_date = NOW()
             WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }
}
