<?php
include 'db_connect.php'; // Ensure this file exists and is correctly set up

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST["username"]) ? $conn->real_escape_string($_POST["username"]) : '';
    $email = isset($_POST["email"]) ? $conn->real_escape_string($_POST["email"]) : '';
    $password = isset($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_DEFAULT) : '';

    if (!empty($username) && !empty($email) && !empty($password)) {
        $sql = "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            // ✅ Redirect to login.html after successful signup
            header("Location: ../login.html");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "All fields are required!";
    }
}

$conn->close();
?>
