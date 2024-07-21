<?php
include 'db_connect.php'; // Include the database connection

// Check if an ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Retrieve existing customer data
    $stmt = $conn->prepare("SELECT id, title, first_name, middle_name, last_name, contact_no, district FROM customer WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Customer not found.";
        exit;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
    exit;
}

// Update customer data if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $contactNumber = $_POST['contactNumber'];
    $district = $_POST['district'];

    $stmt = $conn->prepare("UPDATE customer SET title = ?, first_name = ?, middle_name = ?, last_name = ?, contact_no = ?, district = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $title, $firstName, $middleName, $lastName, $contactNumber, $district, $id);

    if ($stmt->execute()) {
        echo "Customer updated successfully!";
        header("Location: view_customers.php"); // Redirect to the customers list page
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>
<body>
    <h2>Edit Customer</h2>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label for="title">Title:</label>
        <select name="title" id="title" required>
            <option value="Mr" <?php echo ($row['title'] == 'Mr') ? 'selected' : ''; ?>>Mr</option>
            <option value="Mrs" <?php echo ($row['title'] == 'Mrs') ? 'selected' : ''; ?>>Mrs</option>
            <option value="Miss" <?php echo ($row['title'] == 'Miss') ? 'selected' : ''; ?>>Miss</option>
            <option value="Dr" <?php echo ($row['title'] == 'Dr') ? 'selected' : ''; ?>>Dr</option>
        </select><br>

        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName" value="<?php echo $row['first_name']; ?>" required><br>

        <label for="middleName">Middle Name:</label>
        <input type="text" id="middleName" name="middleName" value="<?php echo $row['middle_name']; ?>"><br>

        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName" value="<?php echo $row['last_name']; ?>" required><br>

        <label for="contactNumber">Contact Number:</label>
        <input type="text" id="contactNumber" name="contactNumber" value="<?php echo $row['contact_no']; ?>" required><br>

        <label for="district">District:</label>
        <input type="text" id="district" name="district" value="<?php echo $row['district']; ?>" required><br>

        <input type="submit" value="Update Customer">
    </form>
</body>
</html>
