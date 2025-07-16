<header>
    <div class="header-content">
        <div class="header-left">
            <img src="logosma.png" alt="Logo" class="logo">
            <h1>Welcome, <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h1>
        </div>
        <nav>
            <ul>
                <li><a href="interface.php">Home</a></li>
				
 <li><a href="menuu.php<?php echo $user_id ? '?user_id=' . $user_id : ''; ?>">Menu</a></li>
                <li class="cart-icon"><a href="cart.php<?php echo $user_id ? '?user_id=' . $user_id : ''; ?>">🛒 Cart</a></li>
                <?php if ($user_id): ?>
                    <li class="profile-icon" onclick="toggleProfileMenu()">👤 Profile</li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

