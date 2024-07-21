<?php
include 'db_connect.php'; // Include the database connection

// Include Bootstrap CSS and Font Awesome for icons
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">';
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">';

// Retrieve the search query
$searchTerm = '';
if (isset($_POST['searchTerm']) && !empty($_POST['searchTerm'])) {
    $searchTerm = $_POST['searchTerm'];
}

// Initialize SQL query
$sql = "SELECT id, title, first_name, middle_name, last_name, contact_no, district FROM customer";
if (!empty($searchTerm)) {
    $sql .= " WHERE first_name LIKE ? OR last_name LIKE ?";
}

// Prepare and execute the query
$stmt = $conn->prepare($sql);
if (!empty($searchTerm)) {
    $searchTerm = "%$searchTerm%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="table-wrapper">
            <h2 class="mb-4">Search Results</h2>
            <form class="d-flex mb-3" method="POST" action="search_customer.php">
                <input class="form-control me-2" type="search" name="searchTerm" placeholder="Search by First or Last Name" value="<?php echo htmlspecialchars($searchTerm); ?>" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            
            <a href="view_customers.php" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Back to Customer List
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
                echo "<div class='alert alert-info' role='alert'>No results found for '" . htmlspecialchars($searchTerm) . "'.</div>";
            }
            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
