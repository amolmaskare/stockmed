<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - StockMed</title>
  <link rel="stylesheet" type="text/css" href="../frontend/css/order.css">
  <link rel="stylesheet" type="text/css" href="../frontend/css/login.css">

</head>
<body>
  <div class="login-container">
    <h1>Login to StockMed</h1>
    <form id="login-form" action="connect.php" method="POST">
      <!-- <input type="text" name="username" id="username" placeholder="User Name" required /> -->
      <input type="email" name="email" id="email" placeholder="Email" required />
      <input type="password" name="password" id="password" placeholder="Password" required />
      <button type="submit" class="btn" name="submit">login Up</button>
    </form>
    <p>Don't have an account? <a href="signup.php">Sign Up</a></p>  
  </div>

  <!-- Medicine Icons -->
  <div class="medicine-icons">
    <img src="https://cdn-icons-png.flaticon.com/512/206/206853.png" style="top: 10%; left: 5%;" />
    <img src="https://cdn-icons-png.flaticon.com/512/206/206853.png" style="top: 20%; right: 10%;" />
    <img src="https://cdn-icons-png.flaticon.com/512/206/206853.png" style="bottom: 15%; left: 20%;" />
  </div>
</body>
</html>
