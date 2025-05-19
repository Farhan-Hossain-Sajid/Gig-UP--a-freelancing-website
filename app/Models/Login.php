<?php
namespace App\Models;

use PDO;

class Login
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Verifies credentials and returns the user row or null.
     */
    public function authenticate(string $email, string $password, string $role): ?array
    {
        $stmt = $this->db->prepare("
          SELECT * FROM users 
           WHERE email = ? 
             AND role  = ?
        ");
        $stmt->execute([$email, $role]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }
}
