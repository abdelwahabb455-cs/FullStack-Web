<?php
include("config.php");
session_start(); // Start the session

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;

// Fetch address for the user
$address_sql = "SELECT * FROM addresses WHERE user_id = '$user_id'";
$address_result = mysqli_query($con, $address_sql);
$address = mysqli_fetch_assoc($address_result);

// Fetch cart items for the user
$cart_sql = "SELECT cart.product_id, cart.count, products.name, products.price, products.image_url FROM cart 
             JOIN products ON cart.product_id = products.product_id 
             WHERE cart.user_id = '$user_id'";
			 $cart1_sql = "SELECT cart.product_id, cart.count, products.name, products.price, products.image_url FROM cart 
             JOIN products ON cart.product_id = products.product_id 
             WHERE cart.user_id = '$user_id'";
			 $cart2_sql = "SELECT cart.product_id, cart.count, products.name, products.price, products.image_url FROM cart 
             JOIN products ON cart.product_id = products.product_id 
             WHERE cart.user_id = '$user_id'";
$cart_result = mysqli_query($con, $cart_sql);
$cart1_result = mysqli_query($con, $cart1_sql);
$cart2_result = mysqli_query($con, $cart2_sql);

$totalPrice = 0; // Initialize total price

// Calculate total price while fetching cart items
while ($row = mysqli_fetch_assoc($cart1_result)) {
    $productPrice = htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8');
    $count = htmlspecialchars($row['count'], ENT_QUOTES, 'UTF-8');
    $totalPrice += $productPrice * $count; // Add to total price
}

// Handle form submission


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'];
    $credit_card_number = $_POST['credit_card_number'] ?? null;
    $credit_card_expiry = $_POST['credit_card_expiry'] ?? null;
    $credit_card_cvc = $_POST['credit_card_cvc'] ?? null;

    // Insert payment into the database
    $payment_sql = "INSERT INTO payments (user_id, payment_method, amount, credit_card_number, credit_card_expiry, credit_card_cvc) 
                    VALUES ('$user_id', '$payment_method', '$totalPrice', '$credit_card_number', '$credit_card_expiry', '$credit_card_cvc')";
    mysqli_query($con, $payment_sql);

    // Fetch the last inserted payment_id for this user
    $payment_id = mysqli_insert_id($con);

    // Fetch the address_id for this user
$address_sql = "SELECT address_id FROM addresses WHERE user_id = '$user_id' LIMIT 1";
$address_result = mysqli_query($con, $address_sql);
$address_row = mysqli_fetch_assoc($address_result);
$address_id = $address_row['address_id'] ?? null;

if ($address_id) {
    // Insert order into the database
    $order_date = date('Y-m-d H:i:s'); // Current timestamp
    $order_status = "pending"; // Enum value
    $order_sql = "INSERT INTO orders3 (user_id, payment_id, order_date, total_amount, status, address_id) 
                  VALUES ('$user_id', '$payment_id', '$order_date', '$totalPrice', '$order_status', '$address_id')";
    mysqli_query($con, $order_sql);
   $order_id = mysqli_insert_id($con);

        // Insert each product into the order_items table
        
        while ($row = mysqli_fetch_assoc($cart2_result)) {
            $product_id = htmlspecialchars($row['product_id'], ENT_QUOTES, 'UTF-8');
            $quantity = htmlspecialchars($row['count'], ENT_QUOTES, 'UTF-8');
            $item_price = htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8');

            $order_item_sql = "INSERT INTO order_items1 (order_id, product_id, quantity, item_price) 
                               VALUES ('$order_id', '$product_id', '$quantity', '$item_price')";
            mysqli_query($con, $order_item_sql);
        }
    // Redirect to a confirmation page (e.g., thank you page)
   header("Location: thankyou.php?user_id=$user_id&order_id=$order_id");
exit();
} else {
    echo "Error: Address not found for this user.";
}
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - CairoCravings</title>
    <link rel="stylesheet" href="menuu.css">
    <script>
        function togglePaymentMethod() {
            const creditCardInfo = document.getElementById('credit-card-info');
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            creditCardInfo.style.display = paymentMethod === 'credit' ? 'block' : 'none';
        }

        function validateCreditCard() {
            const creditCardNumber = document.getElementById('credit_card_number').value;
            const creditCardExpiry = document.getElementById('credit_card_expiry').value;
            const creditCardCVC = document.getElementById('credit_card_cvc').value;

            const cardNumberPattern = /^[0-9]{16}$/;
            const expiryPattern = /^(0[1-9]|1[0-2])\/?([0-9]{4}|[0-9]{2})$/;
            const cvcPattern = /^[0-9]{3,4}$/;

            if (!cardNumberPattern.test(creditCardNumber)) {
                alert('Please enter a valid credit card number.');
                return false;
            }

            if (!expiryPattern.test(creditCardExpiry)) {
                alert('Please enter a valid expiry date in MM/YY format.');
                return false;
            }

            if (!cvcPattern.test(creditCardCVC)) {
                alert('Please enter a valid CVC code.');
                return false;
            }

            return true;
        }

        function validateForm() {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            if (paymentMethod === 'credit') {
                return validateCreditCard();
            }
            return true;
        }
    </script>
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Checkout</h1>
          <nav>
                <ul>
                    <li><a href="interface.html">Home</a></li>
                    <li><a href="menuu.php?user_id=<?php echo $user_id; ?>">menu</a></li>
                    
                    <li class="cart-icon"><a href="cart.php?user_id=<?php echo $user_id; ?>">🛒 Cart</a></li>
                    <li class="profile-icon" onclick="toggleProfileMenu()">👤 Profile</li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Checkout Section -->
    <div class="menu">
        <h1>Order Summary</h1>
        <div class="product-grid">
   <?php


