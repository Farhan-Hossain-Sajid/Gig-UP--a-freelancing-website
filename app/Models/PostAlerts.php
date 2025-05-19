<?php
namespace App\Models;

use PDO;

class PostAlerts extends Model
{
    protected string $table = 'alerts';

    public function create(string $message): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table}
              (message, created_at)
            VALUES (?, NOW())
        ");
        $stmt->execute([$message]);
        return (int)$this->db->lastInsertId();
    }

    public function history(): array
    {
        return $this->db
            ->query("SELECT * FROM {$this->table} ORDER BY created_at DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}
