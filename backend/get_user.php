<?php
session_start();
header('Content-Type: application/json');

$response = [
    'username' => $_SESSION['username'] ?? null,
    'user_id' => $_SESSION['user_id'] ?? null // Store user ID or another identifier
];
echo json_encode($response);
?>
