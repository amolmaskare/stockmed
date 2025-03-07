<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medicine";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch medicines from the database
$query = "SELECT id, name, price, url FROM medicine_list";
$stmt = $pdo->prepare($query);
$stmt->execute();
$medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return JSON response
echo json_encode($medicines);
?>
