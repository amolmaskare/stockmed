<?php
// Assuming you're using PDO for database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medicine";

// Create connection
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get the JSON input from the request
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['id'])) {
    $id = $input['id'];

    // Check if the URL already exists for the given product ID
    $checkQuery = "SELECT url FROM medicine_list WHERE id = :id";
    $stmt = $pdo->prepare($checkQuery);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // If URL is already set for this product
        $existingUrl = $result['url'];

        if (!empty($existingUrl)) {
            // URL exists, return it
            echo json_encode(['message' => 'url exists', 'url' => $existingUrl]);
        } else {
            // URL is empty, indicate that it needs to be added
            echo json_encode(['message' => 'add url']);
        }
    } else {
        // Product not found
        echo json_encode(['error' => 'Product not found']);
    }
} else {
    // Missing id
    echo json_encode(['error' => 'Invalid input. ID is required.']);
}
?>