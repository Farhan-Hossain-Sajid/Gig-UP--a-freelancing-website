<?php
namespace App\Models;

use PDO;

class Refer
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Get my own referral code.
     */
    public function getCode(int $userId): ?string
    {
        $stmt = $this->db->prepare("SELECT referral_code FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn() ?: null;
    }

    /**
     * List everyone who’s used my code.
     */
    public function getHistory(int $referrerId): array
    {
        $stmt = $this->db->prepare("
            SELECT 
              r.used_at,
              u.name AS friend_name
            FROM referrals r
            JOIN users    u ON u.id = r.referred_id
            WHERE r.referrer_id = ?
            ORDER BY r.used_at DESC
        ");
        $stmt->execute([$referrerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
