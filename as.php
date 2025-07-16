<?php
include("config.php");
session_start(); // Start the session

$name = ""; // Placeholder for user's name

// Check if the session user_id is set
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; // Retrieve the user ID from the session

    // Query to get the user's name from the database
    $sql = "SELECT name FROM users WHERE user_id = '$user_id'";
    $res = mysqli_query($con, $sql);

    if ($res) {
        if ($row = mysqli_fetch_assoc($res)) {
            $name = $row["name"]; // Retrieve the user's name
        }
    }
} else {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_to_cart"])) {
    $product_id = mysqli_real_escape_string($con, $_POST['product_id']);
    $count = mysqli_real_escape_string($con, $_POST['count']);

    $sql_check = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
    $res_check = mysqli_query($con, $sql_check);

    if (mysqli_num_rows($res_check) > 0) {
        $sql_update = "UPDATE cart SET count = count + '$count' WHERE user_id = '$user_id' AND product_id = '$product_id'";
        mysqli_query($con, $sql_update);
    } else {
        $sql_insert = "INSERT INTO cart (user_id, product_id, count) VALUES ('$user_id', '$product_id', '$count')";
        mysqli_query($con, $sql_insert);
    }

    echo "<script>document.getElementById('confirmation').innerHTML = 'Item added to cart!';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CairoCravings Coffee Menu</title>
    <link rel="stylesheet" href="menuu.css">
    <script>
        function addToCart(productId) {
            document.getElementById('product_id').value = productId;
            document.getElementById('count_form').submit();
        }

        function showConfirmation(message) {
            const confirmation = document.getElementById('confirmation');
            confirmation.innerHTML = message;
            setTimeout(() => {
                confirmation.innerHTML = "";
            }, 2000);
        }
    </script>
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Welcome, <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h1>
            <nav>
                <ul>
                    <li><a href="interface.html">Home</a></li>
                    <li><a href="#">Contact</a></li>
                    <li class="cart-icon"><a href="cart.php">🛒 Cart</a></li>
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

    <!-- Confirmation Message -->
    <p id="confirmation" style="color: green; font-weight: bold;"></p>

    <!-- Coffee Products Section -->
    <div class="menu">
        <h1>Our Coffee Selection</h1>
        <form id="count_form" method="POST" action="">
            <input type="hidden" name="product_id" id="product_id" value="">
            <input type="hidden" name="count" id="count" value="1">
        </form>
        <div class="product-grid">
            <?php
            $sql = "SELECT * FROM products WHERE category = 'coffee'";
            $result = mysqli_query($con, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                $product_id = htmlspecialchars($row['product_id'], ENT_QUOTES, 'UTF-8');
                $productName = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
                $productPrice = htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8');
                $stockQuantity = htmlspecialchars($row['stock_quantity'], ENT_QUOTES, 'UTF-8');
                $productImage = htmlspecialchars($row['image_url'], ENT_QUOTES, 'UTF-8');
                $stockStatus = $stockQuantity > 0 ? "In Stock" : "Out of Stock";

                echo "<div class='product-item'>";
                echo "<img src='$productImage' alt='$productName'>";
                echo "<h3>$productName</h3>";
                echo "<p>$productPrice</p>";
                echo "<p>$stockStatus</p>";
                if ($stockQuantity > 0) {
                    echo "<input type='number' name='count' min='1' max='$stockQuantity' value='1' onchange=\"document.getElementById('count').value = this.value\">";
                    echo "<button type='button' class='add-to-cart' onclick=\"addToCart('$product_id')\">Add to Cart</button>";
                }
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <footer>
        <p>© 2024 CairoCraving. All rights reserved.</p>
    </footer>

    <script>
        // Toggle the profile menu
        function toggleProfileMenu() {
            const profileMenu = document.getElementById('profile-menu');
            profileMenu.style.display = profileMenu.style.display === 'block' ? 'none' : 'block';
        }
    </script>
</body>
</html>
