<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cairo_craving";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$product = null;

// Check if a product ID was provided for editing
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    // Retrieve the product data
    $result = $conn->query("SELECT * FROM products WHERE product_id = $product_id");
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        $message = "Product not found.";
    }
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $category = $_POST['category'];
    $image_url = $_POST['image_url'];

    // Update product data
    $sql = "UPDATE products SET name='$name', description='$description', price=$price, stock_quantity=$stock_quantity, category='$category', image_url='$image_url' WHERE product_id=$product_id";

    if ($conn->query($sql) === TRUE) {
        $message = "Product updated successfully!";
        // Refresh product data after update
        $result = $conn->query("SELECT * FROM products WHERE product_id = $product_id");
        $product = $result->fetch_assoc();
    } else {
        $message = "Error updating product: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Navigation</h2>
            <ul>
                <li><a href="addproduct.php">Add New Product</a></li>
                <li><a href="staffmenu.html">Back to Dashboard</a></li>
            </ul>
        </div>
        <div class="main-content">
            <header class="navbar">
                <h1>Update Product</h1>
            </header>
            <main class="form-container">
                <?php if ($message): ?>
                    <p class="message"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>

                <?php if ($product): ?>
                    <form action="" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">

                        <div class="form-group">
                            <label for="name">Product Name:</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="price">Price:</label>
                            <input type="number" step="0.01" id="price" name="price" value="<?php echo $product['price']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="stock_quantity">Stock Quantity:</label>
                            <input type="number" id="stock_quantity" name="stock_quantity" value="<?php echo $product['stock_quantity']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="category">Category:</label>
                            <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="image_url">Image URL:</label>
                            <input type="url" id="image_url" name="image_url" value="<?php echo htmlspecialchars($product['image_url']); ?>" required>
                        </div>

                        <button type="submit" class="submit-button">Update Product</button>
                    </form>
                <?php else: ?>
                    <p>No product selected for updating.</p>
                <?php endif; ?>
            </main>
        </div>
    </div>
</body>
</html>
