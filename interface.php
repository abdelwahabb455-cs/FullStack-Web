<?php
session_start();
include("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Check if email exists in the staff table
    $sql = "SELECT staff_id, role FROM staff WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['staff_id'] = $row['staff_id'];
        header("Location: staff_dashboard.php");
        exit();
    }

    // Check if email exists in the users table
    $sql = "SELECT user_id FROM users WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['user_id'];
        header("Location: menuu.php?user_id=" . $row['user_id']);
        exit();
    }

    // Check if email exists in the delivery_staff table
    $sql = "SELECT delivery_staff_id FROM delivery_staff WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['delivery_staff_id'] = $row['delivery_staff_id'];
        header("Location: delivery.php");
        exit();
    }

    // If no match, show error
    $error = "Invalid email or password.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CairoCraving</title>
    <link rel="stylesheet" href="interface.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHs8D2O45cb5m2IQ4dv8s80E5aGmaZ6MzuHwo8sn2l+0Dz8Md02IYq02w/tf5pKJKXw2P5oA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="menuu.php">Menu</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="main-content">
        <div class="text-container">
            <h1 class="slogan">Satisfy Your Cravings, Anytime, Anywhere</h1>
            <p class="description">Delicious meals delivered right to your door in Cairo.</p>
        </div>

        <div class="form-container">
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
            <form class="login-form" method="post" action="">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="login-button">Login</button>
            </form>
            <div class="register-link">
                <p>Don't have an account? <a href="registration.php">Register</a></p>
            </div>
        </div>
    </div>

    <footer id="contact">
        <div class="footer-left">
            <p><i class="fas fa-phone"></i> Hotline: 19281</p>
            <p><i class="fas fa-envelope"></i> Email: cairo@cravings.com</p>
        </div>
        <div class="footer-right">
            <p>© 2024 CairoCraving. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
