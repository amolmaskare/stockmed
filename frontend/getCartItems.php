<?php
// getCartItems.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medicine";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch cart items
    $query = "SELECT id, name, price, quantity FROM medicine_list"; // Adjust the query based on your table structure
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return cart items as JSON
    header('Content-Type: application/json');
    echo json_encode($cartItems);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>