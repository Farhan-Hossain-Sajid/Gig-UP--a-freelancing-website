<?php
namespace App\Models;

use PDO;

class AdminDashboard
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Fetch all transactions (gig orders).
     */
    public function getTransactions(): array
    {
        $sql = "
          SELECT
            t.order_id,
            o.order_code,
            gb.title       AS order_title,
            bu.name        AS buyer_name,
            bu.email       AS buyer_email,
            se.name        AS seller_name,
            se.email       AS seller_email,
            t.amount,
            t.platform_fee,
            t.payment_method,
            t.created_at
          FROM transactions t
          JOIN orders  o  ON o.id        = t.order_id
          JOIN gigs    gb ON gb.id       = o.gig_id
          JOIN users   bu ON bu.id       = t.buyer_id
          JOIN users   se ON se.id       = t.seller_id
          ORDER BY t.created_at DESC
        ";
        return $this->db->query($sql, PDO::FETCH_ASSOC);
    }

    /**
     * Fetch all subscription purchases.
     */
    public function getSubscriptions(): array
    {
        $sql = "
          SELECT
            s.id           AS sub_id,
            u.name         AS buyer_name,
            u.email        AS buyer_email,
            s.price        AS amount,
            s.payment_method,
            s.start_date,
            s.end_date,
            s.created_at   AS purchased_at
          FROM subscriptions s
          JOIN users u ON u.id = s.user_id
          ORDER BY s.created_at DESC
        ";
        return $this->db->query($sql, PDO::FETCH_ASSOC);
    }
}
