<?php
require_once 'config/database.php';
require_once 'classes/Animal.php';

// Initialize Animal class
$animal = new Animal($pdo);

// Get adopted animals from database (we only need adopted animals here)
$adoptedAnimals = array_filter($animal->getAll(), function($a) {
    return strtolower($a['status']) === 'adopted';
});

// Function to render adopted animal card
function renderAdoptedAnimal($a) {
    $dataName = strtolower($a['name']);
    $dataDesc = strtolower($a['description']);
    $imagePath = !empty($a['image_path']) ? 'admin/' . $a['image_path'] : 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=400&q=80';

    return "
    <div class='story-card loading'>
        <div class='story-image'>
            <img src='" . htmlspecialchars($imagePath) . "' alt='" . htmlspecialchars($a['name']) . "'>
            <div class='story-status'>✅ Adopted</div>
        </div>
        <h3>" . htmlspecialchars($a['name']) . "</h3>
        <p>" . htmlspecialchars($a['description']) . "</p>
    </div>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoption - Animal Shelter Kosovo</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <div class="logo">
                <a href="index.php">
                    <div class="logo-icon green">🐾</div>
                    <span>Animal Shelter Kosovo</span>
                </a>
            </div>
            <div class="nav-links" id="navLinks">
                <a href="index.php#about">About</a>
                <a href="index.php#animals">Animals</a>
                <a href="volunteering.html">Volunteer</a>
                <a href="index.php" class="btn btn-outline">Back to Home</a>
            </div>
            <div class="mobile-menu-btn" id="mobileMenuBtn">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero adoption-hero">
        <div class="hero-bg">
            <img
                src="https://images.unsplash.com/photo-1647877911821-2af4403f3d64?q=80&w=3870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-content">
            <div class="badge green">🏠 Success Stories</div>
            <h1 class="hero-title">
                Happy Endings<br>
                <span class="gradient-text">Meet Our Adopted Friends</span>
            </h1>
            <p class="hero-description">
                See the wonderful stories of animals who found their forever homes.<br>Your future companion could be next!
            </p>
            <div class="hero-buttons">
                <a href="index.php#animals" class="btn btn-green btn-lg">
                    Find Your Companion <span class="arrow">→</span>
                </a>
                <a href="#adopted" class="btn btn-outline btn-lg">View Success Stories</a>
            </div>
        </div>
    </section>

    <!-- Adopted Animals Section -->
    <section id="adopted" class="animals-gallery">
        <div class="container">
            <div class="section-header">
                <div class="badge green">❤️ Success Stories</div>
                <h2>Our <span class="text-green">Adopted</span> Friends</h2>
                <p>Meet the animals who found their forever homes through our shelter.</p>
            </div>

            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search adopted animals..." id="searchInput">
            </div>

            <div class="animals-grid" id="animalsGrid">
                <?php foreach ($adoptedAnimals as $adoptedAnimal): 
                    $imagePath = !empty($adoptedAnimal['image_path']) ? 'admin/' . $adoptedAnimal['image_path'] : 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=400&q=80';
                ?>
                    <div class="animal-card loading" data-name="<?php echo strtolower($adoptedAnimal['name']); ?>" data-description="<?php echo strtolower($adoptedAnimal['description']); ?>">
                        <div class="animal-image">
                            <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($adoptedAnimal['name']); ?>">
                            <div class="animal-status">Adopted</div>
                        </div>
                        <div class="animal-info">
                            <h3><?php echo htmlspecialchars($adoptedAnimal['name']); ?></h3>
                            <p><?php echo htmlspecialchars($adoptedAnimal['description']); ?></p>
                            <p class="species-tag"><?php echo htmlspecialchars($adoptedAnimal['species']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- No Results Message -->
            <p id="noResults" style="text-align:center; display:none; margin-top:20px; color:gray;">
                No adopted animals found matching your search.
            </p>
        </div>
    </section>

    <!-- Success Stories -->
    <section class="success-stories">
        <div class="container">
            <div class="section-header">
                <div class="badge green">❤️ Happy Endings</div>
                <h2>Success <span class="text-green">Stories</span></h2>
                <p>See how adoption has changed lives for both pets and their new families.</p>
            </div>

            <div class="stories-grid">
                <?php if (!empty($adoptedAnimals)): ?>
                    <?php foreach ($adoptedAnimals as $adoptedAnimal): ?>
                        <?php echo renderAdoptedAnimal($adoptedAnimal); ?>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Your existing success stories -->
                <div class="story-card loading">
                    <div class="story-image">
                        <img src="https://images.unsplash.com/photo-1583337130417-3346a1be7dee?auto=format&fit=crop&w=400&q=80"
                            alt="Bella and family">
                        <div class="story-status">✅ Adopted</div>
                    </div>
                    <h3>Bella & The Johnson Family</h3>
                    <p>Bella found her forever home with the Johnsons after 6 months at the shelter. Now she's the queen
                        of the house!</p>
                </div>
                <div class="story-card loading">
                    <div class="story-image">
                        <img src="https://images.unsplash.com/photo-1552053831-71594a27632d?auto=format&fit=crop&w=400&q=80"
                            alt="Max and Sarah">
                        <div class="story-status">✅ Adopted</div>
                    </div>
                    <h3>Max & Sarah</h3>
                    <p>Max was a shy rescue dog who blossomed with Sarah's patience and love. They're now inseparable
                        hiking buddies!</p>
                </div>
                <div class="story-card loading">
                    <div class="story-image">
                        <img src="https://images.unsplash.com/photo-1543852786-1cf6624b9987?auto=format&fit=crop&w=400&q=80"
                            alt="Luna and family">
                        <div class="story-status">✅ Adopted</div>
                    </div>
                    <h3>Luna & The Petrovics</h3>
                    <p>Luna the cat went from street life to luxury with the Petrovic family. She now rules her own cat
                        tower kingdom!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Rest of the content remains the same -->
    <?php include('includes/adoption_content.php'); ?>

    <script src="script/javascript.js"></script>
    <script>
        // Chart.js implementation
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('volunteerChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Monthly Volunteers',
                            data: [150, 180, 200, 220, 190, 210],
                            borderColor: '#2563eb',
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const animalsGrid = document.getElementById('animalsGrid');
        const noResults = document.getElementById('noResults');
        const animalCards = document.querySelectorAll('.animal-card');

        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            let foundResults = false;

            animalCards.forEach(card => {
                const name = card.dataset.name;
                const description = card.dataset.description;
                
                if (name.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = 'block';
                    foundResults = true;
                } else {
                    card.style.display = 'none';
                }
            });

            noResults.style.display = foundResults ? 'none' : 'block';
        });

        // Loading animation
        document.addEventListener('DOMContentLoaded', function() {
            const loadingElements = document.querySelectorAll('.loading');
            
            loadingElements.forEach(element => {
                element.classList.add('loaded');
            });
        });

        // Mobile menu
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navLinks = document.getElementById('navLinks');

        mobileMenuBtn.addEventListener('click', function() {
            this.classList.toggle('active');
            navLinks.classList.toggle('active');
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Animate statistics
        function animateValue(element, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                element.innerHTML = Math.floor(progress * (end - start) + start);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        // Animate all stat numbers
        document.querySelectorAll('.stat-number').forEach(stat => {
            const target = parseInt(stat.getAttribute('data-target'));
            animateValue(stat, 0, target, 2000);
        });
    </script>
</body>

</html> 