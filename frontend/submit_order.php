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
// print_r($_SESSION);exit();
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["success" => false, "message" => "User not logged in"]));
}

// Get the JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    die(json_encode(["success" => false, "message" => "Invalid data"]));
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
// Insert into medicine_ordered table

$medicineListIds = [];
$quantities = [];
$prices = [];

// error_log(print_r($cartItems, true));
// print_r($cartItems, true);
// exit();
foreach ($cartItems as $item) {
    $medicineListIds[] = $item['id']; // Add medicine_list_id to the array
    $quantities[] = $item['quantity']; // Add quantity to the array
    $prices[] = $item['price(₹)']; // Add quantity to the array
}

// Encode arrays as JSON
$medicineListIdsJson = json_encode($medicineListIds);
$quantitiesJson = json_encode($quantities);
$pricesJson = json_encode($prices);
if ($conn) {
    
        // $price = $item['price'];
        $sql = "INSERT INTO medicine_ordered (medicine_list_id, quantity, price, shipping_address, phone_no, payment_method, total_price,track_order, order_date,user_id) 
        VALUES ('$medicineListIdsJson', '$quantitiesJson',  '$pricesJson', '$address','$phone', '$paymentMethod', '$totalAmount','Order Successfully Placed', '$orderDate','$user_id')";
        // $sqlItem = "INSERT INTO medicine_ordered ( ) 
        //             VALUES ()";
        $conn->query($sql);
    

    echo json_encode(["success" => true, "message" => "Order placed successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
}

// Close connection
$conn->close();
?>
