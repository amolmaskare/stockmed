<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "medicine";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user ID from the query parameter
$user_id = $_GET['id'];

// Fetch the user details
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f6;
            color: #333;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #2c3e50;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
        }

        .sidebar h2 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li a:hover {
            background-color: #34495e;
        }

        .sidebar ul li a i {
            margin-right: 10px;
        }

        .sidebar ul li a.active {
            background-color: #34495e;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .navbar h2 {
            font-size: 24px;
            color: #2c3e50;
        }

        .navbar .back-btn {
            background-color: #2c3e50;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .navbar .back-btn:hover {
            background-color: #34495e;
        }

        /* User Details Section */
        .user-details {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .user-details h3 {
            font-size: 22px;
            color: #2c3e50;
            margin-bottom: 20px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
        }

        .user-details p {
            font-size: 16px;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .user-details p strong {
            color: #2c3e50;
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
        <li><a href="http://localhost/StockMed/frontend/change.php"><i class="fas fa-tachometer-alt"></i> Medicine Orders</a></li>
        <li><a href="http://localhost/StockMed/frontend/users.php" class="active"><i class="fas fa-users"></i> Users</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="navbar">
            <h2>View User Details</h2>
            <a href="user.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
        </div>

        <div class="user-details">
            <h3>User ID: <?php echo $user['id']; ?></h3>
            <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <p><strong>Password:</strong> <?php echo $user['password']; ?></p>
            <p><strong>Created:</strong> <?php echo $user['created']; ?></p>
            <p><strong>Modified:</strong> <?php echo $user['modified']; ?></p>
        </div>
    </div>

</body>
</html>