<?php
include 'db_connect.php'; // Include the database connection

$searchTerm = '';
if (isset($_POST['searchTerm']) && !empty($_POST['searchTerm'])) {
    $searchTerm = $_POST['searchTerm'];
}

// Initialize SQL query
$sql = "SELECT id, item_code, item_category, item_subcategory, item_name, quantity, unit_price FROM item";
if (!empty($searchTerm)) {
    $sql .= " WHERE item_code LIKE ? OR item_name LIKE ? OR item_category LIKE ? OR item_subcategory LIKE ?";
}

// Prepare and execute the query
$stmt = $conn->prepare($sql);
if (!empty($searchTerm)) {
    $searchTerm = "%$searchTerm%";
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Items</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .container {
            margin-top: 20px;
        }
        table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Search Items</h2>

        <form class="d-flex mb-3" method="POST" action="search_item.php">
            <input class="form-control me-2" type="search" name="searchTerm" placeholder="Search by Item Code, Name, Category, or Subcategory" value="<?php echo htmlspecialchars($searchTerm); ?>" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>

        <a href="register_item.php" class="btn btn-success mb-3">Add </a>
        
        <?php if (isset($_GET['message'])): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '<?php echo htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8'); ?>'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'view_items.php';
                    }
                });
            </script>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Item Code</th>
                        <th>Item Category</th>
                        <th>Item Subcategory</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["id"], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row["item_code"], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row["item_category"], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row["item_subcategory"], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row["item_name"], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row["quantity"], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row["unit_price"], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <a href="edit_item.php?id=<?php echo htmlspecialchars($row["id"], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="#" class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo htmlspecialchars($row["id"], ENT_QUOTES, 'UTF-8'); ?>)">
                                    <i class='fas fa-trash'></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No items found</p>
        <?php endif; ?>

        <?php $stmt->close(); ?>
        <?php $conn->close(); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'delete_item.php?id=' + id;
                }
            });
        }
    </script>
</body>
</html>
