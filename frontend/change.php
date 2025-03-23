<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "medicine";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination logic
$records_per_page = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $records_per_page; // Offset for SQL query

// Fetch total number of records
$total_records_sql = "SELECT COUNT(*) AS total FROM medicine_ordered";
$total_records_result = $conn->query($total_records_sql);
$total_records = $total_records_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page); // Total number of pages

// Fetch orders for the current page
$sql = "SELECT 
            medicine_ordered.*, 
            users.username AS user_name 
        FROM 
            medicine_ordered 
        JOIN 
            users 
        ON 
            medicine_ordered.user_id = users.id
        LIMIT $records_per_page OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Medicine Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./css/admin.css">
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="http://localhost/StockMed/frontend/users.php" class="active"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="http://localhost/StockMed/frontend/change.php"><i class="fas fa-pills"></i> Medicine Orders</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="navbar">
            <h2>Medicine Orders</h2>
            <div class="search-bar">
                <input type="text" placeholder="Search orders...">
                <button><i class="fas fa-search"></i></button>
            </div>
            <button class="new-btn"><i class="fas fa-plus"></i> New Order</button>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>Product Name</th>
                        <th>Order Date</th>
                        <th>Total Price</th>
                        <th>Track Order</th>
                        <th>Payment Method</th>
                        <th>Shipping Address</th>
                        <th>Phone No</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>".$row['id']."</td>
                                    <td>".$row['user_name']."</td>
                                    <td>".$row['product_name']."</td>
                                    <td>".$row['order_date']."</td>
                                    <td>$".$row['total_price']."</td>
                                    <td>".$row['track_order']."</td>
                                    <td>".$row['payment_method']."</td>
                                    <td>".$row['shipping_address']."</td>
                                    <td>".$row['phone_no']."</td>
                                    <td class='actions'>
                                        <a href='view.php?id=".$row['id']."' class='view-btn'><i class='fas fa-eye'></i></a>
                                        <a href='edit.php?id=".$row['id']."' class='edit-btn'><i class='fas fa-edit'></i></a>
                                        <form method='POST' action='orderdelete.php' style='display: inline;' onsubmit='return confirmDelete()'>
                                            <input type='hidden' name='id' value='".$row['id']."'>
                                            <button type='submit' class='delete-btn'><i class='fas fa-trash'></i></button>
                                        </form>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='11'>No orders found</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>"><button><i class="fas fa-chevron-left"></i></button></a>
            <?php endif; ?>

            <span>Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>"><button><i class="fas fa-chevron-right"></i></button></a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this record?");
        }
    </script>

</body>
</html>