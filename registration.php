
<?php 
include("config.php");
$error = "";
$checkerror = "";
$checkerror1 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['ps'] != $_POST['cp']) {
        $error = "Passwords must match";
    } else {
        // Escape user inputs to prevent SQL injection
        $name = mysqli_real_escape_string($con, $_POST['fn']);
        $email = mysqli_real_escape_string($con, $_POST['em']);
        $password = mysqli_real_escape_string($con, $_POST['ps']);// Hash the password
        $phone = mysqli_real_escape_string($con, $_POST['num']);

        $checkEmail = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $checkPhone = "SELECT * FROM users WHERE phone='$phone' LIMIT 1";
        $resultEmail = mysqli_query($con, $checkEmail);
        $resultPhone = mysqli_query($con, $checkPhone);

        if (mysqli_fetch_assoc($resultEmail)) {
            $checkerror = "Email already exists";
        } elseif (mysqli_fetch_assoc($resultPhone)) {
            $checkerror1 = "Phone number already exists";
        } else {
            $sql = "INSERT INTO users (name, email, password, phone) VALUES ('$name', '$email', '$password', '$phone')";

            // Execute the query and check if it was successful
            if (mysqli_query($con, $sql)) {
                // Redirect to login page
                header("Location: login.php");
                exit();
            } else {
                echo "Error: " . mysqli_error($con);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CairoCraving</title>
    <link rel="stylesheet" href="reg.css" type='text/css'>
</head>
<body>
    <div class="registration-container">
        <div class="form-header">
            <h1>Welcome to CairoCravings</h1>
            <p>Create an account to enjoy seamless online ordering!</p>
        </div>
        <form class="registration-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-group">
                <label for="full-name">Full Name</label>
                <input type="text" id="full-name" name="fn" placeholder="Enter your full name" value="<?php echo isset($_POST['fn']) ? $_POST['fn'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <?php if ($checkerror): ?>
                    <div class="error-message"><?php echo htmlspecialchars($checkerror, ENT_QUOTES); ?></div>
                <?php endif; ?>
                <input type="email" id="email" name="em" placeholder="Enter your email address" value="<?php echo isset($_POST['em']) ? $_POST['em'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="phone-number">Phone Number</label>
                <?php if ($checkerror1): ?>
                    <div class="error-message"><?php echo htmlspecialchars($checkerror1, ENT_QUOTES); ?></div>
                <?php endif; ?>
                <input type="tel" id="phone-number" name="num" placeholder="0123456789" value="<?php echo isset($_POST['num']) ? $_POST['num'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="ps" placeholder="Enter a strong password" value="<?php echo isset($_POST['ps']) ? $_POST['ps'] : ''; ?>"  required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <?php if ($error): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></div>
                <?php endif; ?>
                <input type="password" id="confirm-password" name="cp" placeholder="Confirm your password" value="<?php echo isset($_POST['cp']) ? $_POST['cp'] : ''; ?>" required>
            </div>
            <button type="submit" class="register-button">Register</button>
        </form>
        <p class="login-link">Already have an account? <a href="/login.php">Log in here</a>.</p>
    </div>
</body>
</html>

