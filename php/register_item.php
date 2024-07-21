<?php
include 'db_connect.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemCode = $_POST['itemCode'];
    $itemName = $_POST['itemName'];
    $itemCategory = $_POST['itemCategory'];
    $itemSubcategory = $_POST['itemSubcategory'];
    $quantity = $_POST['quantity'];
    $unitPrice = $_POST['unitPrice'];

    // Form validation
    if (empty($itemCode) || empty($itemName) || empty($itemCategory) || empty($itemSubcategory) || empty($quantity) || empty($unitPrice)) {
        $error = "All fields are required.";
    } elseif (!is_numeric($itemCategory) || $itemCategory <= 0) {
        $error = "Item Category must be a positive number.";
    } elseif (!is_numeric($itemSubcategory) || $itemSubcategory <= 0) {
        $error = "Item Subcategory must be a positive number.";
    } elseif (!is_numeric($quantity) || $quantity <= 0) {
        $error = "Quantity must be a positive number.";
    } elseif (!is_numeric($unitPrice) || $unitPrice <= 0) {
        $error = "Unit Price must be a positive number.";
    } else {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO item (item_code, item_category, item_subcategory, item_name, quantity, unit_price) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssii", $itemCode, $itemCategory, $itemSubcategory, $itemName, $quantity, $unitPrice);

        // Execute statement
        if ($stmt->execute()) {
            $success = "Item registered successfully!";
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Item</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <script>
        function validateForm() {
            const itemCategory = document.getElementById('itemCategory').value;
            const itemSubcategory = document.getElementById('itemSubcategory').value;
            const quantity = document.getElementById('quantity').value;
            const unitPrice = document.getElementById('unitPrice').value;

            if (itemCategory <= 0 || itemSubcategory <= 0 || quantity <= 0 || unitPrice <= 0) {
                alert("Item Category, Item Subcategory, Quantity, and Unit Price must be positive numbers.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <h2>Register Item</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>
    <form method="POST" action="register_item.php" onsubmit="return validateForm()">
        <label for="itemCode">Item Code:</label>
        <input type="text" id="itemCode" name="itemCode" required><br>

        <label for="itemName">Item Name:</label>
        <input type="text" id="itemName" name="itemName" required><br>

        <label for="itemCategory">Item Category:</label>
        <input type="number" id="itemCategory" name="itemCategory" min="1" required><br>

        <label for="itemSubcategory">Item Subcategory:</label>
        <input type="number" id="itemSubcategory" name="itemSubcategory" min="1" required><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="1" required><br>

        <label for="unitPrice">Unit Price:</label>
        <input type="number" step="0.01" id="unitPrice" name="unitPrice" min="0.01" required><br>

        <input type="submit" value="Register Item">
    </form>
</body>
</html>
