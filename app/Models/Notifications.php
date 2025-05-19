<?php
namespace App\Models;

use PDO;
use DateTime;

class Notifications
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Fetch all alerts (admin‐posted).
     */
    public function getAlerts(): array
    {
        return $this->db
            ->query("SELECT * FROM alerts ORDER BY created_at DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch all active coupons for a buyer.
     */
    public function getCoupons(int $buyerId): array
    {
        $stmt = $this->db->prepare("
            SELECT * 
              FROM coupons
             WHERE issued_to = ?
               AND start_date <= CURDATE()
               AND end_date   >= CURDATE()
             ORDER BY created_at DESC
        ");
        $stmt->execute([$buyerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mark “last seen” timestamp so notifications stop showing as new.
     */
    public function markSeen(int $userId): void
    {
        $now = (new DateTime())->format('Y-m-d H:i:s');

        // if a row exists, update; otherwise insert
        $exists = $this->db
            ->prepare("SELECT COUNT(*) FROM user_meta WHERE user_id = ?")
            ->execute([$userId]);

        if ($this->db->prepare("SELECT COUNT(*) FROM user_meta WHERE user_id = ?")->execute([$userId])
            && $this->db->prepare("SELECT COUNT(*) FROM user_meta WHERE user_id = ?")->fetchColumn() > 0
        ) {
            $upd = $this->db->prepare("
                UPDATE user_meta
                   SET last_noise = ?
                 WHERE user_id = ?
            ");
            $upd->execute([$now, $userId]);
        } else {
            $ins = $this->db->prepare("
                INSERT INTO user_meta (user_id, last_noise)
                VALUES (?, ?)
            ");
            $ins->execute([$userId, $now]);
        }
    }
}
