<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/database.php';
require_once 'classes/User.php';
require_once 'classes/Animal.php';

session_start();

// Define base URL for assets
$baseUrl = '/ashkphp-main';

define("ORG_NAME", "Animal Shelter Kosovo");
define("IMAGE_DIR", "https://images.unsplash.com/");

$siteTitle = "Animal Shelter Kosovo";
$tagline = "Adopting & Loving Animals in Kosovo";
$heroDescription = "Give a loving home to animals in need.<br>Every adoption saves a life.";

// Initialize classes
$user = new User($pdo);
$animal = new Animal($pdo);

// Get available animals from database
$availableAnimals = array_filter($animal->getAll(), function ($a) {
    return strtolower($a['status']) === 'available' || strtolower($a['status']) === 'pending';
});

$team = [
    ["name" => "Vera", "age" => 30, "desc" => "I've been working here for <br>6 months and I love it!", "img" => "https://images.unsplash.com/photo-1598897468838-e750a545847d?q=80&w=3870&auto=format&fit=crop", "btn" => "Contact her"],
    ["name" => "Eris", "age" => 19, "desc" => "I'm new here but working with animals <br> really reduces my stress!", "img" => "https://images.unsplash.com/photo-1519456264917-42d0aa2e0625?q=80&w=3870&auto=format&fit=crop", "btn" => "Contact him"],
    ["name" => "Erina", "age" => 18, "desc" => "Co-founder. Helping animals <br>find homes is a blessing.", "img" => "./images/erina.jpeg", "btn" => "Contact me"]
];

function renderAnimal($a)
{
    global $baseUrl;
    $dataName = strtolower($a['name']);
    $dataDesc = strtolower($a['description']);
    
    // Process image path
    $imagePath = !empty($a['image_path']) ? $a['image_path'] : 'public/placeholder.jpg';
    // Make sure the path starts with baseUrl only if it's not already a relative path
    $fullImagePath = strpos($imagePath, '/') === 0 ? $imagePath : $baseUrl . '/' . $imagePath;
    
    // Debug output
    error_log("Rendering animal: " . $a['name'] . " with image path: " . $fullImagePath);
    
    // Determine button state based on status
    $buttonText = 'Adopt';
    $buttonClass = 'btn btn-primary adopt-btn';
    $buttonDisabled = '';
    
    if ($a['status'] === 'adopted') {
        $buttonText = 'Adopted';
        $buttonClass .= ' btn-disabled';
        $buttonDisabled = 'disabled';
    } elseif ($a['status'] === 'pending') {
        $buttonText = 'Pending';
        $buttonClass .= ' btn-disabled';
        $buttonDisabled = 'disabled';
    }

    return "
    <div class='animal-card loading' data-name='$dataName' data-description='$dataDesc'>
        <div class='animal-image'>
            <img src='" . htmlspecialchars($fullImagePath) . "' 
                 alt='" . htmlspecialchars($a['name']) . "' 
                 onerror=\"this.src='" . $baseUrl . "/public/placeholder.jpg'; console.log('Using placeholder for: ' + this.alt);\"
                 onload=\"console.log('Successfully loaded image for: ' + this.alt);\">
            <div class='animal-status " . htmlspecialchars(strtolower($a['status'])) . "'>" . htmlspecialchars($a['status']) . "</div>
        </div>
        <div class='animal-info'>
            <h3>" . htmlspecialchars($a['name']) . "</h3>
            <p>" . htmlspecialchars($a['description']) . "</p>
            <p class='species-tag'>" . htmlspecialchars($a['species']) . "</p>
            <button class='$buttonClass' data-animal-id='" . intval($a['id']) . "' $buttonDisabled>$buttonText</button>
        </div>
    </div>";
}

function renderTeamMember($m)
{
    return "
    <div class='about-card loading'>
        <div class='about-image'>
            <img src='{$m['img']}' alt='{$m['name']}'>
            <div class='age-badge'>{$m['age']} years old</div>
        </div>
        <h3>{$m['name']}</h3>
        <p>{$m['desc']}</p>
        <button class='btn btn-gray-3'>{$m['btn']}</button>
    </div>";
}

