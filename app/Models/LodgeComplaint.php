<?php
namespace App\Models;

use PDO;

class LodgeComplaint extends Model
{
    protected string $table = 'complaints';

    /**
     * Create a new complaint.
     */
    public function create(int $orderId, int $userId, string $role, string $message, ?string $attachment): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table}
              (order_id, user_id, role, message, attachment)
            VALUES
              (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$orderId, $userId, $role, $message, $attachment]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Fetch complaints by a single user.
     */
    public function forUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT c.*, o.order_code, g.title AS gig_title
              FROM {$this->table} c
              JOIN orders o ON o.id   = c.order_id
              JOIN gigs   g ON g.id   = o.gig_id
             WHERE c.user_id = ?
             ORDER BY c.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
