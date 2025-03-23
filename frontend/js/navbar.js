// navbar.js

// Display user initial from localStorage
function displayUserInitial() {
    const userInitial = localStorage.getItem('userInitial');
    if (userInitial) {
        document.getElementById('user-initial').textContent = userInitial;
    }
}

// Update Cart Count from localStorage
function updateCartCount() {
    const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    const cartCount = document.getElementById('cart-count');
    cartCount.textContent = cartItems.reduce((total, item) => total + item.quantity, 0);
}

// Logout functionality
function setupLogout() {
    document.getElementById('logout-button').addEventListener('click', function () {
        // Clear session storage and local storage
        localStorage.removeItem('userInitial');
        localStorage.removeItem('user_id');
        localStorage.removeItem('cartItems');
        sessionStorage.clear();

        // Redirect to login page
        window.location.href = '../backend/login.php';
    });
}

// Toggle dropdown on click
function setupDropdown() {
    document.getElementById('user-initial').addEventListener('click', function () {
        const dropdown = document.getElementById('user-dropdown');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });

    // Hide dropdown when clicking outside
    document.addEventListener('click', function (event) {
        const dropdown = document.getElementById('user-dropdown');
        if (!document.getElementById('user-cart-container').contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });
}

// Make cart count clickable (redirect to cart page)
function setupCartClick() {
    document.getElementById('cart-container').addEventListener('click', function () {
        // Redirect to cart page
        window.location.href = 'cart.html'; // Replace with your cart page or modal logic
    });
}

// Initialize all navbar functionality
function initializeNavbar() {
    displayUserInitial();
    updateCartCount();
    setupLogout();
    setupDropdown();
    setupCartClick();

    // Listen for changes in localStorage to update the cart count dynamically
    window.addEventListener('storage', function (event) {
        if (event.key === 'cartItems') {
            updateCartCount();
        }
    });
}

// Run the initialization when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', initializeNavbar);