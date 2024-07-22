<?php
include 'db_connect.php'; // Include the database connection

// Include Bootstrap CSS and Font Awesome for icons
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">';
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">';

$sql = "SELECT id, title, first_name, middle_name, last_name, contact_no, district FROM customer";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer List</title>
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
            margin-left:'100px';
            width: 100%; /* Ensure table width is responsive */
            max-width: 1200px; /* Optional: Set a max-width */
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
    <div class="container">
   
        <div class="table-wrapper">
            <h2 class="mb-4">Customer List</h2>
             <!-- Search Form -->
             <a href="search_customer.php" class=" btn btn-secondary mb-3">
               Search
            </a>
            <a href="register_customer.php" class=" btn btn-success mb-3">
                <i class="fas fa-plus"></i> Add 
            </a>

            <?php
            if ($result->num_rows > 0) {
                echo "<table class='table table-striped'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Last Name</th>
                                <th>Contact Number</th>
                                <th>District</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row["id"]) . "</td>
                            <td>" . htmlspecialchars($row["title"]) . "</td>
                            <td>" . htmlspecialchars($row["first_name"]) . "</td>
                            <td>" . htmlspecialchars($row["middle_name"]) . "</td>
                            <td>" . htmlspecialchars($row["last_name"]) . "</td>
                            <td>" . htmlspecialchars($row["contact_no"]) . "</td>
                            <td>" . htmlspecialchars($row["district"]) . "</td>
                            <td>
                                <a href='edit_customer.php?id=" . htmlspecialchars($row["id"]) . "' class='btn btn-primary btn-sm'>
    <i class='fas fa-edit'></i> Edit
</a>

                                <a href='delete_customer.php?id=" . htmlspecialchars($row["id"]) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this customer?\")'>
                                    <i class='fas fa-trash'></i> Delete
                                </a>
                            </td>
                        </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<div class='alert alert-info' role='alert'>No results found.</div>";
            }
            $conn->close();
            ?>
        </div>
    </div>

    <!-- Include Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
