<?php
namespace App\Models;

use PDO;

class BonusTransaction extends Model
{
    protected string $table = 'transactions';

    /**
     * Fetch all bonus‐only transactions (admin view).
     */
    public function allBonuses(): array
    {
        return $this->db
            ->query("
                SELECT t.id AS txn_id,
                       t.seller_id,
                       u.name AS seller_name,
                       t.amount,
                       t.created_at
                  FROM {$this->table} t
                  JOIN users u ON u.id = t.seller_id
                 WHERE t.is_bonus = 1
                 ORDER BY t.created_at DESC
            ")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch bonuses received by a specific seller.
     */
    public function forSeller(int $sellerId): array
    {
        $stmt = $this->db->prepare("
            SELECT id AS txn_id, amount, created_at
              FROM {$this->table}
             WHERE seller_id = ?
               AND is_bonus = 1
             ORDER BY created_at DESC
        ");
        $stmt->execute([$sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
