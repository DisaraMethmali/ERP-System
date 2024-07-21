<?php
include 'db_connect.php'; // Include the database connection
echo '<link rel="stylesheet" type="text/css" href="../css/styles.css">';

$sql = "SELECT id, title, first_name, middle_name, last_name, contact_no, district FROM customer";
$result = $conn->query($sql);

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
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"]. "</td>
                <td>" . $row["title"]. "</td>
                <td>" . $row["first_name"]. "</td>
                <td>" . $row["middle_name"]. "</td>
                <td>" . $row["last_name"]. "</td>
                <td>" . $row["contact_no"]. "</td>
                <td>" . $row["district"]. "</td>
                <td>
                    <a href='edit_customer.php?id=" . $row["id"] . "'>Edit</a> |
                    <a href='copy_customer.php?id=" . $row["id"] . "'>Copy</a> |
                    <a href='delete_customer.php?id=" . $row["id"] . "'>Delete</a>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}
$conn->close();
?>
