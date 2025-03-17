<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>StockMed Products</title>
  <link rel="stylesheet" href="navbar.css">
  <link rel="stylesheet" href="./css/order.css" />
  <!-- Firebase App (the core Firebase SDK) -->
  <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-app-compat.js"></script>
  <!-- Firebase Firestore SDK -->
  <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-firestore-compat.js"></script>
  <!-- Modal Styles -->
<style>
  .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
  }

  .modal-content {
    background-color: white;
    margin: 15% auto;
    padding: 20px;
    border-radius: 8px;
    width: 300px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }

  .close-modal {
    float: right;
    font-size: 24px;
    cursor: pointer;
  }

  .modal-buttons {
    margin-top: 20px;
  }

  .modal-button {
    padding: 10px 20px;
    margin: 5px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  .modal-button:hover {
    background-color: #45a049;
  }
  header {
    position: sticky;
    top: 0;
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 1000;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  #user-initial {
    width: 40px;
    height: 40px;
    background-color: white;
    color: #4CAF50;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 20px;
    font-weight: bold;
  }
</style>
</head>
<body>
<header>
  <h1>StockMed Products</h1>
  <div id="user-cart-container">
    <!-- User Initials -->
    <div id="user-initial"></div>
    <div id="user-dropdown" class="dropdown">
    <button id="logout-button">Logout</button>
  </div>
<!-- </div> -->
    <!-- Cart Icon Container -->
    <div id="cart-container">
    <span id="cart-count">0</span>

      <img src="https://cdn-icons-png.flaticon.com/512/1170/1170678.png" id="cart-icon" alt="Cart Icon">
    </div>
  </div>
</header>
  
<!-- Navbar linking -->
<script>
      fetch("navbar.html")
          .then(response => response.text())
          .then(data => {
              document.getElementById("navbar-placeholder").innerHTML = data;
          })
          .catch(error => console.error("Error loading navbar:", error));
  </script>
   
  <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get current page URL
        let currentPage = window.location.pathname.split("/").pop();

        // Select all nav links
        let navLinks = document.querySelectorAll(".nav-link");

        // Loop through each link
        navLinks.forEach(link => {
            let linkPage = link.getAttribute("href");

            // Add 'active' class if link matches the current page
            if (currentPage === linkPage) {
                link.classList.add("active");
            } else {
                link.classList.remove("active");
            }
        });
    });
</script>
  <main>
    <section id="search-section">
      <input type="text" id="search-input" placeholder="Search for medicines..." />
      <button id="search-button">Search</button>
    </section>

    <section id="products-section">
      <!-- Products will be dynamically inserted here -->
    </section>
    <!-- Login/Signup Modal -->
<div id="login-modal" class="modal">
  <div class="modal-content">
    <span class="close-modal">&times;</span>
    <h2>Login or Sign Up</h2>
    <p>You need to log in or sign up to add items to your cart.</p>
    <div class="modal-buttons">
      <button id="login-button" class="modal-button">Login</button>
      <button id="signup-button" class="modal-button">Sign Up</button>
    </div>
  </div>
</div>
<!-- Medicine Icons -->
<div class="medicine-icons">
  <img src="https://cdn-icons-png.flaticon.com/512/206/206853.png" style="top: 10%; left: 5%;" />
  <img src="https://cdn-icons-png.flaticon.com/512/206/206853.png" style="top: 20%; right: 10%;" />
  <img src="https://cdn-icons-png.flaticon.com/512/206/206853.png" style="bottom: 15%; left: 20%;" />
</div>

  </main>

  <footer>
    <p>&copy; 2023 StockMed. All rights reserved.</p>
  </footer>

  <!-- Your custom JS -->
  <script src="./js/order.js"></script>
  <!-- use to access config data -->
  <script src="./js/config.js"></script>
<!-- <script src="order.js"></script> -->
<script src="./js/cart.js"></script>
<script>
  // Display user initial from localStorage
  const userInitial = localStorage.getItem('userInitial');
  if (userInitial) {
    document.getElementById('user-initial').textContent = userInitial;
  }

  // Toggle dropdown on click
  document.getElementById("user-initial").addEventListener("click", function () {
    const dropdown = document.getElementById("user-dropdown");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
  });

  // Logout functionality
  document.getElementById("logout-button").addEventListener("click", function () {
    // Clear session storage and local storage
    localStorage.removeItem("userInitial");
    localStorage.removeItem("user_id");
    sessionStorage.clear();

    // Redirect to login page
    window.location.href = "../backend/login.php";
  });

  // Hide dropdown when clicking outside
  document.addEventListener("click", function (event) {
    const dropdown = document.getElementById("user-dropdown");
    if (!document.getElementById("user-cart-container").contains(event.target)) {
      dropdown.style.display = "none";
    }
  });
</script>


</body>
</html>
