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

// Fetch the order details
$sql = "SELECT * FROM medicine_ordered WHERE id = $order_id";
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
    <title>Edit Order - Admin Panel</title>
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

        /* Edit Form */
        .edit-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .edit-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #2c3e50;
        }

        .edit-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .edit-form input:focus {
            border-color: #2c3e50;
            outline: none;
        }

        .edit-form button {
            background-color: #2c3e50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .edit-form button:hover {
            background-color: #34495e;
        }
        /* Custom Dropdown Styling */
.custom-select {
    position: relative;
    width: 100%;
    margin-bottom: 15px;
}

.custom-select select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    background-color: #fff;
    appearance: none; /* Remove default arrow */
    -webkit-appearance: none; /* Remove default arrow for Safari */
    -moz-appearance: none; /* Remove default arrow for Firefox */
    cursor: pointer;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.custom-select select:focus {
    border-color: #2c3e50;
    outline: none;
    box-shadow: 0 0 5px rgba(44, 62, 80, 0.3);
}

/* Custom Arrow */
/* Custom Dropdown Styling */
.custom-select {
    position: relative;
    width: 100%;
    margin-bottom: 20px;
}

.custom-select label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #2c3e50;
}

.custom-select select {
    width: 100%;
    padding: 12px;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 16px;
    color: #333;
    background-color: #fff;
    appearance: none; /* Remove default arrow */
    -webkit-appearance: none; /* Remove default arrow for Safari */
    -moz-appearance: none; /* Remove default arrow for Firefox */
    cursor: pointer;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.custom-select select:focus {
    border-color: #2c3e50;
    outline: none;
    box-shadow: 0 0 8px rgba(44, 62, 80, 0.2);
}

/* Custom Arrow */
.custom-select::after {
    content: "▼";
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    pointer-events: none;
    color: #2c3e50;
    font-size: 12px;
    transition: color 0.3s ease;
}

.custom-select:hover::after {
    color: #34495e;
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
            <h2>Edit Order</h2>
            <a href="change.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
        </div>

        <div class="edit-form">
            <form action="update_order.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                <label for="user_id">User ID:</label>
                <input type="text" id="user_id" name="user_id" value="<?php echo $order['user_id']; ?>" required>

                <label for="product_name">Product Name:</label>
                <input type="text" id="product_name" name="product_name" value="<?php echo $order['product_name']; ?>" required>

                <label for="order_date">Order Date:</label>
                <input type="date" id="order_date" name="order_date" value="<?php echo $order['order_date']; ?>" required>

                <label for="total_price">Total Price:</label>
                <input type="number" id="total_price" name="total_price" value="<?php echo $order['total_price']; ?>" required>

                <div class="custom-select">
    <label for="track_order">Track Order:</label>
    <select id="track_order" name="track_order" required>
        <option value="Order Successfully Placed" <?php echo ($order['track_order'] == 'Order Successfully Placed') ? 'selected' : ''; ?>>Order Successfully Placed</option>
        <option value="Order Dispatched" <?php echo ($order['track_order'] == 'Order Dispatched') ? 'selected' : ''; ?>>Order Dispatched</option>
        <option value="Order Received Near House" <?php echo ($order['track_order'] == 'Order Received Near House') ? 'selected' : ''; ?>>Order Received Near House</option>
        <option value="Order Delivered Successfully" <?php echo ($order['track_order'] == 'Order Delivered Successfully') ? 'selected' : ''; ?>>Order Delivered Successfully</option>
    </select>
</div>

                <label for="payment_method">Payment Method:</label>
                <input type="text" id="payment_method" name="payment_method" value="<?php echo $order['payment_method']; ?>" required>

                <label for="shipping_address">Shipping Address:</label>
                <input type="text" id="shipping_address" name="shipping_address" value="<?php echo $order['shipping_address']; ?>" required>

                <label for="phone_no">Phone No:</label>
                <input type="text" id="phone_no" name="phone_no" value="<?php echo $order['phone_no']; ?>" required>

                <button type="submit" class="save-btn">Save Changes</button>
            </form>
        </div>
    </div>

</body>
</html>