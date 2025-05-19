<?php
namespace App\Models;

use PDO;

class AdminComplaints
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Fetch all complaints with order + complainer context.
     */
    public function all(): array
    {
        $sql = "
          SELECT
            c.id,
            c.order_id,
            c.user_id,
            CASE WHEN c.user_id = o.buyer_id THEN 'buyer' ELSE 'seller' END AS user_role,
            u.name     AS complainer_name,
            u.email    AS complainer_email,
            c.message,
            c.attachment,
            c.created_at,
            o.order_code,
            buyer.name  AS buyer_name,
            seller.name AS seller_name
          FROM complaints c
          JOIN orders  o      ON o.id       = c.order_id
          JOIN users   u      ON u.id       = c.user_id
          JOIN users   buyer  ON buyer.id   = o.buyer_id
          JOIN users   seller ON seller.id  = o.seller_id
          ORDER BY c.created_at DESC
        ";
        return $this->db->query($sql, PDO::FETCH_ASSOC);
    }
}
