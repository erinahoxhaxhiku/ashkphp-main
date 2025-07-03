<?php
class Animal {
    private $pdo;
    private $placeholderImage = '../public/placeholder.jpg'; // Default placeholder path
    
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
        try {
            // Get all animals with their images
            $sql = "SELECT a.*, i.file_path as image_path 
                    FROM animals a 
                    LEFT JOIN images i ON a.id = i.animal_id 
                    ORDER BY a.created_at DESC";
            $stmt = $this->pdo->query($sql);
            $animals = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Fix image paths
            foreach ($animals as &$animal) {
                $animalName = strtolower($animal['name']);
                $imagePath = 'public/placeholder.jpg';
                
                // Define search directories
                $searchDirs = [
                    'admin/uploads/animals/',
                    'images/'
                ];
                
                // Try different methods to find the image in each directory
                foreach ($searchDirs as $dir) {
                    // First try exact name match
                    if (file_exists($dir . $animalName . '.jpg')) {
                        $imagePath = $dir . $animalName . '.jpg';
                        error_log("Found exact match for {$animal['name']}: {$imagePath}");
                        break;
                    }
                    
                    // Then try with timestamp prefix
                    $pattern = $dir . '*_' . $animalName . '.jpg';
                    $files = glob($pattern);
                    if (!empty($files)) {
                        rsort($files);  // Get most recent file
                        $imagePath = $files[0];
                        error_log("Found timestamped image for {$animal['name']}: {$imagePath}");
                        break;
                    }
                }
                
                // Set the final image path
                $animal['image_path'] = $imagePath;
                error_log("Final image path for {$animal['name']}: {$animal['image_path']}");
            }
            
            return $animals;
        } catch (Exception $e) {
            error_log("Error in getAll: " . $e->getMessage());
            throw $e;
        }
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
        try {
            // First, delete any associated images from the filesystem
            $sql = "SELECT file_path FROM images WHERE animal_id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $images = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            foreach ($images as $imagePath) {
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            // Then delete the animal (cascade will handle the images table)
            $sql = "DELETE FROM animals WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (Exception $e) {
            error_log("Error deleting animal: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Update animal status
    public function updateStatus($id, $status) {
        $sql = "UPDATE animals SET status = :status WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':status' => $status
        ]);
    }
    
    // Save image
    private function saveImage($animalId, $image) {
        try {
            $uploadDir = 'admin/uploads/animals/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileName = uniqid() . '_' . basename($image['name']);
            $filePath = $uploadDir . $fileName;
            
            if (move_uploaded_file($image['tmp_name'], $filePath)) {
                // Save image path to database
                $sql = "INSERT INTO images (animal_id, file_name, file_path) VALUES (:animal_id, :file_name, :file_path)";
                $stmt = $this->pdo->prepare($sql);
                return $stmt->execute([
                    ':animal_id' => $animalId,
                    ':file_name' => $fileName,
                    ':file_path' => $filePath
                ]);
            }
            
            throw new Exception("Failed to move uploaded file.");
        } catch (Exception $e) {
            error_log("Error saving image: " . $e->getMessage());
            throw $e;
        }
    }
} 