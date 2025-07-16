<?php 
include("config.php");

$error = ""; 
$id = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    
    // Query to check if the email exists in the database and retrieve user_id
    $sql = "SELECT user_id, password FROM users WHERE email = '$email'";
    $res = mysqli_query($con, $sql);
    
    // Check if a row is returned for that email
    if ($row = mysqli_fetch_assoc($res)) {
        // Compare the input password with the stored password (plain text comparison)
        if ($password === $row['password']) {
            $id = $row['user_id']; // Assign user_id to $id
            header("Location: menuu.php?user_id=$id"); // Redirect with user_id in URL
            exit();
        } else {
            $error = "Email or password incorrect"; // Set error if passwords don't match
        }
    } else {
        $error = "Email or password incorrect"; // Set error if email doesn't exist
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CairoCraving</title>
    <link rel="stylesheet" href="reg.css" type='text/css'>
</head>
<body>
    <div class="login-container">
        <div class="form-header">
            <h1>Welcome Back!</h1>
            <p>Login to CairoCravings to satisfy your breakfast cravings.</p>
        </div>
        <!-- Make sure the form submits to the same page -->
        <form class="login-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <!-- Show error message if any -->
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
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
</body>
</html>
