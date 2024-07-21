<?php
include 'db_connect.php'; // Include the database connection

$sql = "SELECT id, item_code, item_category, item_subcategory, item_name, quantity, unit_price FROM item";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>View Items</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>
<body>
    <h2>Item List</h2>
    <?php if ($result->num_rows > 0): ?>
        <table border='1'>
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
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["id"]; ?></td>
                    <td><?php echo $row["item_code"]; ?></td>
                    <td><?php echo $row["item_category"]; ?></td>
                    <td><?php echo $row["item_subcategory"]; ?></td>
                    <td><?php echo $row["item_name"]; ?></td>
                    <td><?php echo $row["quantity"]; ?></td>
                    <td><?php echo $row["unit_price"]; ?></td>
                    <td>
                        <a href='edit_item.php?id=<?php echo $row["id"]; ?>'>Edit</a> |
                        <a href='delete_item.php?id=<?php echo $row["id"]; ?>'>Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No items found</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>
</html>
