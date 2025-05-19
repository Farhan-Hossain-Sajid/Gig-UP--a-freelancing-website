<?php
namespace App\Models;

use PDO;

class MyBonusesSeller extends Model
{
    protected string $table = 'transactions';

    public function forSeller(int $sellerId): array
    {
        $stmt = $this->db->prepare("
            SELECT id AS txn_id, amount, created_at
              FROM {$this->table}
             WHERE seller_id = ? AND is_bonus = 1
             ORDER BY created_at DESC
        ");
        $stmt->execute([$sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
