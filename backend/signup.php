<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
include 'db_connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debug: Print form data
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Get form data safely
    $username = isset($_POST["username"]) ? $conn->real_escape_string($_POST["username"]) : '';
    $email = isset($_POST["email"]) ? $conn->real_escape_string($_POST["email"]) : '';
    $password = isset($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_BCRYPT) : '';

    // Check if fields are empty
    if (!empty($username) && !empty($email) && !empty($password)) {
        // Insert user into database
        $sql = "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$password')";
        
        // Debug: Print SQL query
        echo "SQL Query: " . $sql;

        if ($conn->query($sql) === TRUE) {
            echo "✅ Signup successful!";
            header("Location: ../login.html"); // Redirect to login page
            exit();
        } else {
            echo "❌ Error: " . $conn->error; // Show SQL error message
        }
    } else {
        echo "❌ All fields are required!";
    }
}

// Close the database connection
$conn->close();
?>