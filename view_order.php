<?php
session_start();
include("config.php");

// Check if order_id is provided
if (!isset($_GET['order_id'])) {
    echo "Order ID is missing.";
    exit;
}

$order_id = mysqli_real_escape_string($con, $_GET['order_id']);

// Fetch order details
$sql = "SELECT o.order_id, o.order_date, o.total_amount, o.status, u.name AS customer_name, u.email, u.phone, a.city, a.street, a.building, a.floor, a.apartment, o.payment_id
        FROM orders3 o
        JOIN users u ON o.user_id = u.user_id
        JOIN addresses a ON o.address_id = a.address_id
        WHERE o.order_id = $order_id";
$result = mysqli_query($con, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $order = mysqli_fetch_assoc($result);
} else {
    echo "Order not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Cairo Craving</title>
    <link rel="stylesheet" href="view_order.css">
</head>
<body>
    <div class="container">
        <h1>Order Details</h1>
        <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
        <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
        <p><strong>Total Amount:</strong> <?php echo htmlspecialchars($order['total_amount']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
        <h2>Customer Information</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
        <h2>Shipping Address</h2>
        <p><?php echo htmlspecialchars($order['city'] . ', ' . $order['street'] . ', ' . $order['building'] . ', ' . $order['floor'] . ', ' . $order['apartment']); ?></p>
        <h2>Payment Information</h2>
        <p><strong>Payment ID:</strong> <?php echo htmlspecialchars($order['payment_id']); ?></p>
        <a href="staff_dashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>
