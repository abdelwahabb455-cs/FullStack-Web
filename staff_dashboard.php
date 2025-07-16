<?php
session_start();
include("config.php");

// Check if the staff_id is stored in the session
if (!isset($_SESSION['staff_id'])) {
    header("Location: interface.php");
    exit();
}

$staff_id = $_SESSION['staff_id'];

// Fetch staff details from the database
$sql = "SELECT name, email, role FROM staff WHERE staff_id = $staff_id";
$result = mysqli_query($con, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $staff = mysqli_fetch_assoc($result);
} else {
    echo "Staff member not found.";
    exit();
}

// Fetch recent orders
$sql = "SELECT o.order_id, u.name AS customer_name, o.total_amount, o.status
        FROM orders3 o
        JOIN users u ON o.user_id = u.user_id
        ORDER BY o.order_date DESC
        LIMIT 10";
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Cairo Craving</title>
    <link rel="stylesheet" href="staff_dashboard.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($staff['name']); ?></h1>
        <p>Email: <?php echo htmlspecialchars($staff['email']); ?></p>
        <p>Role: <?php echo htmlspecialchars($staff['role']); ?></p>
        
        <div class="actions">
            <a href="addproduct.php" class="action-button">Add Products</a>
            <a href="updateproduct.php" class="action-button">Update Products</a>
            <a href="delete.php" class="action-button">Delete Products</a>
        </div>
        
        <h2>Recent Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_amount']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td>
                                <a href="view_order.php?order_id=<?php echo $row['order_id']; ?>" class="action-button">View</a>
                            </td>
                        </tr>
                    <?php }
                } else {
                    echo "<tr><td colspan='5'>No recent orders found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <a href="logout.php" class="logout-button">Logout</a>
    </div>
</body>
</html>
