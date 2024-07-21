<?php
include 'db_connect.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = $_POST['title'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName']; // Add this if your form includes a middle name field
    $lastName = $_POST['lastName'];
    $contactNumber = $_POST['contactNumber'];
    $district = $_POST['district'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO customer (title, first_name, middle_name, last_name, contact_no, district) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $firstName, $middleName, $lastName, $contactNumber, $district);

    // Execute statement
    if ($stmt->execute()) {
        echo "Customer registered successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
