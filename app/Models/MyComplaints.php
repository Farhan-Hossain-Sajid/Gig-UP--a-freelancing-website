<?php
namespace App\Models;

use PDO;

class MyComplaints extends Model
{
    protected string $table = 'complaints';

    /**
     * Alias for LodgeComplaint::forUser()
     */
    public function forUser(int $userId): array
    {
        return (new LodgeComplaint($this->db))->forUser($userId);
    }
}
