<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medicine";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$message = $_POST['message'];

// Insert data into the database
$sql = "INSERT INTO contactus (name, email, phone, message) VALUES ('$name', '$email', '$phone', '$message')";

if ($conn->query($sql)) {
    echo "<script>alert('Message Send successfully!');</script>";
    // Redirect back to the orders page
    echo "<script>window.location.href = 'order.php';</script>";
} else {
    echo "<script>alert('Error Sending record: " . $conn->error . "');</script>";
}

// Close connection
$conn->close();
?>