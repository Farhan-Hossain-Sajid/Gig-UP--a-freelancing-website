<?php
namespace App\Models;

use PDO;

class Referral
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /** Fetch history of referrals made by one user */
    public function allByReferrer(int $referrerId): array
    {
        $stmt = $this->db->prepare("
            SELECT r.*, u.name AS friend_name
              FROM referrals r
              JOIN users    u ON u.id = r.referred_id
             WHERE r.referrer_id = ?
             ORDER BY r.used_at DESC
        ");
        $stmt->execute([$referrerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Record a new referral event */
    public function create(int $referrerId, int $referredId): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO referrals
              (referrer_id, referred_id)
            VALUES (?, ?)
        ");
        $stmt->execute([$referrerId, $referredId]);
        return (int)$this->db->lastInsertId();
    }
}
