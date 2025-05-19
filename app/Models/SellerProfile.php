<?php
namespace App\Models;

use PDO;

class SellerProfile extends Model
{
    public function attachment(int $sellerId): ?string
    {
        $stmt = $this->db->prepare("
            SELECT portfolio_attachment
              FROM users
             WHERE id = ? AND role = 'seller'
        ");
        $stmt->execute([$sellerId]);
        return $stmt->fetchColumn() ?: null;
    }
}