function truncateText($text, $limit = 100)
{
    return strlen($text) > $limit ? substr($text, 0, $limit) . "..." : $text;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ORG_NAME; ?> - Adopting & Loving Animals</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <div class="logo">
                <a href="index.php">
                    <div class="logo-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-paw-print w-6 h-6 text-white">
                            <circle cx="11" cy="4" r="2"></circle>
                            <circle cx="18" cy="8" r="2"></circle>
                            <circle cx="20" cy="16" r="2"></circle>
                            <path
                                d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z">
                            </path>
                        </svg></div>
                    <span>Animal Shelter Kosovo</span>
                </a>
            </div>
            <div class="nav-links" id="navLinks">
                <a href="index.php">Home</a>
                <a href="#about">About</a>
                <a href="#animals">Animals</a>
                <a href="volunteering.html">Volunteer</a>
                <a href="adoption.php">Adopt</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($user->isAdmin()): ?>
                        <a href="admin/animals.php">Admin Panel</a>
                    <?php endif; ?>
                    <a href="logout.php" class="btn btn-secondary">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary">Login</a>
                    <a href="register.php" class="btn btn-secondary">Register</a>
                <?php endif; ?>
            </div>
            <div class="mobile-menu-btn" id="mobileMenuBtn">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-bg">
            <img src="https://images.unsplash.com/photo-1478098711619-5ab0b478d6e6?q=80&w=3870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                alt="Happy animals" class="hero-image">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-content">
            <div class="badge">🐾 Saving Lives in Kosovo</div>
            <h1 class="hero-title">
                <?php echo $tagline; ?>
            </h1>
            <p class="hero-description"><?php echo $heroDescription; ?></p>
            <div class="hero-buttons">
                <a href="adoption.html" class="btn btn-primary btn-lg">
                    Adopt Now <span class="arrow">→</span>
                </a>
                <a href="#about" class="btn btn-outline btn-lg">Learn More</a>
            </div>
        </div>
    </section>
    <!-- Services Section -->
    <section class="services" id="services">
        <div class="container">
            <div class="section-header">
                <div class="badge">🐾 Our Services</div>
                <h2>Welcome to Our <span class="text-green">Animal Care</span> Community</h2>
                <p>Dive into a world of whiskers, purrs, and heartwarming stories. Whether you're a pet lover, new pet
                    parent, or just curious, we're here to help you every step of the way.</p>
            </div>

            <div class="services-grid">
                <div class="service-card loading">
                    <div class="service-icon blue">❤️</div>
                    <h3>Animal Cafe</h3>
                    <p>Relax with adorable animals while enjoying your favorite drink. Our animal cafe is a peaceful
                        haven for animal lovers.</p>
                </div>
                <div class="service-card loading">
                    <div class="service-icon green">🛡️</div>
                    <h3>Animal Care</h3>
                    <p>Tips, tricks, and expert advice to ensure your animal friends are happy, healthy, and well cared
                        for every day.</p>
                </div>
                <div class="service-card loading">
                    <div class="service-icon purple">🏆</div>
                    <h3>Pet Guide</h3>
                    <p>Our essential guide covers nutrition, grooming, and fun activities that strengthen the bond
                        between you and your pet.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Animals Gallery -->
    <section id="animals" class="animals-gallery">
        <div class="container">
            <div class="section-header">
                <div class="badge">🏠 Find Your Friend</div>
                <h2>Adoptable Animals in <span class="text-blue">Kosovo</span></h2>
                <p>Discover some of the lovely animals you can adopt and give a forever home to.</p>
            </div>

            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search for animals..." id="searchInput">
            </div>

            <div class="animals-grid" id="animalsGrid">
                <?php
                foreach ($availableAnimals as $animal) {
                    echo renderAnimal($animal);
                }
                ?>
            </div>

            <!-- No Results Message -->
            <p id="noResults" style="text-align:center; display:none; margin-top:20px; color:gray;">
                No animals found.
            </p>

            <?php if (empty($availableAnimals)): ?>
                <div class="no-animals-message" style="text-align:center; margin: 2rem 0;">
                    <p>No animals are currently available for adoption.</p>
                    <p>Please check back later or <a href="contact.php">contact us</a> for more information.</p>
                </div>
            <?php endif; ?>

        </div>
    </section>

    <!-- Animal Care Section -->
    <section class="animal-care">
        <div class="container">
            <div class="care-content">
                <div class="care-image">
                    <div class="image-decoration"></div>
                    <div class="image-overlay">
                        <img src="https://images01.nicepagecdn.com/c461c07a441a5d220e8feb1a/6996e6cb7dc85d03a1444c62/uyuyuy.jpg"
                            alt="Caring for animals">
                    </div>
                </div>
                <div class="care-text">
                    <div class="badge">💚 Our Mission</div>
                    <h2>Love. Respect. <span class="text-green">Care.</span></h2>
                    <p>Taking care of animals means more than just feeding them. It's about giving them love, attention,
                        and a safe place to call home. Spend time playing with them, talk to them, and make sure they
                        get regular checkups with a vet.</p>
                    <p>Animals have emotions too — they feel joy, sadness, excitement, and fear. When you show them
                        kindness, they give you unconditional love in return. Whether it's a wagging tail or a gentle
                        purr, they'll thank you in their own way.</p>
                    <a href="#contact" class="btn btn-primary btn-lg">
                        Learn About Care <span class="arrow">→</span>
                    </a>
                </div>
            </div>
        </div>
    </section>


    <!-- About Us Section -->
    <section id="about" class="about-us">
        <div class="container">
            <div class="section-header">
                <div class="badge">👋 Meet Our Team</div>
                <h2>The People Behind <span class="text-blue">Our Mission</span></h2>
                <p>Meet our dedicated team members who work tirelessly to help animals find their forever homes.</p>
            </div>

            <div class="about-cards">
                <?php foreach ($team as $member): ?>
                    <?php echo renderTeamMember($member); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contact Footer -->
    <footer id="contact" class="contact-footer">
        <div class="container">
            <div class="footer-content">
                <h2>Want to Know More About Helping Animals?</h2>
                <p>We'd love to hear from you! Whether you have questions about adopting, volunteering, or learning more
                    about stray animals in Kosovo — we're here to help.</p>

                <form class="contact-form" id="contactForm">
                    <div class="form-group">
                        <input type="text" id="name" name="name" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder="Your Email" required>
                    </div>
                    <div class="form-group">
                        <textarea id="message" name="message" placeholder="Your Message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                </form>

                <div id="formMessage" class="form-message"></div>

                <script>
                document.getElementById('contactForm').addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const submitButton = this.querySelector('button[type="submit"]');
                    const formMessage = document.getElementById('formMessage');
                    
                    // Disable form and show loading state
                    submitButton.disabled = true;
                    submitButton.textContent = 'Sending...';
                    formMessage.style.display = 'none';
                    
                    try {
                        const response = await fetch('process_contact.php', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json',
                                'Cache-Control': 'no-cache',
                                'Pragma': 'no-cache'
                            },
                            cache: 'no-store'
                        });
                        
                        // Log response details for debugging
                        console.log('Response status:', response.status);
                        console.log('Response headers:', Object.fromEntries(response.headers.entries()));
                        
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            // Get the response text for debugging
                            const responseText = await response.text();
                            console.error('Invalid response:', responseText);
                            throw new Error(`Invalid response type: ${contentType}. Response: ${responseText.substring(0, 200)}...`);
                        }
                        
                        // Parse the response as JSON
                        const data = await response.json().catch(e => {
                            throw new Error(`JSON parse error: ${e.message}`);
                        });
                        console.log('Response data:', data);
                        
                        // Handle the response
                        formMessage.textContent = data.message || 'An unknown error occurred';
                        formMessage.className = `form-message ${data.success ? 'success' : 'error'}`;
                        formMessage.style.display = 'block';
                        
                        if (data.success) {
                            this.reset();
                        }
                    } catch (error) {
                        console.error('Request error:', error);
                        formMessage.textContent = error.message || 'An error occurred. Please try again later.';
                        formMessage.className = 'form-message error';
                        formMessage.style.display = 'block';
                    } finally {
                        // Re-enable form
                        submitButton.disabled = false;
                        submitButton.textContent = 'Send Message';
                        
                        // Auto-hide message after 5 seconds
                        setTimeout(() => {
                            if (formMessage.style.display === 'block') {
                                formMessage.style.display = 'none';
                            }
                        }, 5000);
                    }
                });
                </script>

                <div class="social-links">
                    <a href="https://instagram.com/erinahoxhaxhiiku" target="_blank" class="social-link">
                        <div class="social-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                        </div>
                    </a>
                    <a href="https://facebook.com/erinahoxhaxhiku" target="_blank" class="social-link">
                        <div class="social-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                        </div>
                    </a>
                    <a href="https://www.linkedin.com/in/erina-hoxhaxhiku-448160339/" target="_blank" class="social-link">
                        <div class="social-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                <rect x="2" y="9" width="4" height="12"></rect>
                                <circle cx="4" cy="4" r="2"></circle>
                            </svg>
                        </div>
                    </a>
                </div>
            </div>
            <br>
            <div class="footer-bottom">
                <p>&copy; 2025 Animal Rescue Kosovo. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="script/javascript.js"></script>
    <script>
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const animalsGrid = document.getElementById('animalsGrid');
        const noResults = document.getElementById('noResults');
        const animalCards = document.querySelectorAll('.animal-card');

        // Add click event listener for all Learn More buttons
        document.querySelectorAll('.adopt-btn').forEach(button => {
            button.addEventListener('click', function() {
                const originalText = this.textContent;
                this.textContent = 'Available';

                // Optional: Change back to original text after 2 seconds
                setTimeout(() => {
                    this.textContent = originalText;
                }, 2000);
            });
        });

        document.querySelectorAll('.adopt-btn').forEach(button => {
            button.addEventListener('click', function() {
                const animalId = this.dataset.animalId;
                if (!animalId) {
                    alert('Animal ID not found.');
                    return;
                }

                // Disable button to prevent multiple clicks
                this.disabled = true;
                this.textContent = 'Submitting...';

                fetch('apply_adoption.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `animal_id=${encodeURIComponent(animalId)}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.textContent = 'Application Sent';
                            alert(data.message);
                        } else {
                            this.disabled = false;
                            this.textContent = 'Adopt';
                            alert(data.message);
                        }
                    })
                    .catch(() => {
                        this.disabled = false;
                        this.textContent = 'Adopt';
                        alert('There was an error submitting your application.');
                    });
            });
        });


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
    </script>

</body>

</html>