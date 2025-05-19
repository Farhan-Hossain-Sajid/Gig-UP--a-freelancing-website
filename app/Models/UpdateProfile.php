<?php
namespace App\Models;

use PDO;

class UpdateProfile
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Fetch a user’s profile info.
     */
    public function find(int $userId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT name, email, dob, phone, role, portfolio, portfolio_attachment
              FROM users
             WHERE id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Update the portfolio_attachment field for a user.
     */
    public function updateAttachment(int $userId, string $filename): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users
               SET portfolio_attachment = ?
             WHERE id = ?
        ");
        return $stmt->execute([$filename, $userId]);
    }
}
