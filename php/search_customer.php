<?php
include 'db_connect.php'; // Include the database connection

// Include CSS for styling
echo '<link rel="stylesheet" type="text/css" href="../css/styles.css">';

// HTML form for search
echo '<form method="POST" action="">
        <input type="text" name="searchTerm" placeholder="Search by First or Last Name" />
        <input type="submit" value="Search" />
      </form>';

// Initialize SQL query
$sql = "SELECT id, title, first_name, middle_name, last_name, contact_no, district FROM customer";

// Check if a search term was provided
if (isset($_POST['searchTerm']) && !empty($_POST['searchTerm'])) {
    $searchTerm = $_POST['searchTerm'];
    // Modify the SQL query to include the search term
    $sql .= " WHERE first_name LIKE ? OR last_name LIKE ?";
}

// Prepare and execute the query
$stmt = $conn->prepare($sql);

// Bind parameters if a search term is present
if (isset($searchTerm) && !empty($searchTerm)) {
    $searchTerm = "%$searchTerm%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Display the results
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Last Name</th>
                <th>Contact Number</th>
                <th>District</th>
                <th>Actions</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["title"] . "</td>
                <td>" . $row["first_name"] . "</td>
                <td>" . $row["middle_name"] . "</td>
                <td>" . $row["last_name"] . "</td>
                <td>" . $row["contact_no"] . "</td>
                <td>" . $row["district"] . "</td>
                <td>
                    <a href='edit_customer.php?id=" . $row["id"] . "'>Edit</a> |
                    <a href='copy_customer.php?id=" . $row["id"] . "'>Copy</a> |
                    <a href='delete_customer.php?id=" . $row["id"] . "'>Delete</a>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No results found</p>";
}

$stmt->close();
$conn->close();
?>
