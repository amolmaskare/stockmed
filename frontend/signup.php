<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - StockMed</title>
  <link rel="stylesheet" href="./css/order.css">
  <link rel="stylesheet" href="./css/login.css">
  <style>
    /* Same styles as login.html */
  </style>
</head>
<body>
  <div class="login-container">
    <h1>Sign Up for StockMed</h1>
    <!-- <form id="signup-form">
      <input type="text" id="name" placeholder="Full Name" required />
      <input type="email" id="email" placeholder="Email" required />
      <input type="password" id="password" placeholder="Password" required />
      <button type="submit">Sign Up</button>
    </form> -->
    <form id="signup-form" action="/backend/signup.php" method="POST">
      <input type="text" name="username" id="name" placeholder="Full Name" required />
      <input type="email" name="email" id="email" placeholder="Email" required />
      <input type="password" name="password" id="password" placeholder="Password" required />
      <button type="submit">Sign Up</button>
    </form>
    

    <p>Already have an account? <a href="login.html">Login</a></p>
  </div>

  <!-- Medicine Icons -->
  <div class="medicine-icons">
    <img src="https://cdn-icons-png.flaticon.com/512/206/206853.png" style="top: 10%; left: 5%;" />
    <img src="https://cdn-icons-png.flaticon.com/512/206/206853.png" style="top: 20%; right: 10%;" />
    <img src="https://cdn-icons-png.flaticon.com/512/206/206853.png" style="bottom: 15%; left: 20%;" />
  </div>

  <script>
    document.getElementById('signup-form').addEventListener('submit', function (e) {
      e.preventDefault();
      const name = document.getElementById('name').value;
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;

      // Simulate signup logic
      localStorage.setItem('isLoggedIn', 'true');
      localStorage.setItem('userInitial', name[0].toUpperCase());
      window.location.href = 'order.html';
    });
  </script>
</body>
</html>
