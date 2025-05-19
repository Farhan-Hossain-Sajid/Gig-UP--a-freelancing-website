<?php
namespace App\Models;

use PDO;
use Exception;

class PopulateReferralCode
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Generates and saves a new 8-char uppercase referral_code for every user.
     * @return int Number of users updated.
     */
    public function run(): int
    {
        $ids = $this->db
            ->query("SELECT id FROM users", PDO::FETCH_COLUMN)
            ->fetchAll();

        $upd = $this->db->prepare("UPDATE users SET referral_code = ? WHERE id = ?");
        $count = 0;

        foreach ($ids as $id) {
            // 8 hex chars → 4 bytes → uppercase
            $code = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
            $upd->execute([$code, $id]);
            $count++;
        }

        return $count;
    }
}
