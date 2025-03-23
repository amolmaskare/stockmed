<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medicine";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection error
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["success" => false, "message" => "User not logged in", "session" => $_SESSION]));
}

// Get the JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    die(json_encode(["success" => false, "message" => "Invalid data", "input" => file_get_contents("php://input")]));
}

// Extract data from JSON
$fullName = $data['fullName'];
$address = $data['address'];
$city = $data['city'];
$state = $data['state'];
$pincode = $data['pincode'];
$phone = $data['phone'];
$paymentMethod = $data['paymentMethod'];
$totalAmount = $data['totalAmount'];
$cartItems = $data['cartItems'];
$orderDate = date('Y-m-d H:i:s');
$user_id = $_SESSION['user_id'];

// Prepare arrays for medicine_list_id, quantities, and prices
$medicineListIds = [];
$quantities = [];
$prices = [];

// Prepare a comma-separated string for product names
$productNames = [];

foreach ($cartItems as $item) {
    $medicineListIds[] = $item['id']; // Add medicine_list_id to the array
    $productNames[] = $item['name']; // Add product name to the array
    $quantities[] = $item['quantity']; // Add quantity to the array
    $prices[] = $item['price(₹)']; // Add price to the array
}

// Convert arrays to JSON (for medicine_list_id, quantities, and prices)
$medicineListIdsJson = json_encode($medicineListIds);
$quantitiesJson = json_encode($quantities);
$pricesJson = json_encode($prices);

// Convert product names array to a comma-separated string
$productNamesString = implode(", ", $productNames);

// Insert into medicine_ordered table
if ($conn) {
    $sql = "INSERT INTO medicine_ordered (medicine_list_id, product_name, quantity, price, shipping_address, phone_no, payment_method, total_price, track_order, order_date, user_id) 
            VALUES ('$medicineListIdsJson', '$productNamesString', '$quantitiesJson', '$pricesJson', '$address', '$phone', '$paymentMethod', '$totalAmount', 'Order Successfully Placed', '$orderDate', '$user_id')";
    
    error_log("SQL Query: " . $sql); // Log the query for debugging

    if ($conn->query($sql)) {
        echo json_encode(["success" => true, "message" => "Order placed successfully"]);
    } else {
        error_log("SQL Error: " . $conn->error); // Log the error
        echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Database connection error"]);
}

// Close connection
$conn->close();
?>