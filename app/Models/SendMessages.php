<?php
namespace App\Models;

use PDO;

class SendMessages extends Model
{
    protected string $table = 'messages';

    public function send(int $orderId, int $senderId, string $message, ?string $attachment): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table}
              (order_id, sender_id, message, attachment, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$orderId, $senderId, $message, $attachment]);
        return (int)$this->db->lastInsertId();
    }

    public function forOrder(int $orderId): array
    {
        $stmt = $this->db->prepare("
            SELECT m.*, u.name
              FROM {$this->table} m
              JOIN users u ON u.id = m.sender_id
             WHERE m.order_id = ?
             ORDER BY m.created_at ASC
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
