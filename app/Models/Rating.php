<?php
namespace App\Models;

use PDO;

class Rating
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /** Create a new rating */
    public function create(int $orderId, int $raterId, int $rateeId, int $rating): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO ratings
              (order_id, rater_id, ratee_id, rating, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$orderId, $raterId, $rateeId, $rating]);
        return (int)$this->db->lastInsertId();
    }

    /** Compute average + count for a seller */
    public function statsForSeller(int $sellerId): array
    {
        $stmt = $this->db->prepare("
            SELECT 
              ROUND(AVG(r.rating),2) AS avg_rating,
              COUNT(r.rating)        AS total_ratings
            FROM orders o
            JOIN ratings r 
              ON r.order_id = o.id
             AND r.ratee_id = ?
            WHERE o.seller_id = ?
              AND o.completed_at IS NOT NULL
        ");
        $stmt->execute([$sellerId, $sellerId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['avg_rating'=>0,'total_ratings'=>0];
    }

    /** Fetch all ratings received by one user */
    public function allByRatee(int $rateeId): array
    {
        $stmt = $this->db->prepare("
            SELECT r.*, o.order_code
              FROM ratings r
              JOIN orders o ON o.id = r.order_id
             WHERE r.ratee_id = ?
             ORDER BY r.created_at DESC
        ");
        $stmt->execute([$rateeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
