<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "medicine";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the ID of the record to delete
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Delete the record
    $sql = "DELETE FROM medicine_ordered WHERE id = $id";
    if ($conn->query($sql)) {
        echo "<script>alert('Record deleted successfully!');</script>";
        // Redirect back to the orders page
        echo "<script>window.location.href = 'change.php';</script>";
    } else {
        echo "<script>alert('Error deleting record: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>