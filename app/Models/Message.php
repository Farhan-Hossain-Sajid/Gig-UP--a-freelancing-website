<?php
namespace App\Models;

use PDO;

class Message
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /** Fetch all messages for one order */
    public function allByOrder(int $orderId): array
    {
        $stmt = $this->db->prepare("
            SELECT m.*, u.name AS sender_name
              FROM messages m
              JOIN users    u ON u.id = m.sender_id
             WHERE m.order_id = ?
             ORDER BY m.created_at ASC
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Create a new message */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO messages
              (order_id, sender_id, message, attachment)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['order_id'],
            $data['sender_id'],
            $data['message'],
            $data['attachment'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }
}
