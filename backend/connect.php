<?php
session_start();
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'medicine');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle login and signup logic
if (isset($_POST['submit'])) {
    // Check if it's login or signup
    if (isset($_POST['username'])) { // This means it's a signup
        // Collect and sanitize form input for signup
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Prepare and execute SQL query to insert data into the database (signup)
        $qry = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        
        if (mysqli_query($conn, $qry)) {
            echo "Data uploaded successfully";
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            header("Location: ../frontend/index.html"); // Redirect after success
            exit(); // Ensure no further code runs after redirect
        } else {
            echo "Error: " . $qry . "<br>" . mysqli_error($conn);
        }
    } else { // If 'username' is not set, it must be a login attempt
        // Collect and sanitize form input for login
        
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Prepare and execute SQL query to check if the user exists for login
        $qry = "SELECT * FROM users WHERE email='$email' AND password='$password'";

        $result = mysqli_query($conn, $qry);
        if (mysqli_num_rows($result) > 0) {
            // Login successful, redirect to the homepage
            $user = mysqli_fetch_assoc($result);
            // echo $user;
            // print_r($user['username']);
            // Store username in session
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            // print_r($_SESSION['username']);
            
    
            // // Redirect to the homepage
            header("Location: ../frontend/index.html");
            exit();
        } else {
            // If no match is found
            echo "Invalid email or password.";
        }
    }
}
if (isset($_POST['admin'])) {
    // Hardcoded admin credentials
    $adminEmail = 'admin@test.com';
    $adminPassword = 'admin@1234';

    // Collect form input
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if credentials match admin
    if ($email === $adminEmail && $password === $adminPassword) {
        // Admin login successful
        $_SESSION['isAdmin'] = true;
        $_SESSION['email'] = $email;
        header("Location: ../frontend/change.html");
        exit();
    } else {
        // Invalid credentials
        echo "Invalid email or password.";
    }
}
// Close connection
mysqli_close($conn);
?>
