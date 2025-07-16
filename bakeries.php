<?php
include("config.php");

// Check if user_id exists in the URL
$user_id = null;
$name = "Guest";
if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $user_id = htmlspecialchars($_GET['user_id']);
    $sql = "SELECT name FROM users WHERE user_id = $user_id";
    $res = mysqli_query($con, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $name = $row["name"];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id'], $_POST['count'])) {
        $product_id = intval($_POST['product_id']);
        $count = intval($_POST['count']);

        // Check if the product already exists in the cart
        $checkQuery = "SELECT count FROM cart WHERE user_id = $user_id AND product_id = $product_id";
        $result = mysqli_query($con, $checkQuery);

        if (mysqli_num_rows($result) > 0) {
            // Product exists in the cart, update the count
            $updateQuery = "UPDATE cart SET count = count + $count WHERE user_id = $user_id AND product_id = $product_id";
            mysqli_query($con, $updateQuery);
        } else {
            // Product does not exist in the cart, insert a new row
            $insertQuery = "INSERT INTO cart (user_id, product_id, count) VALUES ($user_id, $product_id, $count)";
            mysqli_query($con, $insertQuery);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CairoCravings Bakeries Menu</title>
    <link rel="stylesheet" href="menuu.css"> 
    <script>
        function addToCart(productName) {
            const confirmation = document.getElementById('confirmation');
            confirmation.innerHTML = productName + " added to cart!";
            setTimeout(() => {
                confirmation.innerHTML = "";
            }, 2000);
        }

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
            <h1>Welcome, <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h1>
            <nav>
                <ul>
                    <li><a href="interface.php">Home</a></li>
                    <li class="cart-icon"><a href="cart.php<?php echo $user_id ? '?user_id=' . $user_id : ''; ?>">🛒 Cart</a></li>
                    <?php if ($user_id): ?>
                        <li class="profile-icon" onclick="toggleProfileMenu()">👤 Profile</li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <?php if ($user_id): ?>
        <!-- Profile Menu -->
        <div id="profile-menu" class="profile-menu" style="display: none;">
            <p class="pr-box"><a href="edit_profile_settings.php?user_id=<?php echo $user_id; ?>">Edit Profile Settings</a></p>
            <p class="pr-box"><a href="logout.php">Logout</a></p>
        </div>
    <?php endif; ?>

    <!-- Confirmation Message -->
    <p id="confirmation" style="color: green; font-weight: bold;"></p>

    <!-- Bakeries Products Section -->
    <div class="menu">
        <h1>Our Bakeries Selection</h1>
        <div class="product-grid">
            <?php
            $sql = "SELECT * FROM products WHERE category = 'bakery'";
            $result = mysqli_query($con, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                $productId = $row['product_id'];
                $productName = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
                $productPrice = htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8');
                $stockQuantity = htmlspecialchars($row['stock_quantity'], ENT_QUOTES, 'UTF-8');
                $productImage = htmlspecialchars($row['image_url'], ENT_QUOTES, 'UTF-8');
                $stockStatus = $stockQuantity > 0 ? "In Stock" : "Out of Stock";

                echo "<div class='product-item'>";
                echo "<img src='$productImage' alt='$productName' width='626' height='626'>"; 
                echo "<h3>$productName</h3>";
                echo "<p>$productPrice EGP</p>";
                echo "<p>$stockStatus</p>";
                if ($stockQuantity > 0) {
                    echo "<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "?user_id=$user_id' method='POST'>";
                    echo "<input type='hidden' name='product_id' value='$productId'>";
                    echo "<input type='number' name='count' min='1' max='$stockQuantity' value='1'>";
                    echo "<button type='submit' class='add-to-cart'>Add to Cart</button>";
                    echo "</form>";
                }
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
