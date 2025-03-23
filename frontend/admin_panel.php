<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "medicine";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM medicine_ordered";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="./css/admin.css">
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Users</a></li>
            <li><a href="#">Images</a></li>
            <li><a href="#">medicine_ordered</a></li>
            <li><a href="#">Categories</a></li>
            <li><a href="#">Companies</a></li>
            <li><a href="#">Reviews</a></li>
            <li><a href="#">Contacts</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="navbar">
            <h2>medicine_ordered</h2>
            <button class="new-btn">New</button>
        </div>

        <table>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Original Price</th>
                <th>Offer Price</th>
                <th>Discount</th>
                <th>Description</th>
                <th>Category</th>
                <th>Company</th>
                <th>Created</th>
                <th>Modified</th>
                <th>Actions</th>
            </tr>

            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>".$row['id']."</td>
                            <td>".$row['name']."</td>
                            <td>".$row['original_price']."</td>
                            <td>".$row['offer_price']."</td>
                            <td>".$row['discount']."</td>
                            <td>".$row['description']."</td>
                            <td>".$row['category']."</td>
                            <td>".$row['company']."</td>
                            <td>".$row['created_at']."</td>
                            <td>".$row['modified_at']."</td>
                            <td>
                                <button class='view-btn'>View</button>
                                <button class='edit-btn'>Edit</button>
                                <button class='delete-btn'>Delete</button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='11'>No medicine_ordered Found</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>

</body>
</html>
