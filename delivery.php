<?php
session_start();
include("config.php");

// Handle order status update and add remaining amount to wallet balance
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = mysqli_real_escape_string($con, $_POST['order_id']);
    $new_status = mysqli_real_escape_string($con, $_POST['status']);
    
    // Check if remaining_amount is set
    $remaining_amount = isset($_POST['remaining_amount']) ? mysqli_real_escape_string($con, $_POST['remaining_amount']) : '';

    // Update order status
    $update_sql = "UPDATE orders3 SET status = '$new_status' WHERE order_id = $order_id";
    if (mysqli_query($con, $update_sql)) {
        $success = "Order status updated to $new_status successfully!";
    } else {
        $error = "Error updating order status: " . mysqli_error($con);
    }

    // Add remaining amount to user's wallet balance
    if ($remaining_amount !== '' && is_numeric($remaining_amount)) {
        // Fetch user_id associated with the order
        $user_sql = "SELECT user_id FROM orders3 WHERE order_id = $order_id";
        $user_result = mysqli_query($con, $user_sql);
        if ($user_result) {
            $user_row = mysqli_fetch_assoc($user_result);
            $user_id = $user_row['user_id'];

            $wallet_sql = "UPDATE users SET wallet_balance = wallet_balance + $remaining_amount WHERE user_id = $user_id";
            if (mysqli_query($con, $wallet_sql)) {
                $success .= " Remaining amount added to user's wallet balance!";
            } else {
                $error .= " Error updating wallet balance: " . mysqli_error($con);
            }
        }
    }
}

// Fetch all orders
$sql = "SELECT o.order_id, u.name AS customer_name, a.city, a.street, a.building, a.floor, a.apartment, u.phone, o.total_amount, o.status
        FROM orders3 o
        JOIN users u ON o.user_id = u.user_id
        JOIN addresses a ON o.address_id = a.address_id";
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Dashboard</title>
    <link rel="stylesheet" href="delivery.css">
</head>
<body>
    <div class="container">
        <h1>Delivery Staff Dashboard</h1>
        <?php if (isset($success)) { echo "<p class='success'>$success</p>"; } ?>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                    <th>Add Remaining</th>  <!-- New column header for the remaining amount -->
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['city'] . ', ' . $row['street'] . ', ' . $row['building'] . ', ' . $row['floor'] . ', ' . $row['apartment']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_amount']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td>
                                <form action="delivery.php" method="post" style="text-align: center;">
                                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                    <button type="submit" name="status" value="completed" class="action-button">Mark as Delivered</button>
                                    <button type="submit" name="status" value="canceled" class="action-button">Cancel</button>
                                    <button type="submit" name="status" value="failed" class="action-button">Mark as Failed</button>
                                    <button type="submit" name="status" value="pending" class="action-button">Pending</button>
                                </form>
                            </td>
                            <td>
                                <form action="delivery.php" method="post" style="text-align: center;">
                                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                    <input type="text" name="remaining_amount" placeholder="Add Remaining" class="remaining-amount">
                                    <button type="submit" name="status" value="<?php echo htmlspecialchars($row['status']); ?>" class="action-button">Add</button>
                                </form>
                            </td>
                        </tr>
                    <?php }
                } else {
                    echo "<tr><td colspan='8'>No orders found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
