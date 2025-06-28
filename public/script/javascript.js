// Modern JavaScript for Animal Shelter Kosovo
document.addEventListener("DOMContentLoaded", () => {
  // Navbar scroll effect
  const navbar = document.querySelector(".navbar")
  let lastScrollTop = 0

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

  // Smooth scrolling for navigation links
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
  document.querySelectorAll(".card, .gallery-item, .animal-card").forEach((el) => {
    el.classList.add("loading")
    observer.observe(el)
  })

  // Contact form handling
  const contactForm = document.getElementById("contactForm")
  if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault()

      const formData = new FormData(this)
      const name = formData.get("name") || document.getElementById("name")?.value
      const email = formData.get("email") || document.getElementById("email")?.value
      const message = formData.get("message") || document.getElementById("message")?.value

      // Basic validation
      if (!name || !email || !message) {
        showMessage("Please fill out all fields.", "error")
        return
      }

      if (!isValidEmail(email)) {
        showMessage("Please enter a valid email address.", "error")
        return
      }

      // Simulate form submission
      showMessage("Thank you for contacting us! We'll be in touch soon.", "success")
      this.reset()
    })
  }

  // Email validation
  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return emailRegex.test(email)
  }

  // Show message function
  function showMessage(text, type) {
    const messageEl = document.getElementById("formMsg") || createMessageElement()
    messageEl.textContent = text
    messageEl.className = type === "error" ? "error-message" : "success-message"
    messageEl.style.display = "block"

    // Hide message after 5 seconds
    setTimeout(() => {
      messageEl.style.display = "none"
    }, 5000)
  }

  // Create message element if it doesn't exist
  function createMessageElement() {
    const messageEl = document.createElement("p")
    messageEl.id = "formMsg"
    messageEl.style.marginTop = "10px"
    messageEl.style.padding = "10px"
    messageEl.style.borderRadius = "5px"
    messageEl.style.display = "none"

    const form = document.getElementById("contactForm")
    if (form) {
      form.appendChild(messageEl)
    }

    return messageEl
  }

  // Add styles for message elements
  const style = document.createElement("style")
  style.textContent = `
        .success-message {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .error-message {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
    `
  document.head.appendChild(style)

  // Gallery image lazy loading
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

  // Card hover effects
  document.querySelectorAll(".card, .gallery-item, .animal-card").forEach((card) => {
    card.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-8px) scale(1.02)"
    })

    card.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0) scale(1)"
    })
  })

  // Mobile menu toggle (if needed)
  const mobileMenuBtn = document.querySelector(".mobile-menu-btn")
  const navLinks = document.querySelector(".nav-links")

  if (mobileMenuBtn && navLinks) {
    mobileMenuBtn.addEventListener("click", function () {
      navLinks.classList.toggle("active")
      this.classList.toggle("active")
    })
  }

  // Parallax effect for hero section
  const heroSection = document.querySelector(".foto")
  if (heroSection) {
    window.addEventListener("scroll", () => {
      const scrolled = window.pageYOffset
      const parallax = heroSection.querySelector(".foto2")
      if (parallax) {
        const speed = scrolled * 0.5
        parallax.style.transform = `translateY(${speed}px)`
      }
    })
  }

  // Counter animation for statistics
  function animateCounters() {
    const counters = document.querySelectorAll(".counter")
    counters.forEach((counter) => {
      const target = Number.parseInt(counter.getAttribute("data-target"))
      const count = Number.parseInt(counter.innerText)
      const increment = target / 200

      if (count < target) {
        counter.innerText = Math.ceil(count + increment)
        setTimeout(() => animateCounters(), 1)
      } else {
        counter.innerText = target
      }
    })
  }

  // Trigger counter animation when in view
  const statsSection = document.querySelector(".stats-section")
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

  // Add loading animation to page
  window.addEventListener("load", () => {
    document.body.classList.add("loaded")
  })

  // Donation button functionality
  const donateButtons = document.querySelectorAll(".donate-btn")
  donateButtons.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault()
      showMessage("Thank you for your interest in donating! Please contact us for donation information.", "success")
    })
  })

  // Adoption button functionality
  const adoptButtons = document.querySelectorAll(".adopt-btn")
  adoptButtons.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault()
      const animalName = this.closest(".gallery-item, .animal-card")?.querySelector("h3")?.textContent
      if (animalName) {
        showMessage(
          `Thank you for your interest in adopting ${animalName}! Please contact us to start the adoption process.`,
          "success",
        )
      } else {
        showMessage("Thank you for your interest in adoption! Please contact us to learn more.", "success")
      }
    })
  })

  // Volunteer button functionality
  const volunteerButtons = document.querySelectorAll(".volunteer-btn")
  volunteerButtons.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault()
      showMessage("Thank you for your interest in volunteering! We'll contact you soon with opportunities.", "success")
    })
  })

  // Search functionality (if search input exists)
  const searchInput = document.querySelector(".search-input")
  if (searchInput) {
    searchInput.addEventListener("input", function () {
      const searchTerm = this.value.toLowerCase()
      const animals = document.querySelectorAll(".gallery-item, .animal-card")

      animals.forEach((animal) => {
        const name = animal.querySelector("h3")?.textContent.toLowerCase()
        const description = animal.querySelector("p")?.textContent.toLowerCase()

        if (name?.includes(searchTerm) || description?.includes(searchTerm)) {
          animal.style.display = "block"
        } else {
          animal.style.display = "none"
        }
      })
    })
  }

  // Add click tracking for analytics (placeholder)
  document.addEventListener("click", (e) => {
    if (e.target.matches(".btn, .footer-btn, .adopt-btn, .volunteer-btn, .donate-btn")) {
      // Track button clicks for analytics
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
    // Any scroll-based functionality can go here
  }, 10)

  window.addEventListener("scroll", debouncedScroll)

  console.log("Animal Shelter Kosovo website loaded successfully! 🐾")
})

// Additional utility functions
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

function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return emailRegex.test(email)
}

// Export functions for use in other scripts
window.AnimalShelter = {
  formatPhoneNumber,
  validateForm,
  isValidEmail,
}
