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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CairoCravings Menu</title>
    <link rel="stylesheet" href="menuu.css">
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

    <!-- Menu Section -->
    <div class="menu">
        <h1>Our Menu</h1>

        <!-- 2x2 Grid Layout for Categories -->
        <div class="category-grid">
            <div class="category-box">
                <a href="snacks.php<?php echo $user_id ? '?user_id=' . $user_id : ''; ?>">
                    <img src="snacks.jpg" alt="Snacks" />
                </a>
                <h3>Snacks</h3>
            </div>
            <div class="category-box">
                <a href="bakeries.php<?php echo $user_id ? '?user_id=' . $user_id : ''; ?>">
                    <img src="Bakeries.jpg" alt="Bakeries" />
                </a>
                <h3>Bakeries</h3>
            </div>
            <div class="category-box">
                <a href="desserts.php<?php echo $user_id ? '?user_id=' . $user_id : ''; ?>">
                    <img src="Desserts.jpg" alt="Desserts" />
                </a>
                <h3>Desserts</h3>
            </div>
            <div class="category-box">
                <a href="coffee.php<?php echo $user_id ? '?user_id=' . $user_id : ''; ?>">
                    <img src="360_F_105907729_4RzHYsHJ2UFt5koUI19fc6VzyFPEjeXe.jpg" alt="Coffee" />
                </a>
                <h3>Coffee</h3>
            </div>
        </div>
    </div>

    <footer>
        <p>© 2024 CairoCraving. All rights reserved.</p>
    </footer>

    <script src="menu.js"></script>
</body>
</html>
