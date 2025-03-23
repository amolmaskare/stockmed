<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "medicine";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$id = $_POST['id'];
$user_id = $_POST['user_id'];
$product_name = $_POST['product_name'];
$order_date = $_POST['order_date'];
$total_price = $_POST['total_price'];
$track_order = $_POST['track_order'];
$payment_method = $_POST['payment_method'];
$shipping_address = $_POST['shipping_address'];
$phone_no = $_POST['phone_no'];

// Update the order
$sql = "UPDATE medicine_ordered 
        SET user_id = '$user_id', 
            product_name = '$product_name', 
            order_date = '$order_date', 
            total_price = '$total_price', 
            track_order = '$track_order', 
            payment_method = '$payment_method', 
            shipping_address = '$shipping_address', 
            phone_no = '$phone_no' 
        WHERE id = $id";

// Execute the query
if ($conn->query($sql) === TRUE) {
    // Redirect back to the admin panel
    header("Location: change.php");
    exit(); // Ensure no further code is executed after the redirect
} else {
    die("Error updating order: " . $conn->error);
}

// Close the connection
$conn->close();
?>
