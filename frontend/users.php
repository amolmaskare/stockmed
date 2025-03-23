<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "medicine";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete action
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $delete_sql = "DELETE FROM users WHERE id = $user_id";
    if ($conn->query($delete_sql)) {
        echo "<script>alert('User deleted successfully!');</script>";
        // Refresh the page to reflect changes
        echo "<script>window.location.href = 'users.php';</script>";
    } else {
        echo "<script>alert('Error deleting user: " . $conn->error . "');</script>";
    }
}

// Fetch all users from the users table
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./css/admin.css">
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

        /* Table Styling */
        .table-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #2c3e50;
            color: #fff;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .actions a {
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }

        .actions .view-btn {
            background-color: #3498db;
        }

        .actions .edit-btn {
            background-color: #f39c12;
        }

        .actions .delete-btn {
            background-color: #e74c3c;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .actions .delete-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="http://localhost/StockMed/frontend/users.php" class="active"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="http://localhost/StockMed/frontend/change.php"><i class="fas fa-pills"></i> Medicine Orders</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="navbar">
            <h2>Users</h2>
            <div class="search-bar">
                <input type="text" placeholder="Search users...">
                <button><i class="fas fa-search"></i></button>
            </div>
            <button class="new-btn"><i class="fas fa-plus"></i> New User</button>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Created</th>
                        <th>Modified</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>".$row['id']."</td>
                                    <td>".$row['username']."</td>
                                    <td>".$row['email']."</td>
                                    <td>".$row['password']."</td>
                                    <td>".$row['created']."</td>
                                    <td>".$row['modified']."</td>
                                    <td class='actions'>
                                        <a href='view_user.php?id=".$row['id']."' class='view-btn'><i class='fas fa-eye'></i></a>
                                        <a href='edit_user.php?id=".$row['id']."' class='edit-btn'><i class='fas fa-edit'></i></a>
                                        <form method='POST' style='display: inline;' onsubmit='return confirmDelete()'>
                                            <input type='hidden' name='user_id' value='".$row['id']."'>
                                            <button type='submit' name='delete_user' class='delete-btn'><i class='fas fa-trash'></i></button>
                                        </form>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No users found</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this user?");
        }
    </script>

</body>
</html>