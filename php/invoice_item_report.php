<?php
include 'db_connect.php'; // Include the database connection

// Define variables and initialize with empty values
$startDate = $endDate = "";
$invoiceItems = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // Validate date range
    if (!empty($startDate) && !empty($endDate)) {
        // Sanitize input to prevent SQL injection
        $startDate = htmlspecialchars($startDate, ENT_QUOTES, 'UTF-8');
        $endDate = htmlspecialchars($endDate, ENT_QUOTES, 'UTF-8');

        // Validate date format (YYYY-MM-DD)
        if (DateTime::createFromFormat('Y-m-d', $startDate) && DateTime::createFromFormat('Y-m-d', $endDate)) {
            // Prepare SQL query
            $stmt = $conn->prepare("
                SELECT im.invoice_no, i.date as invoiced_date, 
                       CONCAT(c.first_name, ' ', COALESCE(c.middle_name, ''), ' ', c.last_name) AS customer_name, 
                       itm.item_name, itm.item_code, itm.item_category, im.unit_price
                FROM invoice_master im
                JOIN invoice i ON im.invoice_no = i.invoice_no
                JOIN customer c ON i.customer = c.id
                JOIN item itm ON im.item_id = itm.id
                WHERE i.date BETWEEN ? AND ?
            ");
            if (!$stmt) {
                die('Prepare Error: ' . $conn->error);
            }

            // Bind parameters and execute the query
            $stmt->bind_param("ss", $startDate, $endDate);
            if (!$stmt->execute()) {
                die('Execute Error: ' . $stmt->error);
            }

            // Fetch the results
            $result = $stmt->get_result();
            $invoiceItems = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        } else {
            echo "Invalid date format. Please use YYYY-MM-DD.";
        }
    } else {
        echo "Please select both start and end dates.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice Item Report</title>
</head>
<body>
    <h2>Invoice Item Report</h2>
    <form method="POST" action="invoice_item_report.php">
        <label for="startDate">Start Date:</label>
        <input type="date" id="startDate" name="startDate" value="<?php echo htmlspecialchars($startDate, ENT_QUOTES, 'UTF-8'); ?>" required><br>
        <label for="endDate">End Date:</label>
        <input type="date" id="endDate" name="endDate" value="<?php echo htmlspecialchars($endDate, ENT_QUOTES, 'UTF-8'); ?>" required><br>
        <input type="submit" value="Generate Report">
    </form>

    <?php if (!empty($invoiceItems)): ?>
        <h3>Invoice Items from <?php echo htmlspecialchars($startDate, ENT_QUOTES, 'UTF-8'); ?> to <?php echo htmlspecialchars($endDate, ENT_QUOTES, 'UTF-8'); ?></h3>
        <table border="1">
            <tr>
                <th>Invoice Number</th>
                <th>Invoiced Date</th>
                <th>Customer Name</th>
                <th>Item Name</th>
                <th>Item Code</th>
                <th>Item Category</th>
                <th>Item Unit Price</th>
            </tr>
            <?php foreach ($invoiceItems as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['invoice_no'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($item['invoiced_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($item['customer_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($item['item_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($item['item_code'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($item['item_category'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($item['unit_price'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
