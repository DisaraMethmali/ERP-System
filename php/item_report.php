<?php
include 'db_connect.php'; // Include the database connection

// Fetch item report data
$stmt = $conn->prepare("
    SELECT DISTINCT itm.item_name, itm.item_category, itm.item_subcategory, SUM(im.quantity) AS item_quantity
    FROM item itm
    LEFT JOIN invoice_master im ON itm.id = im.item_id
    GROUP BY itm.item_name, itm.item_category, itm.item_subcategory
");
$stmt->execute();
$result = $stmt->get_result();
$items = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Report</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Poppins, sans-serif;
            background-color: #e0f7fa;
        }
        .container {
            margin-top: 50px;
        }
        .table {
            background-color: #fff;
            border-radius: 15px;
            overflow: hidden;
            border-collapse: separate;
            border-spacing: 0;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table th:first-child, .table td:first-child {
            border-top-left-radius: 15px;
            border-bottom-left-radius: 15px;
        }
        .table th:last-child, .table td:last-child {
            border-top-right-radius: 15px;
            border-bottom-right-radius: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Item Report</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Item Name</th>
                        <th>Item Category</th>
                        <th>Item Subcategory</th>
                        <th>Item Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['item_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($item['item_category'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($item['item_subcategory'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($item['item_quantity'], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
