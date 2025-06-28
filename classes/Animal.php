<?php
class Animal {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Create new animal
    public function create($data, $image = null) {
        try {
            $this->pdo->beginTransaction();
            
            $sql = "INSERT INTO animals (name, species, description, status) VALUES (:name, :species, :description, :status)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':species' => $data['species'],
                ':description' => $data['description'],
                ':status' => $data['status'] ?? 'available'
            ]);
            
            $animalId = $this->pdo->lastInsertId();
            
            // Handle image upload if provided
            if ($image && $image['error'] === UPLOAD_ERR_OK) {
                $this->saveImage($animalId, $image);
            }
            
            $this->pdo->commit();
            return $animalId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
    
    // Read all animals
    public function getAll() {
        $sql = "SELECT a.*, i.file_path as image_path 
                FROM animals a 
                LEFT JOIN images i ON a.id = i.animal_id";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
    
    // Get animals by status
    public function getByStatus($status) {
        $sql = "SELECT a.*, i.file_path as image_path 
                FROM animals a 
                LEFT JOIN images i ON a.id = i.animal_id 
                WHERE a.status = :status";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':status' => $status]);
        return $stmt->fetchAll();
    }
    
    // Get available animals
    public function getAvailable() {
        return $this->getByStatus('available');
    }
    
    // Get adopted animals
    public function getAdopted() {
        return $this->getByStatus('adopted');
    }
    
    // Read single animal
    public function getById($id) {
        $sql = "SELECT a.*, i.file_path as image_path 
                FROM animals a 
                LEFT JOIN images i ON a.id = i.animal_id 
                WHERE a.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    // Update animal
    public function update($id, $data, $image = null) {
        try {
            $this->pdo->beginTransaction();
            
            $sql = "UPDATE animals 
                    SET name = :name, 
                        species = :species, 
                        description = :description, 
                        status = :status 
                    WHERE id = :id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'],
                ':species' => $data['species'],
                ':description' => $data['description'],
                ':status' => $data['status']
            ]);
            
            // Handle new image upload if provided
            if ($image && $image['error'] === UPLOAD_ERR_OK) {
                $this->saveImage($id, $image);
            }
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
    
    // Delete animal
    public function delete($id) {
        $sql = "DELETE FROM animals WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // Save image
    private function saveImage($animalId, $image) {
        $uploadDir = 'uploads/animals/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = uniqid() . '_' . basename($image['name']);
        $filePath = $uploadDir . $fileName;
        
        if (move_uploaded_file($image['tmp_name'], $filePath)) {
            $sql = "INSERT INTO images (animal_id, file_name, file_path) VALUES (:animal_id, :file_name, :file_path)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':animal_id' => $animalId,
                ':file_name' => $fileName,
                ':file_path' => $filePath
            ]);
        }
        return false;
    }
} 