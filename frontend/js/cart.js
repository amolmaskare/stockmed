// cart.js

// Initialize cartItems in localStorage if it doesn't exist
if (!localStorage.getItem('cartItems')) {
    localStorage.setItem('cartItems', JSON.stringify([]));
}

// Function to update the cart count
function updateCartCount() {
    const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    const cartCount = document.getElementById('cart-count');
    if (cartCount) {
        cartCount.textContent = cartItems.reduce((total, item) => total + item.quantity, 0);
    }
}

// Function to handle cart count click
function setupCartCountClick() {
    const cartContainer = document.getElementById('cart-container');
    if (cartContainer) {
        cartContainer.addEventListener('click', function () {
            // Redirect to the cart page
            window.location.href = 'cart.html';
        });
    } else {
        // Retry after a short delay if the cart container is not found
        console.warn('Cart container not found. Retrying in 500ms...');
        setTimeout(setupCartCountClick, 500);
    }
}

// Initialize all cart-related functionality
function initializeCart() {
    const cartContainer = document.getElementById('cart-container');
    const cartCount = document.getElementById('cart-count');
  
    if (!cartContainer || !cartCount) {
      console.error('Cart container or count not found. Retrying in 500ms...');
      setTimeout(initializeCart, 500); // Retry after 500ms
      return;
    }
  
    // Initialize cart logic
    console.log('Cart initialized successfully!');
  
    // Example: Update cart count
    const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    cartCount.textContent = cartItems.reduce((total, item) => total + item.quantity, 0);
  
    // Example: Add click event to cart container
    cartContainer.addEventListener('click', () => {
      window.location.href = 'cart.html';
    });
  }
  
  // Wait for the DOM to be fully loaded before initializing the cart
  document.addEventListener('DOMContentLoaded', initializeCart);

// Run the initialization when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', initializeCart);

// Listen for changes in localStorage (cross-tab updates)
window.addEventListener('storage', function (event) {
    if (event.key === 'cartItems') {
        updateCartCount();
    }
});

// Listen for custom cart update events (same-tab updates)
window.addEventListener('cartUpdated', function () {
    updateCartCount();
});

// Re-check for the cart-count element after a short delay (in case navbar loads slowly)
setTimeout(updateCartCount, 500);