<?php
include("config.php");


$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product_id'])) {
    $delete_product_id = $_POST['delete_product_id'];
    $delete_sql = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$delete_product_id'";
    mysqli_query($con, $delete_sql);
}

// Fetch cart items for the user
$sql = "SELECT cart.product_id, cart.count, products.name, products.price, products.image_url FROM cart 
        JOIN products ON cart.product_id = products.product_id 
        WHERE cart.user_id = '$user_id'";
$result = mysqli_query($con, $sql);

$totalPrice = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - CairoCravings</title>
    <link rel="stylesheet" href="cart.css">
	  <script>
        // Toggle the profile menu
        function toggleProfileMenu() {
            const profileMenu = document.getElementById('profile-menu');
            profileMenu.style.display = profileMenu.style.display === 'block' ? 'none' : 'block';
        }
    </script>
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Cart</h1>
            <nav>
                <ul>
                    <li><a href="interface.html">Home</a></li>
               
                    <li><a href="menuu.php?user_id=<?php echo $user_id; ?>">menu</a></li>
                             <li class="profile-icon" onclick="toggleProfileMenu()">👤 Profile</li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Profile Menu -->
    <div id="profile-menu" class="profile-menu" style="display: none;">
        <p><a href="profile.php">Edit Profile Settings</a></p>
        <p><a href="login.php">Logout</a></p>
       
    </div>

    <!-- Cart Items Section -->
    <div class="menu">
        <h1>Your Cart</h1>
        <div class="product-grid">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $productId = htmlspecialchars($row['product_id'], ENT_QUOTES, 'UTF-8');
                $productName = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
                $productPrice = htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8');
                $productImage = htmlspecialchars($row['image_url'], ENT_QUOTES, 'UTF-8');
                $count = htmlspecialchars($row['count'], ENT_QUOTES, 'UTF-8');
	
                $itemTotalPrice = $productPrice * $count;
                $totalPrice += $itemTotalPrice;

                echo "<div class='product-item'>";
                echo "<img src='$productImage' alt='$productName'>";
                echo "<h3>$productName</h3>";
                echo "<p>Price: $productPrice</p>";
                echo "<p>Quantity: x$count</p>";
                echo "<p>Total: $$itemTotalPrice</p>";
                echo "<form method='POST' action='".$_SERVER['PHP_SELF']."?user_id=$user_id'>";
                echo "<input type='hidden' name='delete_product_id' value='$productId'>";
                echo "<button type='submit' class='delete-button'>🗑️</button>";
                echo "</form>";
                echo "</div>";
            }
            ?>
        </div>
        <h2>Total Price: $<?php echo number_format($totalPrice, 2); ?></h2>
        <a href="checkout.php?user_id=<?php echo $user_id; ?>" class="checkout-button">Checkout</a>
    </div>

    <footer>
        <p>© 2024 CairoCraving. All rights reserved.</p>
    </footer>
</body>
</html>
