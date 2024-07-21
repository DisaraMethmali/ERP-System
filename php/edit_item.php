<?php
include 'db_connect.php'; // Include the database connection

// Check if id is set and valid
if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
    $id = $_GET['id'];

    // Retrieve existing item data
    $stmt = $conn->prepare("SELECT id, item_code, item_category, item_subcategory, item_name, quantity, unit_price FROM item WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Item not found.";
        exit;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
    exit;
}

// Update item data if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
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
        $stmt = $conn->prepare("UPDATE item SET item_code = ?, item_category = ?, item_subcategory = ?, item_name = ?, quantity = ?, unit_price = ? WHERE id = ?");
        $stmt->bind_param("ssssidi", $itemCode, $itemCategory, $itemSubcategory, $itemName, $quantity, $unitPrice, $id);

        // Execute statement
        if ($stmt->execute()) {
            header("Location: view_items.php"); // Redirect to the items list page
            exit;
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
    <title>Edit Item</title>
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
    <h2>Edit Item</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="edit_item.php?id=<?php echo $id; ?>" onsubmit="return validateForm()">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label for="itemCode">Item Code:</label>
        <input type="text" id="itemCode" name="itemCode" value="<?php echo htmlspecialchars($row['item_code']); ?>" required><br>

        <label for="itemName">Item Name:</label>
        <input type="text" id="itemName" name="itemName" value="<?php echo htmlspecialchars($row['item_name']); ?>" required><br>

        <label for="itemCategory">Item Category:</label>
        <input type="number" id="itemCategory" name="itemCategory" value="<?php echo htmlspecialchars($row['item_category']); ?>" min="1" required><br>

        <label for="itemSubcategory">Item Subcategory:</label>
        <input type="number" id="itemSubcategory" name="itemSubcategory" value="<?php echo htmlspecialchars($row['item_subcategory']); ?>" min="1" required><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($row['quantity']); ?>" min="1" required><br>

        <label for="unitPrice">Unit Price:</label>
        <input type="number" step="0.01" id="unitPrice" name="unitPrice" value="<?php echo htmlspecialchars($row['unit_price']); ?>" min="0.01" required><br>

        <input type="submit" value="Update Item">
    </form>
</body>
</html>
