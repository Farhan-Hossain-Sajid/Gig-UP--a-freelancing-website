<?php
namespace App\Models;

use PDO;

class Alert
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /** Fetch all alerts (admin posts) */
    public function all(): array
    {
        $stmt = $this->db->query("
            SELECT * FROM alerts
            ORDER BY created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Create a new alert */
    public function create(string $message): int
    {
        $stmt = $this->db->prepare("INSERT INTO alerts (message) VALUES (?)");
        $stmt->execute([$message]);
        return (int)$this->db->lastInsertId();
    }
}
