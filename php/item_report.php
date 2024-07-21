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
<html>
<head>
    <title>Item Report</title>
</head>
<body>
    <h2>Item Report</h2>
    <table border="1">
        <tr>
            <th>Item Name</th>
            <th>Item Category</th>
            <th>Item Subcategory</th>
            <th>Item Quantity</th>
        </tr>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['item_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($item['item_category'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($item['item_subcategory'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($item['item_quantity'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
