<?php
namespace App\Models;

use PDO;

class BuyerRequest
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Fetch buyer requests by type (online/in_person),
     * optionally filtering by location.
     */
    public function all(string $type = 'online', string $loc = ''): array
    {
        $sql    = "
            SELECT br.*, u.name AS buyer_name
              FROM buyer_requests br
              JOIN users u ON u.id = br.buyer_id
             WHERE br.job_type = ?
        ";
        $params = [$type];

        if ($type === 'in_person' && $loc !== '') {
            $sql .= " AND br.location LIKE ?";
            $params[] = "%{$loc}%";
        }

        $sql .= " ORDER BY br.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Create a new buyer request */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO buyer_requests
              (buyer_id, title, description, category, price, job_type, location, maps_link)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['buyer_id'],
            $data['title'],
            $data['description'],
            $data['category'],
            $data['price'],
            $data['job_type'],
            $data['location'] ?? null,
            $data['maps_link'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    /** Fetch a single buyer request */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM buyer_requests WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
