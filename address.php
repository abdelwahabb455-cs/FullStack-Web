<?php
include("config.php");
session_start(); // Start the session

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $city = $_POST['city'];
    $state = $_POST['state'];
    $street = $_POST['street'];
    $building = $_POST['building'];
    $floor = $_POST['floor'];
    $apartment = $_POST['apartment'];

    // Insert address into database
    $sql = "INSERT INTO addresses (user_id, city, state, street, building, floor, apartment) 
            VALUES ('$user_id', '$city', '$state', '$street', '$building', '$floor', '$apartment')";
    mysqli_query($con, $sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Address - CairoCravings</title>
    <link rel="stylesheet" href="address.css">
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Add Address</h1>
            <nav>
                <ul>
                    <li><a href="interface.html">Home</a></li>
                    <li><a href="#">Contact</a></li>
                    <li class="cart-icon"><a href="cart.php">🛒 Cart</a></li>
                    <li class="profile-icon"><a href="profile.php">👤 Profile</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Address Form Section -->
    <div class="menu">
        <h1>Enter Your Address Details</h1>
        <form action="" method="POST" class="address-form">
            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>

            <label for="state">State:</label>
            <input type="text" id="state" name="state" required>

            <label for="street">Street:</label>
            <input type="text" id="street" name="street" required>

            <label for="building">Building:</label>
            <input type="text" id="building" name="building" required>

            <label for="floor">Floor:</label>
            <input type="text" id="floor" name="floor" required>

            <label for="apartment">Apartment:</label>
            <input type="text" id="apartment" name="apartment" required>

            <button type="submit" class="submit-button">Add Address</button>
        </form>
    </div>

    <footer>
        <p>© 2024 CairoCraving. All rights reserved.</p>
    </footer>
</body>
</html>
