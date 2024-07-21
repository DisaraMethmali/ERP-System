<?php
include 'db_connect.php'; // Include the database connection

// Initialize variables
$totalCustomers = $totalItems = $totalInvoices = 0;
$customers = [];

// Query to get total customers
$stmt = $conn->prepare("SELECT COUNT(*) AS total_customers FROM customer");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalCustomers = $row['total_customers'];
$stmt->close();

// Query to get total items
$stmt = $conn->prepare("SELECT COUNT(*) AS total_items FROM item");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalItems = $row['total_items'];
$stmt->close();

// Query to get total invoices
$stmt = $conn->prepare("SELECT COUNT(*) AS total_invoices FROM invoice");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalInvoices = $row['total_invoices'];
$stmt->close();

// Query to get customer details
$stmt = $conn->prepare("SELECT id, title, first_name, middle_name, last_name, contact_no, district FROM customer");
$stmt->execute();
$result = $stmt->get_result();
$customers = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: Poppins, sans-serif;
            background-color: #e0f7fa;
        }

        .sidebar {
            height: 100vh;
            background-color: #00274d;
            padding-top: 20px;
            position: fixed;
            width: 120px;
            border-top-right-radius: 65px;
            border-bottom-left-radius: 65px;
        }

        .sidebar a {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 12px;
            color: #fff;
            padding: 15px;
            text-decoration: none;
            margin-bottom: 5px;
        }

        .sidebar a:hover {
            background-color: #00509e;
            border-radius: 5px;
        }

        .sidebar i {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
        }

        .card-box {
            border-radius: 15px;
            background-color: #fff;
            border: none;
            margin-bottom: 20px;
            margin-top: 50px;
        }

        .card-text {
            font-size: 24px;
            font-weight: bold;
        }

        table {
            background-color: #fff;
            border-radius: 25px;
            overflow: hidden;
        }

        table th, table td {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="sidebar">
      
        <a href="index.php">
            <i class="bi bi-house-door"></i>
            <span>Home</span>
        </a>
        <a href="view_customers.php">
            <i class="bi bi-person"></i>
            <span>Users</span>
        </a>
        <a href="view_items.php">
            <i class="bi bi-box"></i>
            <span>Items</span>
        </a>
       
        <ul class="list-unstyled ps-3">
            <li>
                <a href="invoice_report.php">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>In Report</span>
                </a>
            </li>
            <li>
                <a href="item_report.php">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Item Report</span>
                </a>
            </li>
            <li>
            <a href="invoice_item_report.php">
                <i class="bi bi-file-earmark-text"></i>
                <span>I.Invoice Report</span>
            </a>
            </li>
        </ul>
    </div>

    <div class="content">
     
        <div class="row">
            <div class="col-md-4">
                <div class="card card-box">
                    <div class="card-body">
                        <h5 class="card-title">Total Customers</h5>
                        <p class="card-text"><?php echo $totalCustomers; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-box">
                    <div class="card-body">
                        <h5 class="card-title">Total Items</h5>
                        <p class="card-text"><?php echo $totalItems; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-box">
                    <div class="card-body">
                        <h5 class="card-title">Total Invoices</h5>
                        <p class="card-text"><?php echo $totalInvoices; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="mt-4">Customer List</h3>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Contact No</th>
                    <th>District</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row["id"], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row["title"], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row["first_name"], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row["middle_name"], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row["last_name"], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row["contact_no"], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row["district"], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
