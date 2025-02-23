<?php
$servername = "localhost";
$username = "root";  // Default for XAMPP
$password = "";      // Default is empty for XAMPP
$dbname = "medicine"; // Change this to your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
