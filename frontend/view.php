<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "medicine";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the order ID from the query parameter
$order_id = $_GET['id'];

// Fetch the order details with user name
$sql = "SELECT 
            medicine_ordered.*, 
            users.username AS user_name 
        FROM 
            medicine_ordered 
        JOIN 
            users 
        ON 
            medicine_ordered.user_id = users.id 
        WHERE 
            medicine_ordered.id = $order_id";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    die("Order not found.");
}

$order = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order - Admin Panel</title>
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

        /* Order Details Section */
        .order-details {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .order-details h3 {
            font-size: 22px;
            color: #2c3e50;
            margin-bottom: 20px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
        }

        .order-details p {
            font-size: 16px;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .order-details p strong {
            color: #2c3e50;
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }

        /* Status Highlight */
        .order-details .status {
            padding: 8px 12px;
            border-radius: 5px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
        }

        .order-details .status.placed {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .order-details .status.dispatched {
            background-color: #fff3e0;
            color: #f57c00;
        }

        .order-details .status.received {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .order-details .status.delivered {
            background-color: #f1f8e9;
            color: #388e3c;
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
            <h2>View Order Details</h2>
            <a href="change.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
        </div>

        <div class="order-details">
            <h3>Order ID: <?php echo $order['id']; ?></h3>
            <p><strong>User Name:</strong> <?php echo $order['user_name']; ?></p> <!-- Display user name -->
            <p><strong>Product Name:</strong> <?php echo $order['product_name']; ?></p>
            <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
            <p><strong>Total Price:</strong> $<?php echo $order['total_price']; ?></p>
            <p><strong>Track Order:</strong> 
                <span class="status <?php echo strtolower(str_replace(' ', '-', $order['track_order'])); ?>">
                    <?php echo $order['track_order']; ?>
                </span>
            </p>
            <p><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
            <p><strong>Shipping Address:</strong> <?php echo $order['shipping_address']; ?></p>
            <p><strong>Phone No:</strong> <?php echo $order['phone_no']; ?></p>
        </div>
    </div>

</body>
</html>