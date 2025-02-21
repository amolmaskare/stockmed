<?php
set_time_limit(0);
ini_set('memory_limit', '512M');

// Database connection
$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$database = "medicine"; // ✅ Correct database name

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Load JSON file
$jsonFile = 'medicine_all_list.json';
$jsonData = file_get_contents($jsonFile);
$medicines = json_decode($jsonData, true);

if (!empty($medicines)) {
    $batchSize = 1000;
    $batch = [];

    foreach ($medicines as $medicine) {
        $id = intval($medicine['id']);
        $name = $conn->real_escape_string($medicine['name']);
        $url = isset($medicine['url']) ? $conn->real_escape_string($medicine['url']) : ''; // ✅ Fix for missing "url"
        $price = floatval($medicine["price(₹)"]);
        $is_discontinued = ($medicine['Is_discontinued'] === "TRUE") ? 1 : 0;
        $manufacturer_name = $conn->real_escape_string($medicine['manufacturer_name']);
        $type = $conn->real_escape_string($medicine['type']);
        $pack_size_label = $conn->real_escape_string($medicine['pack_size_label']);
        $short_composition1 = $conn->real_escape_string($medicine['short_composition1']);
        $short_composition2 = $conn->real_escape_string($medicine['short_composition2']);

        $batch[] = "('$id', '$name', '$url', '$price', '$is_discontinued', '$manufacturer_name', '$type', '$pack_size_label', '$short_composition1', '$short_composition2')";

        if (count($batch) >= $batchSize) {
            $sql = "INSERT IGNORE INTO medicine_list (id, name, url, price, is_discontinued, manufacturer_name, type, pack_size_label, short_composition1, short_composition2) VALUES " . implode(',', $batch);
            $conn->query($sql);
            $batch = [];
        }
    }

    if (!empty($batch)) {
        $sql = "INSERT IGNORE INTO medicine_list (id, name, url, price, is_discontinued, manufacturer_name, type, pack_size_label, short_composition1, short_composition2) VALUES " . implode(',', $batch);
        $conn->query($sql);
    }

    echo "Data uploaded successfully!";
} else {
    echo "No data found in JSON file.";
}

$conn->close();
?>
