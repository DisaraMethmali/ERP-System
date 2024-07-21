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
        $error = "Item Category must be a positive integer.";
    } elseif (!is_numeric($itemSubcategory) || $itemSubcategory <= 0) {
        $error = "Item Subcategory must be a positive integer.";
    } elseif (!is_numeric($quantity) || $quantity <= 0) {
        $error = "Quantity must be a positive integer.";
    } elseif (!is_numeric($unitPrice) || $unitPrice <= 0) {
        $error = "Unit Price must be a positive number.";
    } else {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO item (item_code, item_category, item_subcategory, item_name, quantity, unit_price) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssii", $itemCode, $itemCategory, $itemSubcategory, $itemName, $quantity, $unitPrice);

        // Execute statement
        if ($stmt->execute()) {
            // Redirect to view items page
            header("Location: view_items.php");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Item</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        .form-control.is-valid {
            border-color: #198754;
        }
        .container {
            max-width: 1200px;
        }
    </style>
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

        // Restrict fields to numeric values only
        function restrictInput(e) {
            const regex = /^[0-9]*\.?[0-9]*$/; // Allow numbers and decimal points
            if (!regex.test(e.target.value)) {
                e.target.value = e.target.value.slice(0, -1); // Remove invalid character
            }
        }

        // Restrict fields to integers only
        function restrictIntegerInput(e) {
            const regex = /^[0-9]*$/; // Allow only integer numbers
            if (!regex.test(e.target.value)) {
                e.target.value = e.target.value.slice(0, -1); // Remove invalid character
            }
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2>Register Item</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="register_item.php" onsubmit="return validateForm()">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="itemCode" class="form-label">Item Code:</label>
                        <input type="text" id="itemCode" name="itemCode" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="itemName" class="form-label">Item Name:</label>
                        <input type="text" id="itemName" name="itemName" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="itemCategory" class="form-label">Item Category:</label>
                        <input type="text" id="itemCategory" name="itemCategory" class="form-control" oninput="restrictIntegerInput(event)" required>
                    </div>

                    <div class="mb-3">
                        <label for="itemSubcategory" class="form-label">Item Subcategory:</label>
                        <input type="text" id="itemSubcategory" name="itemSubcategory" class="form-control" oninput="restrictIntegerInput(event)" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <input type="text" id="quantity" name="quantity" class="form-control" oninput="restrictIntegerInput(event)" required>
                    </div>

                    <div class="mb-3">
                        <label for="unitPrice" class="form-label">Unit Price:</label>
                        <input type="text" id="unitPrice" name="unitPrice" class="form-control" oninput="restrictInput(event)" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Register Item</button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
