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

if (isset($input['id']) && isset($input['url'])) {
    $id = $input['id'];
    $url = $input['url'];

    // Check if the URL already exists for the given product ID
    $checkQuery = "SELECT url FROM medicine_list WHERE id = :id";
    $stmt = $pdo->prepare($checkQuery);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // If URL is already set for this product
        $existingUrl = $result['url'];

        // Only update if the URL is empty or not set
        if (empty($existingUrl)) {
            // Update the URL in the database
            $updateQuery = "UPDATE medicine_list SET url = :url WHERE id = :id";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->bindParam(':url', $url);
            $updateStmt->bindParam(':id', $id);
            $updateStmt->execute();

            echo json_encode(['message' => 'URL updated successfully']);
        } else {
            // URL already exists, no need to update
            echo json_encode(['message' => 'URL already exists, no update needed']);
        }
    } else {
        // Product not found
        echo json_encode(['error' => 'Product not found']);
    }
} else {
    // Missing id or url
    echo json_encode(['error' => 'Invalid input. ID and URL are required.']);
}

?>
