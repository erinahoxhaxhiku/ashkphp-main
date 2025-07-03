// Utility functions
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return emailRegex.test(email)
}

function formatPhoneNumber(phone) {
  return phone.replace(/(\d{3})(\d{2})(\d{3})(\d{3})/, "+$1 $2 $3 $4")
}

function validateForm(formData) {
  const required = ["name", "email", "message"]
  const errors = []

  required.forEach((field) => {
    if (!formData.get(field) || formData.get(field).trim() === "") {
      errors.push(`${field.charAt(0).toUpperCase() + field.slice(1)} is required`)
    }
  })

  const email = formData.get("email")
  if (email && !isValidEmail(email)) {
    errors.push("Please enter a valid email address")
  }

  return errors
}

// Modern JavaScript for Animal Shelter Kosovo
document.addEventListener("DOMContentLoaded", () => {
  // Navbar functionality
  const navbar = document.getElementById("navbar")
  const mobileMenuBtn = document.getElementById("mobileMenuBtn")
  const navLinks = document.getElementById("navLinks")
  let lastScrollTop = 0

  // Get formMessage element once
  const formMessage = document.getElementById("formMessage")

  // Navbar scroll effect
  window.addEventListener("scroll", () => {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop

    if (scrollTop > 100) {
      navbar.classList.add("scrolled")
    } else {
      navbar.classList.remove("scrolled")
    }

    // Hide navbar on scroll down, show on scroll up
    if (scrollTop > lastScrollTop && scrollTop > 200) {
      navbar.style.transform = "translateY(-100%)"
    } else {
      navbar.style.transform = "translateY(0)"
    }

    lastScrollTop = scrollTop
  })

  // Mobile menu toggle
  if (mobileMenuBtn && navLinks) {
    mobileMenuBtn.addEventListener("click", () => {
      navLinks.classList.toggle("active")
      mobileMenuBtn.classList.toggle("active")
    })

    // Close mobile menu when clicking on a link
    navLinks.addEventListener("click", (e) => {
      if (e.target.tagName === "A") {
        navLinks.classList.remove("active")
        mobileMenuBtn.classList.remove("active")
      }
    })
  }

  // Smooth scrolling for anchor links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault()
      const target = document.querySelector(this.getAttribute("href"))
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
        })
      }
    })
  })

  // Intersection Observer for animations
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("loaded")
      }
    })
  }, observerOptions)

  // Observe elements for animation
  document.querySelectorAll(".loading").forEach((el) => {
    observer.observe(el)
  })

  // Search functionality
  const searchInput = document.getElementById("searchInput")
  const animalsGrid = document.getElementById("animalsGrid")

  if (searchInput && animalsGrid) {
    searchInput.addEventListener("input", function () {
      const searchTerm = this.value.toLowerCase()
      const animalCards = animalsGrid.querySelectorAll(".animal-card")

      animalCards.forEach((card) => {
        const name = card.getAttribute("data-name") || ""
        const description = card.getAttribute("data-description") || ""

        if (name.includes(searchTerm) || description.includes(searchTerm)) {
          card.style.display = "block"
        } else {
          card.style.display = "none"
        }
      })
    })
  }

  // Show message function
  function showMessage(text, type) {
    if (formMessage) {
      formMessage.textContent = text
      formMessage.className = `form-message ${type}`
      formMessage.style.display = "block"

      // Hide message after 5 seconds
      setTimeout(() => {
        formMessage.style.display = "none"
      }, 5000)
    }
  }

  // Adopt button functionality
  document.querySelectorAll(".adopt-btn").forEach((btn) => {
    btn.addEventListener("click", async function(e) {
      e.preventDefault();
      
      // Check if button is already disabled
      if (this.disabled) {
        return;
      }

      const animalId = this.getAttribute('data-animal-id');
      if (!animalId) {
        showMessage('Error: Could not identify the animal.', 'error');
        return;
      }

      try {
        const response = await fetch('apply_adoption.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `animal_id=${animalId}`,
          credentials: 'same-origin' // Include cookies
        });

        const data = await response.json();
        
        if (data.success) {
          // Update button state
          this.textContent = 'Pending';
          this.disabled = true;
          this.classList.add('btn-disabled');
          
          // Update status display
          const statusDiv = this.closest('.animal-card').querySelector('.animal-status');
          if (statusDiv) {
            statusDiv.textContent = 'Pending';
          }
          
          // Show success message
          showMessage(data.message, 'success');

          // Refresh the page after a short delay
          setTimeout(() => {
            window.location.reload();
          }, 2000);
        } else {
          if (data.message.includes('must be logged in')) {
            window.location.href = 'login.php';
          } else {
            showMessage(data.message, 'error');
          }
        }
      } catch (error) {
        console.error('Error:', error);
        showMessage('An error occurred. Please try again.', 'error');
      }
    });
  });

  // Function to update animal status
  function updateAnimalStatus(animalId, status) {
    const animalCard = document.querySelector(`.animal-card [data-animal-id="${animalId}"]`)?.closest('.animal-card');
    if (!animalCard) return;

    const statusDiv = animalCard.querySelector('.animal-status');
    const adoptBtn = animalCard.querySelector('.adopt-btn');

    if (statusDiv) {
      statusDiv.textContent = status;
    }

    if (adoptBtn) {
      if (status === 'adopted') {
        adoptBtn.textContent = 'Adopted';
        adoptBtn.disabled = true;
        adoptBtn.classList.add('btn-disabled');
      } else if (status === 'pending') {
        adoptBtn.textContent = 'Pending';
        adoptBtn.disabled = true;
        adoptBtn.classList.add('btn-disabled');
      } else {
        adoptBtn.textContent = 'Adopt';
        adoptBtn.disabled = false;
        adoptBtn.classList.remove('btn-disabled');
      }
    }
  }

  // Add styles for disabled button
  const style = document.createElement('style');
  style.textContent = `
    .btn-disabled {
      opacity: 0.6;
      cursor: not-allowed;
      background: #ccc !important;
      pointer-events: none;
    }
    .animal-status {
      text-transform: capitalize;
      font-weight: bold;
    }
  `;
  document.head.appendChild(style);

  // Counter animation for statistics
  function animateCounters() {
    const counters = document.querySelectorAll(".stat-number[data-target]")

    counters.forEach((counter) => {
      const target = Number.parseInt(counter.getAttribute("data-target"))
      const count = Number.parseInt(counter.innerText)
      const increment = target / 200

      if (count < target) {
        counter.innerText = Math.ceil(count + increment)
        setTimeout(animateCounters, 1)
      } else {
        counter.innerText = target
      }
    })
  }

  // Trigger counter animation when statistics section is in view
  const statsSection = document.querySelector(".statistics, .about-section")
  if (statsSection) {
    const statsObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animateCounters()
          statsObserver.unobserve(entry.target)
        }
      })
    })
    statsObserver.observe(statsSection)
  }

  // Card hover effects
  document
    .querySelectorAll(".service-card, .animal-card, .about-card, .opportunity-card, .reason-card, .story-card")
    .forEach((card) => {
      card.addEventListener("mouseenter", function () {
        this.style.transform = "translateY(-8px) scale(1.02)"
      })

      card.addEventListener("mouseleave", function () {
        this.style.transform = "translateY(0) scale(1)"
      })
    })

  // Parallax effect for hero sections
  const heroSections = document.querySelectorAll(".hero")
  heroSections.forEach((hero) => {
    const heroImage = hero.querySelector(".hero-image")
    if (heroImage) {
      window.addEventListener("scroll", () => {
        const scrolled = window.pageYOffset
        const speed = scrolled * 0.5
        heroImage.style.transform = `translateY(${speed}px)`
      })
    }
  })

  // Image lazy loading
  const images = document.querySelectorAll("img[data-src]")
  const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const img = entry.target
        img.src = img.dataset.src
        img.classList.remove("lazy")
        imageObserver.unobserve(img)
      }
    })
  })

  images.forEach((img) => imageObserver.observe(img))

  // Button click tracking
  document.addEventListener("click", (e) => {
    if (e.target.matches(".btn, .adopt-btn")) {
      console.log("Button clicked:", e.target.textContent.trim())
    }
  })

  // Performance optimization: Debounce scroll events
  function debounce(func, wait) {
    let timeout
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout)
        func(...args)
      }
      clearTimeout(timeout)
      timeout = setTimeout(later, wait)
    }
  }

  // Apply debouncing to scroll events
  const debouncedScroll = debounce(() => {
    // Any additional scroll-based functionality can go here
  }, 10)

  window.addEventListener("scroll", debouncedScroll)

  // Add loading animation to page
  window.addEventListener("load", () => {
    document.body.classList.add("loaded")
  })

  // Keyboard navigation support
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      // Close mobile menu on escape
      if (navLinks && navLinks.classList.contains("active")) {
        navLinks.classList.remove("active")
        mobileMenuBtn.classList.remove("active")
      }
    }
  })

  // Focus management for accessibility
  const focusableElements = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'

  document.addEventListener("keydown", (e) => {
    if (e.key === "Tab") {
      const focusableContent = document.querySelectorAll(focusableElements)
      const firstFocusableElement = focusableContent[0]
      const lastFocusableElement = focusableContent[focusableContent.length - 1]

      if (e.shiftKey) {
        if (document.activeElement === firstFocusableElement) {
          lastFocusableElement.focus()
          e.preventDefault()
        }
      } else {
        if (document.activeElement === lastFocusableElement) {
          firstFocusableElement.focus()
          e.preventDefault()
        }
      }
    }
  })

  console.log("Animal Shelter Kosovo website loaded successfully! 🐾")
})

// Export functions for use in other scripts
window.AnimalShelter = {
  formatPhoneNumber,
  validateForm,
  isValidEmail,
}
