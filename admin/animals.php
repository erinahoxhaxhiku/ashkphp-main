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
$requests = $adoptionRequest->getAll();

// Check if user is admin
if (!$user->isAdmin()) {
    header('Location: ../login.php');
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
            case 'update':
                $data = [
                    'name' => $_POST['name'],
                    'species' => $_POST['species'],
                    'description' => $_POST['description'],
                    'status' => $_POST['status']
                ];

                $image = isset($_FILES['image']) ? $_FILES['image'] : null;

                if ($_POST['action'] === 'create') {
                    $animal->create($data, $image);
                } else {
                    $animal->update($_POST['id'], $data, $image);
                }
                break;

            case 'delete':
                if (isset($_POST['id'])) {
                    $animal->delete($_POST['id']);
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

// Get all animals
$animals = $animal->getAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin - Manage Animals</title>
    <link rel="stylesheet" href="../css/style.css" />
    <style>
        /* Your existing styles here */

        /* Added styles for adoption requests */
        .adoption-requests {
            margin-top: 3rem;
            background: var(--white);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .adoption-requests h2 {
            color: var(--primary);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .request-card {
            border: 1px solid var(--border);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--background);
        }

        .request-info {
            flex: 1;
        }

        .request-actions form {
            display: inline-block;
            margin-left: 0.5rem;
        }

        .request-status {
            font-weight: 600;
            text-transform: capitalize;
            color: var(--secondary);
        }

        .btn-approve {
            background-color: var(--success);
            color: var(--white);
            border: none;
            padding: 0.4rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .btn-approve:hover {
            background-color: #047857;
        }

        .btn-reject {
            background-color: #DC2626;
            /* Red */
            color: var(--white);
            border: none;
            padding: 0.4rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .btn-reject:hover {
            background-color: #B91C1C;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Your existing animals management HTML here -->

        <!-- Add New Animal Form -->
        <div class="admin-form">
            <h2>Add New Animal</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create" />
                <!-- existing form fields... -->
                <!-- (kept your existing form as is) -->
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required />
                </div>

                <div class="form-group">
                    <label for="species">Species:</label>
                    <input type="text" id="species" name="species" required />
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
                    <div class="file-input-container">
                        <input type="file" id="image" name="image" accept="image/*" />
                        <label for="image" class="file-input-label">Choose Image</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Add Animal</button>
            </form>
        </div>

        <!-- Animals List -->
        <div class="admin-list">
            <h2>Current Animals</h2>
            <div class="animals-grid">
                <?php foreach ($animals as $a) : ?>
                    <div class="animal-card">
                        <div class="animal-image">
                            <img src="<?php echo $a['image_path'] ?? '../images/placeholder.jpg'; ?>" alt="<?php echo htmlspecialchars($a['name']); ?>" />
                            <div class="animal-status"><?php echo htmlspecialchars($a['status']); ?></div>
                        </div>
                        <div class="animal-info">
                            <h3><?php echo htmlspecialchars($a['name']); ?></h3>
                            <p><?php echo htmlspecialchars($a['description']); ?></p>

                            <!-- Edit Form -->
                            <form action="animals.php" method="POST" enctype="multipart/form-data" class="edit-form" style="display: none;">
                                <input type="hidden" name="action" value="update" />
                                <input type="hidden" name="id" value="<?php echo $a['id']; ?>" />

                                <div class="form-group">
                                    <input type="text" name="name" value="<?php echo htmlspecialchars($a['name']); ?>" required />
                                </div>

                                <div class="form-group">
                                    <input type="text" name="species" value="<?php echo htmlspecialchars($a['species']); ?>" required />
                                </div>

                                <div class="form-group">
                                    <textarea name="description" required><?php echo htmlspecialchars($a['description']); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <select name="status" required>
                                        <option value="available" <?php echo $a['status'] === 'available' ? 'selected' : ''; ?>>Available</option>
                                        <option value="pending" <?php echo $a['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="adopted" <?php echo $a['status'] === 'adopted' ? 'selected' : ''; ?>>Adopted</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <input type="file" name="image" accept="image/*" />
                                </div>

                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <button type="button" class="btn btn-secondary cancel-edit">Cancel</button>
                            </form>

                            <div class="animal-actions">
                                <button class="btn btn-secondary edit-btn">Edit</button>
                                <form action="animals.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="delete" />
                                    <input type="hidden" name="id" value="<?php echo $a['id']; ?>" />
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this animal?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- *** NEW: Adoption Requests Section *** -->
        <div class="adoption-requests">
            <h2>Adoption Requests</h2>

            <?php if (count($requests) === 0): ?>
                <p>No adoption requests at this time.</p>
            <?php else: ?>
                <?php foreach ($requests as $req): ?>
                    <div class="request-card">
                        <div class="request-info">
                            <strong>User:</strong> <?php echo htmlspecialchars($req['user_name']); ?> (<?php echo htmlspecialchars($req['user_email']); ?>)<br />
                            <strong>Animal:</strong> <?php echo htmlspecialchars($req['animal_name']); ?><br />
                            <strong>Date:</strong> <?php echo htmlspecialchars($req['application_date']); ?><br />
                            <strong>Status:</strong> <span class="request-status"><?php echo htmlspecialchars($req['status']); ?></span>
                        </div>
                        <div class="request-actions">
                            <?php if ($req['status'] === 'pending'): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="approve_request" />
                                    <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>" />
                                    <button type="submit" class="btn-approve">Approve</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="reject_request" />
                                    <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>" />
                                    <button type="submit" class="btn-reject">Reject</button>
                                </form>
                            <?php else: ?>
                                <em>No actions available</em>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Handle edit button clicks
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const card = this.closest('.animal-card');
                card.querySelector('.edit-form').style.display = 'block';
                card.querySelector('.animal-actions').style.display = 'none';
            });
        });

        // Handle cancel button clicks
        document.querySelectorAll('.cancel-edit').forEach(button => {
            button.addEventListener('click', function() {
                const card = this.closest('.animal-card');
                card.querySelector('.edit-form').style.display = 'none';
                card.querySelector('.animal-actions').style.display = 'block';
            });
        });
    </script>
</body>

</html>