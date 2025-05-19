<?php
namespace App\Models;

use PDO;

class CompleteOrder extends Model
{
    protected string $table = 'orders';

    /**
     * Mark a single order as completed (sets completed_at = NOW()).
     */
    public function markCompleted(int $orderId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table}
               SET status = 'completed',
                   completed_at = NOW()
             WHERE id = ?
        ");
        return $stmt->execute([$orderId]);
    }
}