while ($row = mysqli_fetch_assoc($cart_result)) {
    $productId = htmlspecialchars($row['product_id'], ENT_QUOTES, 'UTF-8');
    $productName = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
    $productPrice = htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8');
    $count = htmlspecialchars($row['count'], ENT_QUOTES, 'UTF-8');
    $productImage = htmlspecialchars($row['image_url'], ENT_QUOTES, 'UTF-8');

    // Calculate item total price
    $itemTotalPrice = $productPrice * $count;
    // Add to total price

    echo "<div class='product-item'>";
    echo "<img src='$productImage' alt='$productName'>";
    echo "<h3>$productName</h3>";
    echo "<p>Price: $$productPrice</p>";
    echo "<p>Quantity: x$count</p>";
    echo "<p>Total: $$itemTotalPrice</p>";
    echo "</div>";
}

// Insert total price into the payments table securely

?>

        </div>
        <h2>Total Price: $<?php echo number_format($totalPrice, 2); ?></h2>

        <h1>Delivery Address</h1>
        <?php if ($address): ?>
            <p><?php echo htmlspecialchars($address['city'], ENT_QUOTES, 'UTF-8') . ", " . htmlspecialchars($address['state'], ENT_QUOTES, 'UTF-8') . ", " . htmlspecialchars($address['street'], ENT_QUOTES, 'UTF-8') . ", " . htmlspecialchars($address['building'], ENT_QUOTES, 'UTF-8') . ", " . htmlspecialchars($address['floor'], ENT_QUOTES, 'UTF-8') . ", " . htmlspecialchars($address['apartment'], ENT_QUOTES, 'UTF-8'); ?></p>
        <?php else: ?>
            <p>No address on file. <a href="address.php?user_id=<?php echo $user_id; ?>">Add Address</a></p>
        <?php endif; ?>

        <h1>Payment Method</h1>
        <form action="" method="POST" onsubmit="return validateForm()">
            <label>
                <input type="radio" name="payment_method" value="cash" onclick="togglePaymentMethod()" checked> Cash on Delivery
            </label>
            <label>
                <input type="radio" name="payment_method" value="credit" onclick="togglePaymentMethod()"> Credit Card
            </label>

            <div id="credit-card-info" style="display: none;">
                <label for="credit_card_number">Credit Card Number:</label>
                <input type="text" id="credit_card_number" name="credit_card_number">

                <label for="credit_card_expiry">Expiry Date (MM/YY):</label>
                <input type="text" id="credit_card_expiry" name="credit_card_expiry">

                <label for="credit_card_cvc">CVC:</label>
                <input type="text" id="credit_card_cvc" name="credit_card_cvc">
            </div>

            <button type="submit" class="submit-button">Place Order</button>
        </form>
    </div>

    <footer>
        <p>© 2024 CairoCraving. All rights reserved.</p>
    </footer>
</body>
</html>
