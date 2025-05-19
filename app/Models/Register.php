<?php
namespace App\Models;

use PDO;
use Exception;

class Register
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Inserts a new user, generates & saves their referral_code.
     * @param array $data [name,email,dob,phone,job_type,role,portfolio,portfolio_attachment,password]
     * @return int New user ID
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
          INSERT INTO users 
            (name,email,dob,phone,job_type,role,portfolio,portfolio_attachment,password)
          VALUES (?,?,?,?,?,?,?,?,?)
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
            password_hash($data['password'], PASSWORD_BCRYPT)
        ]);

        $userId = (int)$this->db->lastInsertId();

        // generate and save referral code
        $code = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        $this->db
            ->prepare("UPDATE users SET referral_code = ? WHERE id = ?")
            ->execute([$code, $userId]);

        return $userId;
    }
}
