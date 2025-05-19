<?php
namespace App\Models;

use PDO;

class User
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /** Find one user by ID */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** Find one user by email & role (for login) */
    public function findByEmailAndRole(string $email, string $role): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM users
             WHERE email = ? AND role = ?
        ");
        $stmt->execute([$email, $role]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** Create a new user and return its ID */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO users
              (name, email, dob, phone, job_type, role, portfolio, portfolio_attachment, password)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['dob'],
            $data['phone'],
            $data['job_type'],
            $data['role'],
            $data['portfolio'],
            $data['portfolio_attachment'] ?? null,
            $data['password_hash']
        ]);
        return (int)$this->db->lastInsertId();
    }

    /** Update a user’s referral_code */
    public function updateReferralCode(int $userId, string $code): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users SET referral_code = ? WHERE id = ?
        ");
        return $stmt->execute([$code, $userId]);
    }
}
