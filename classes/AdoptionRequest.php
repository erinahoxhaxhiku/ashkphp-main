<?php

class AdoptionRequest
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all requests with user and animal info
    public function getAll()
    {
        $stmt = $this->pdo->prepare("
            SELECT ar.id, ar.status, ar.application_date,
                   u.username AS user_name, u.email AS user_email,
                   a.name AS animal_name
            FROM adoption_applications ar
            JOIN users u ON ar.user_id = u.id
            JOIN animals a ON ar.animal_id = a.id
            ORDER BY ar.application_date DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Change status helper with validation
    private function changeStatus($id, $status)
    {
        $allowed = ['pending', 'approved', 'rejected'];
        if (!in_array($status, $allowed)) {
            throw new InvalidArgumentException("Invalid status: $status");
        }
        $stmt = $this->pdo->prepare("UPDATE adoption_applications SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    // Approve a request (set status = approved)
    public function approve($id)
    {
        return $this->changeStatus($id, 'approved');
    }

    // Reject a request (set status = rejected)
    public function reject($id)
    {
        return $this->changeStatus($id, 'rejected');
    }

    // Set status back to pending
    public function setPending($id)
    {
        return $this->changeStatus($id, 'pending');
    }

    // Optional: create a new request
    public function create($userId, $animalId)
    {
        $stmt = $this->pdo->prepare("INSERT INTO adoption_applications (user_id, animal_id) VALUES (?, ?)");
        return $stmt->execute([$userId, $animalId]);
    }
}
