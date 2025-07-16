<?php
include("config.php");
session_start(); // Start the session

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : 0;

// Fetch order details
$order_sql = "SELECT * FROM orders3 WHERE user_id = '$user_id' AND order_id = '$order_id'";
$order_result = mysqli_query($con, $order_sql);
$order = mysqli_fetch_assoc($order_result);

// Fetch order items
$order_items_sql = "SELECT order_items1.product_id, order_items1.quantity, products.name, products.price 
                    FROM order_items1 
                    JOIN products ON order_items1.product_id = products.product_id 
                    WHERE order_items1.order_id = '$order_id'";
$order_items_result = mysqli_query($con, $order_items_sql);

// Fetch cart items for stock update
$cart_sql = "SELECT product_id, count FROM cart WHERE user_id = '$user_id'";
$cart_result = mysqli_query($con, $cart_sql);

// Update stock quantity for each product ordered
while ($row = mysqli_fetch_assoc($cart_result)) {
    $product_id = $row['product_id'];
    $quantity = $row['count'];
    
    $update_stock_sql = "UPDATE products SET stock_quantity = stock_quantity - $quantity WHERE product_id = '$product_id'";
    mysqli_query($con, $update_stock_sql);
}

// Clear cart for the user
$clear_cart_sql = "DELETE FROM cart WHERE user_id = '$user_id'";
mysqli_query($con, $clear_cart_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - CairoCravings</title>
    <link rel="stylesheet" href="thankyou.css">
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Thank You for Your Order!</h1>
        </div>
    </header>

    <div class="order-summary">
        <h2>Order Details</h2>
        <p>Order ID: <?php echo htmlspecialchars($order['order_id'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Order Date: <?php echo htmlspecialchars($order['order_date'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Total Amount: $<?php echo htmlspecialchars($order['total_amount'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Status: <?php echo htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8'); ?></p>

        <h2>Items Ordered</h2>
        <div class="product-grid">
            <?php
            while ($row = mysqli_fetch_assoc($order_items_result)) {
                $productName = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
                $productPrice = htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8');
                $quantity = htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8');
                $itemTotalPrice = $productPrice * $quantity;

                echo "<div class='product-item'>";
                echo "<h3>$productName</h3>";
                echo "<p>Price: $$productPrice</p>";
                echo "<p>Quantity: x$quantity</p>";
                echo "<p>Total: $$itemTotalPrice</p>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <footer>
        <p>© 2024 CairoCraving. All rights reserved.</p>
    </footer>
</body>
</html>
