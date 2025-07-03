<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/database.php';
require_once '../classes/User.php';
require_once '../classes/Animal.php';

// Check if user is logged in and is admin
$user = new User($pdo);
if (!$user->isAdmin()) {
    header('Location: ../login.php');
    exit();
}

// Initialize Animal class for getting image paths
$animal = new Animal($pdo);

// Handle approve/reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['request_id'])) {
    $requestId = (int)$_POST['request_id'];
    $action = $_POST['action'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM adoption_applications WHERE id = ?");
        $stmt->execute([$requestId]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($request) {
            if ($action === 'approve') {
                // Update application status
                $stmt = $pdo->prepare("UPDATE adoption_applications SET status = 'approved' WHERE id = ?");
                $stmt->execute([$requestId]);
                
                // Update animal status
                $stmt = $pdo->prepare("UPDATE animals SET status = 'adopted' WHERE id = ?");
                $stmt->execute([$request['animal_id']]);
                
                // Reject other pending applications for this animal
                $stmt = $pdo->prepare("UPDATE adoption_applications SET status = 'rejected' 
                                     WHERE animal_id = ? AND id != ? AND status = 'pending'");
                $stmt->execute([$request['animal_id'], $requestId]);
                
                $message = "Application approved successfully!";
            } elseif ($action === 'reject') {
                // Update application status
                $stmt = $pdo->prepare("UPDATE adoption_applications SET status = 'rejected' WHERE id = ?");
                $stmt->execute([$requestId]);
                
                // If animal was pending, set it back to available
                $stmt = $pdo->prepare("UPDATE animals SET status = 'available' 
                                     WHERE id = ? AND status = 'pending'");
                $stmt->execute([$request['animal_id']]);
                
                $message = "Application rejected successfully!";
            }
        }
    } catch (PDOException $e) {
        $error = "Error processing request: " . $e->getMessage();
    }
}

// First, check which date column exists
try {
    $stmt = $pdo->query("SHOW COLUMNS FROM adoption_applications LIKE 'application_date'");
    $hasApplicationDate = $stmt->rowCount() > 0;
    
    // Fetch all adoption requests with user and animal details
    $query = "SELECT aa.*, u.username, u.email, a.name as animal_name, a.species, a.id as animal_id
              FROM adoption_applications aa 
              JOIN users u ON aa.user_id = u.id 
              JOIN animals a ON aa.animal_id = a.id 
              ORDER BY aa." . ($hasApplicationDate ? "application_date" : "created_at") . " DESC";
    
    $requests = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    
    // Get animal details including images
    foreach ($requests as &$request) {
        $animalDetails = $animal->getById($request['animal_id']);
        $request['image_path'] = $animalDetails['image_path'] ?? '../public/placeholder.jpg';
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
    $requests = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Adoption Requests - Admin Panel</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <h1>Manage Adoption Requests</h1>
            <nav>
                <a href="animals.php" class="btn btn-secondary">Back to Animals</a>
                <a href="../logout.php" class="btn btn-secondary">Logout</a>
            </nav>
        </header>

        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="requests-grid">
            <?php foreach ($requests as $request): ?>
                <div class="request-card">
                    <div class="animal-image">
                        <img src="<?php echo htmlspecialchars($request['image_path']); ?>" 
                             alt="<?php echo htmlspecialchars($request['animal_name']); ?>"
                             onerror="this.src='../public/placeholder.jpg'">
                    </div>
                    <div class="request-header">
                        <h3><?php echo htmlspecialchars($request['animal_name']); ?> (<?php echo htmlspecialchars($request['species']); ?>)</h3>
                        <span class="status-badge <?php echo strtolower($request['status']); ?>">
                            <?php echo htmlspecialchars($request['status']); ?>
                        </span>
                    </div>
                    <div class="request-details">
                        <p><strong>Applicant:</strong> <?php echo htmlspecialchars($request['username']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($request['email']); ?></p>
                        <p><strong>Date:</strong> <?php 
                            $dateField = isset($request['application_date']) ? 'application_date' : 'created_at';
                            echo date('M j, Y H:i', strtotime($request[$dateField])); 
                        ?></p>
                    </div>
                    <?php if ($request['status'] === 'pending'): ?>
                        <div class="request-actions">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                <button type="submit" name="action" value="approve" class="btn btn-primary">Approve</button>
                                <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <?php if (empty($requests)): ?>
                <p class="no-requests">No adoption requests found.</p>
            <?php endif; ?>
        </div>
    </div>

    <style>
        .requests-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .request-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .animal-image {
            width: 100%;
            height: 200px;
            margin-bottom: 15px;
            border-radius: 4px;
            overflow: hidden;
        }

        .animal-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .request-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .request-header h3 {
            margin: 0;
            color: #333;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
            font-weight: 500;
        }

        .status-badge.pending {
            background: #ffd700;
            color: #000;
        }

        .status-badge.approved {
            background: #4CAF50;
            color: white;
        }

        .status-badge.rejected {
            background: #f44336;
            color: white;
        }

        .request-details {
            margin-bottom: 15px;
        }

        .request-details p {
            margin: 5px 0;
            color: #666;
        }

        .request-actions {
            display: flex;
            gap: 10px;
        }

        .btn-danger {
            background-color: #f44336;
            color: white;
        }

        .btn-danger:hover {
            background-color: #d32f2f;
        }

        .no-requests {
            grid-column: 1 / -1;
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .alert {
            padding: 15px;
            margin: 20px;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }

        .alert-error {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }
    </style>
</body>
</html> 