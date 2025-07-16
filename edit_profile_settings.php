<?php
session_start();
include("config.php");

// Check if user_id is in session or GET parameter
if (isset($_GET['user_id'])) {
    $_SESSION['user_id'] = $_GET['user_id'];
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT name, email, password FROM users WHERE user_id = $user_id";
$result = mysqli_query($con, $sql);
$user = mysqli_fetch_assoc($result);

// Update user details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = !empty($_POST['password']) ? mysqli_real_escape_string($con, $_POST['password']) : $user['password'];

    $update_sql = "UPDATE users SET name = '$name', email = '$email', password = '$password' WHERE user_id = $user_id";
    
    if (mysqli_query($con, $update_sql)) {
        $success = "Profile updated successfully!";
    } else {
        $error = "Error updating profile: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - CairoCravings</title>
    <link rel="stylesheet" href="edit_profile_settings.css"> <!-- Link to the external CSS file -->
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Welcome, <?php echo htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
        </div>
    </header>
    <div class="profile-container">
        <?php if (isset($success)) { echo "<p class='success'>$success</p>"; } ?>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form action="edit_profile_settings.php" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password (leave blank to keep current):</label>
                <input type="password" id="password" name="password">
            </div>
            <button type="submit" class="save-button">Save Changes</button>
        </form>
    </div>
    <footer>
        
        <p>© 2024 CairoCraving. All rights reserved.</p>
    </footer>
</body>
</html>
