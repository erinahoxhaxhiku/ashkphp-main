<?php
require_once '../config/database.php';
require_once '../classes/User.php';
require_once '../classes/Animal.php';
require_once '../classes/AdoptionRequest.php';

session_start();

// Initialize classes
$user = new User($pdo);
$animal = new Animal($pdo);
$adoptionRequest = new AdoptionRequest($pdo);

// Check if user is admin
if (!$user->isAdmin()) {
    header('Location: ../login.php');
    exit();
}

// Debug information
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get all animals with debug info
try {
    $animals = $animal->getAll();
    error_log("Number of animals fetched: " . count($animals));
    error_log("Animals data: " . print_r($animals, true));
} catch (Exception $e) {
    $animals = [];
    error_log("Error loading animals: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    $_SESSION['error'] = "Error loading animals: " . $e->getMessage();
}

// Get adoption requests
try {
    $requests = $adoptionRequest->getAll();
} catch (Exception $e) {
    error_log("Error loading adoption requests: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    $_SESSION['error'] = "Error loading adoption requests: " . $e->getMessage();
    $requests = [];
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $data = [
                    'name' => $_POST['name'],
                    'species' => $_POST['species'],
                    'description' => $_POST['description'],
                    'status' => $_POST['status']
                ];

                $image = isset($_FILES['image']) ? $_FILES['image'] : null;
                
                try {
                    $newAnimalId = $animal->create($data, $image);
                    $_SESSION['success'] = "Animal added successfully!";
                } catch (Exception $e) {
                    $_SESSION['error'] = "Error adding animal: " . $e->getMessage();
                }
                break;

            case 'update':
                if (isset($_POST['id'])) {
                    $data = [
                        'name' => $_POST['name'],
                        'species' => $_POST['species'],
                        'description' => $_POST['description'],
                        'status' => $_POST['status']
                    ];

                    $image = isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE ? $_FILES['image'] : null;
                    
                    try {
                        $animal->update($_POST['id'], $data, $image);
                        $_SESSION['success'] = "Animal updated successfully!";
                    } catch (Exception $e) {
                        $_SESSION['error'] = "Error updating animal: " . $e->getMessage();
                    }
                }
                break;

            case 'delete':
                if (isset($_POST['id'])) {
                    try {
                        $animal->delete($_POST['id']);
                        $_SESSION['success'] = "Animal deleted successfully!";
                    } catch (Exception $e) {
                        $_SESSION['error'] = "Error deleting animal: " . $e->getMessage();
                    }
                }
                break;

            // *** NEW: Handle adoption request approvals/rejections ***
            case 'approve_request':
                if (isset($_POST['request_id'])) {
                    $adoptionRequest->approve($_POST['request_id']);
                }
                break;

            case 'reject_request':
                if (isset($_POST['request_id'])) {
                    $adoptionRequest->reject($_POST['request_id']);
                }
                break;
        }

        header('Location: animals.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin - Manage Animals</title>
    <link rel="stylesheet" href="../css/style.css" />
    <style>
        /* Base styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
            font-weight: 500;
            transition: background-color 0.2s;
            width: fit-content;
        }

        .back-button:hover {
            background-color: #2563eb;
        }

        .admin-container {
            width: 95%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        .admin-header {
            margin-bottom: 2rem;
        }

        .admin-header nav {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .message {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 0.5rem;
            width: 100%;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Form styles */
        .add-animal-section {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .admin-form {
            display: grid;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            font-weight: 500;
            color: #374151;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            width: 100%;
            font-size: 1rem;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        /* Animals grid */
        .animals-section {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .filters select {
            padding: 0.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            background-color: white;
            min-width: 150px;
        }

        .animals-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }

        .animal-card {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .animal-image {
            position: relative;
            width: 100%;
            padding-top: 66.67%; /* 3:2 aspect ratio */
        }

        .animal-image img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .status-badge {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            z-index: 10;
            color: white;
        }

        .animal-info {
            padding: 1rem;
            flex-grow: 1;
        }

        .animal-actions {
            display: flex;
            gap: 0.5rem;
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        .btn-edit,
        .btn-danger {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            border: none;
            color: white;
            flex: 1;
            text-align: center;
        }

        /* Modal styles */
        .modal-content {
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 1.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .admin-container {
                padding: 0.5rem;
            }

            .admin-header nav {
                flex-direction: column;
            }

            .admin-header nav a {
                width: 100%;
                text-align: center;
            }

            .filters {
                flex-direction: column;
            }

            .filters select {
                width: 100%;
            }

            .animal-card {
                width: 100%;
            }

            .modal-content {
                width: 95%;
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .animal-actions {
                flex-direction: column;
            }

            .btn-edit,
            .btn-danger {
                width: 100%;
            }

            .form-group input,
            .form-group select,
            .form-group textarea {
                font-size: 16px; /* Prevents zoom on iOS */
            }
        }

        /* Button styles */
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 0.2s;
            text-align: center;
        }

        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2563eb;
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
        }

        /* Status badge colors */
        .status-badge.available {
            background-color: #10b981;
        }

        .status-badge.pending {
            background-color: #f59e0b;
        }

        .status-badge.adopted {
            background-color: #3b82f6;
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <header class="admin-header">
            <nav>
                <a href="adoption_requests.php" class="btn btn-primary">Adoption Requests</a>
                <a href="../index.php" class="btn btn-secondary">Back to Home</a>
                <a href="../logout.php" class="btn btn-secondary">Logout</a>
            </nav>
        </header>
        <br>
        <h1>Manage Animals</h1>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="message success">
                <?php 
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Add Animal Form -->
         <br>
        <div class="add-animal-section">
            <h2>Add New Animal</h2> <br>
            <form action="animals.php" method="POST" enctype="multipart/form-data" class="admin-form">
                <input type="hidden" name="action" value="create">
                
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="species">Species:</label>
                    <select id="species" name="species" required>
                        <option value="Dog">Dog</option>
                        <option value="Cat">Cat</option>
                        <option value="Parrot">Parrot</option>
                        <option value="Rabbit">Rabbit</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>
                </div>

                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="available">Available</option>
                        <option value="pending">Pending</option>
                        <option value="adopted">Adopted</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="image">Image:</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                </div>

                <button type="submit" class="btn btn-primary">Add Animal</button>
            </form>
        </div>

        <!-- Animals Section -->
        <div class="animals-section">
            <h2>Animals</h2>
            <div class="filters">
                <select id="species-filter" onchange="filterAnimals()">
                    <option value="">All Species</option>
                    <option value="Dog">Dogs</option>
                    <option value="Cat">Cats</option>
                    <option value="Parrot">Parrots</option>
                    <option value="Rabbit">Rabbits</option>
                </select>
                <select id="status-filter" onchange="filterAnimals()">
                    <option value="">All Statuses</option>
                    <option value="available">Available</option>
                    <option value="pending">Pending</option>
                    <option value="adopted">Adopted</option>
                </select>
            </div>

            <div class="animals-grid">
                <?php
                // Debug output
                echo "<!-- Number of animals: " . count($animals) . " -->\n";
                
                foreach ($animals as $animal): 
                    // Debug output
                    echo "<!-- Processing animal: " . htmlspecialchars($animal['name']) . " -->\n";
                ?>
                    <div class="animal-card" data-species="<?php echo htmlspecialchars($animal['species']); ?>" data-status="<?php echo htmlspecialchars($animal['status']); ?>">
                        <div class="animal-image">
                            <?php
                            $imagePath = $animal['image_path'];
                            // Debug output
                            error_log("Image path before processing: " . $imagePath);
                            
                            // Check if the file exists
                            if (file_exists($imagePath)) {
                                error_log("Image file exists at: " . $imagePath);
                            } else {
                                error_log("Image file does not exist at: " . $imagePath);
                                // Try with admin/ prefix
                                if (file_exists('admin/' . $imagePath)) {
                                    $imagePath = 'admin/' . $imagePath;
                                    error_log("Image found with admin/ prefix at: " . $imagePath);
                                } else {
                                    error_log("Image not found with admin/ prefix either");
                                    $imagePath = '../public/placeholder.jpg';
                                }
                            }
                            
                            error_log("Final image path: " . $imagePath);
                            ?>
                            <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($animal['name']); ?>" onerror="this.src='../public/placeholder.jpg';">
                            <div class="status-badge <?php echo htmlspecialchars(strtolower($animal['status'])); ?>">
                                <?php echo ucfirst(htmlspecialchars($animal['status'])); ?>
                            </div>
                        </div>
                        
                        <div class="animal-info">
                            <h3><?php echo htmlspecialchars($animal['name']); ?></h3>
                            <p class="species"><?php echo htmlspecialchars($animal['species']); ?></p>
                            <p class="description"><?php echo htmlspecialchars($animal['description']); ?></p>
                            <p class="date">Added: <?php echo date('M j, Y', strtotime($animal['created_at'])); ?></p>
                        </div>
                        
                        <div class="animal-actions">
                            <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($animal)); ?>)" class="btn-edit">
                                Edit
                            </button>
                            <form method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this animal?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $animal['id']; ?>">
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000;">
        <div class="modal-content" style="position: relative; background-color: white; margin: 10% auto; padding: 20px; width: 80%; max-width: 500px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <span class="close-modal" style="position: absolute; right: 20px; top: 10px; font-size: 24px; cursor: pointer; color: #666;">&times;</span>
            <h2 style="margin-bottom: 20px; color: #1f2937;">Edit Animal</h2>
            
            <form id="editForm" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="editAnimalId">
                
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label for="editName" style="font-weight: 500; color: #374151;">Name:</label>
                    <input type="text" id="editName" name="name" required style="padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label for="editSpecies" style="font-weight: 500; color: #374151;">Species:</label>
                    <select id="editSpecies" name="species" required style="padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
                        <option value="Dog">Dog</option>
                        <option value="Cat">Cat</option>
                        <option value="Parrot">Parrot</option>
                        <option value="Rabbit">Rabbit</option>
                    </select>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label for="editDescription" style="font-weight: 500; color: #374151;">Description:</label>
                    <textarea id="editDescription" name="description" required style="padding: 8px; border: 1px solid #d1d5db; border-radius: 4px; min-height: 100px;"></textarea>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label for="editStatus" style="font-weight: 500; color: #374151;">Status:</label>
                    <select id="editStatus" name="status" required style="padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
                        <option value="available">Available</option>
                        <option value="pending">Pending</option>
                        <option value="adopted">Adopted</option>
                    </select>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label for="editImage" style="font-weight: 500; color: #374151;">New Image (optional):</label>
                    <input type="file" id="editImage" name="image" accept="image/*" style="padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
                </div>
                
                <div style="display: flex; gap: 10px; margin-top: 10px;">
                    <button type="submit" class="btn-edit" style="flex: 1;">Save Changes</button>
                    <button type="button" onclick="closeEditModal()" class="btn-danger" style="flex: 1;">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function filterAnimals() {
            const speciesFilter = document.getElementById('species-filter').value;
            const statusFilter = document.getElementById('status-filter').value;
            const cards = document.querySelectorAll('.animal-card');

            cards.forEach(card => {
                const species = card.dataset.species;
                const status = card.dataset.status;
                const matchesSpecies = !speciesFilter || species === speciesFilter;
                const matchesStatus = !statusFilter || status === statusFilter;

                card.style.display = matchesSpecies && matchesStatus ? 'block' : 'none';
            });
        }

        function openEditModal(animal) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            
            // Fill in the form fields
            document.getElementById('editAnimalId').value = animal.id;
            document.getElementById('editName').value = animal.name;
            document.getElementById('editSpecies').value = animal.species;
            document.getElementById('editDescription').value = animal.description;
            document.getElementById('editStatus').value = animal.status.toLowerCase();
            
            // Show the modal
            modal.style.display = 'block';
            
            // Add click event to close button
            document.querySelector('.close-modal').onclick = closeEditModal;
            
            // Close modal when clicking outside
            window.onclick = function(event) {
                if (event.target === modal) {
                    closeEditModal();
                }
            };
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.style.display = 'none';
        }

        // Add form submission handling
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.textContent = 'Saving...';
            submitButton.disabled = true;
            
            fetch('animals.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                // Reload the page to show updated data
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating animal. Please try again.');
                
                // Reset button state
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            });
        });
    </script>
</body>

</html>