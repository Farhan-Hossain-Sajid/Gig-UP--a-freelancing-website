<?php
namespace App\Models;

use PDO;

class Complaint
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /** Fetch all complaints (admin view) */
    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM complaints ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Fetch all complaints lodged by one user */
    public function allByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM complaints
             WHERE user_id = ?
             ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Find a single complaint by its ID */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM complaints WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** Create a new complaint */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO complaints
              (order_id, user_id, role, message, attachment)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['order_id'],
            $data['user_id'],
            $data['role'],
            $data['message'],
            $data['attachment'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }
}
