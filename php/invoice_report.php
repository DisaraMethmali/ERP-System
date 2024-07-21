<?php
include 'db_connect.php'; // Include the database connection

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define variables and initialize with empty values
$startDate = $endDate = "";
$invoices = [];

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
                SELECT i.invoice_no, i.date, CONCAT(c.first_name, ' ', c.middle_name, ' ', c.last_name) AS customer, d.district AS customer_district, i.item_count, i.amount
                FROM invoice i
                JOIN customer c ON i.customer = c.id
                JOIN district d ON c.district = d.id
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
            $invoices = $result->fetch_all(MYSQLI_ASSOC);
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
    <title>Invoice Report</title>
</head>
<body>
    <h2>Invoice Report</h2>
    <form method="POST" action="invoice_report.php">
        <label for="startDate">Start Date:</label>
        <input type="date" id="startDate" name="startDate" value="<?php echo htmlspecialchars($startDate, ENT_QUOTES, 'UTF-8'); ?>" required><br>
        <label for="endDate">End Date:</label>
        <input type="date" id="endDate" name="endDate" value="<?php echo htmlspecialchars($endDate, ENT_QUOTES, 'UTF-8'); ?>" required><br>
        <input type="submit" value="Generate Report">
    </form>

    <?php if (!empty($invoices)): ?>
        <h3>Invoices from <?php echo htmlspecialchars($startDate, ENT_QUOTES, 'UTF-8'); ?> to <?php echo htmlspecialchars($endDate, ENT_QUOTES, 'UTF-8'); ?></h3>
        <table border="1">
            <tr>
                <th>Invoice Number</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Customer District</th>
                <th>Item Count</th>
                <th>Invoice Amount</th>
            </tr>
            <?php foreach ($invoices as $invoice): ?>
                <tr>
                    <td><?php echo htmlspecialchars($invoice['invoice_no'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($invoice['date'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($invoice['customer'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($invoice['customer_district'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($invoice['item_count'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($invoice['amount'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
