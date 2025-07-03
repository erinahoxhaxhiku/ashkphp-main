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
        try {
            $stmt = $this->pdo->prepare("
                SELECT ar.*, 
                       u.username AS user_name, u.email AS user_email,
                       a.name AS animal_name, a.species AS animal_species
                FROM adoption_applications ar
                JOIN users u ON ar.user_id = u.id
                JOIN animals a ON ar.animal_id = a.id
                ORDER BY ar.created_at DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in AdoptionRequest::getAll: " . $e->getMessage());
            throw $e;
        }
    }

    // Change status helper with validation
    private function changeStatus($id, $status)
    {
        $allowed = ['pending', 'approved', 'rejected'];
        if (!in_array($status, $allowed)) {
            throw new InvalidArgumentException("Invalid status: $status");
        }

        try {
            $this->pdo->beginTransaction();

            // Get the animal_id before updating the status
            $stmt = $this->pdo->prepare("SELECT animal_id FROM adoption_applications WHERE id = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                throw new Exception("Adoption request not found");
            }

            $animal_id = $result['animal_id'];

            // Update adoption request status
            $stmt = $this->pdo->prepare("UPDATE adoption_applications SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $success = $stmt->execute([$status, $id]);

            if ($success && $status === 'approved') {
                // Update animal status to adopted
                $stmt = $this->pdo->prepare("UPDATE animals SET status = 'adopted', updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                $success = $stmt->execute([$animal_id]);
                
                if (!$success) {
                    throw new Exception("Failed to update animal status");
                }

                // Reject all other pending applications for this animal
                $stmt = $this->pdo->prepare("
                    UPDATE adoption_applications 
                    SET status = 'rejected', updated_at = CURRENT_TIMESTAMP 
                    WHERE animal_id = ? AND id != ? AND status = 'pending'
                ");
                $stmt->execute([$animal_id, $id]);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error in AdoptionRequest::changeStatus: " . $e->getMessage());
            throw $e;
        }
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

    // Create a new adoption request
    public function create($data)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO adoption_applications (
                    animal_id, user_id, applicant_name, applicant_email, 
                    applicant_phone, home_address, housing_type, has_yard,
                    other_pets, experience, reason_for_adoption
                ) VALUES (
                    :animal_id, :user_id, :applicant_name, :applicant_email,
                    :applicant_phone, :home_address, :housing_type, :has_yard,
                    :other_pets, :experience, :reason_for_adoption
                )
            ");
            
            return $stmt->execute([
                ':animal_id' => $data['animal_id'],
                ':user_id' => $data['user_id'],
                ':applicant_name' => $data['applicant_name'],
                ':applicant_email' => $data['applicant_email'],
                ':applicant_phone' => $data['applicant_phone'] ?? null,
                ':home_address' => $data['home_address'],
                ':housing_type' => $data['housing_type'],
                ':has_yard' => $data['has_yard'] ?? false,
                ':other_pets' => $data['other_pets'] ?? null,
                ':experience' => $data['experience'] ?? null,
                ':reason_for_adoption' => $data['reason_for_adoption']
            ]);
        } catch (Exception $e) {
            error_log("Error in AdoptionRequest::create: " . $e->getMessage());
            throw $e;
        }
    }

    // Get a specific adoption request
    public function get($id)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT ar.*, 
                       u.username AS user_name, u.email AS user_email,
                       a.name AS animal_name, a.species AS animal_species
                FROM adoption_applications ar
                JOIN users u ON ar.user_id = u.id
                JOIN animals a ON ar.animal_id = a.id
                WHERE ar.id = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in AdoptionRequest::get: " . $e->getMessage());
            throw $e;
        }
    }

    // Get all adoption requests for a specific user
    public function getByUser($userId)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT ar.*, 
                       a.name AS animal_name, a.species AS animal_species
                FROM adoption_applications ar
                JOIN animals a ON ar.animal_id = a.id
                WHERE ar.user_id = ?
                ORDER BY ar.created_at DESC
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in AdoptionRequest::getByUser: " . $e->getMessage());
            throw $e;
        }
    }

    // Get all adoption requests for a specific animal
    public function getByAnimal($animalId)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT ar.*, 
                       u.username AS user_name, u.email AS user_email
                FROM adoption_applications ar
                JOIN users u ON ar.user_id = u.id
                WHERE ar.animal_id = ?
                ORDER BY ar.created_at DESC
            ");
            $stmt->execute([$animalId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in AdoptionRequest::getByAnimal: " . $e->getMessage());
            throw $e;
        }
    }
}
