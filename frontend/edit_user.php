<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Form submitted!<br>";

    // Debug: Print POST data
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Update the user details
    $update_sql = "UPDATE users SET 
                    username = '$username', 
                    email = '$email', 
                    password = '$password', 
                    modified = NOW() 
                   WHERE id = $user_id";

    // Debug: Print SQL query
    echo "SQL Query: " . $update_sql . "<br>";

    if ($conn->query($update_sql)) {
        echo "<script>alert('User updated successfully!');</script>";
        // Redirect to users.php after successful update
        header("Location: users.php");
        exit();
    } else {
        echo "<script>alert('Error updating user: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Admin Panel</title>
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

        /* Edit User Form */
        .edit-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .edit-form h3 {
            font-size: 22px;
            color: #2c3e50;
            margin-bottom: 20px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
        }

        .edit-form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .edit-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .edit-form button {
            background-color: #2c3e50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .edit-form button:hover {
            background-color: #34495e;
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
            <h2>Edit User</h2>
            <a href="users.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
        </div>

        <div class="edit-form">
            <h3>Edit User ID: <?php echo $user['id']; ?></h3>
            <form method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

                <label for="password">Password:</label>
                <input type="text" id="password" name="password" value="<?php echo $user['password']; ?>" required>

                <button type="submit"  name="update_user">Update User</button>
            </form>
        </div>
    </div>

</body>
</html>