<?php
include 'db_connect.php'; // Include your database connection file

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define variables and initialize with empty values
$title = $firstName = $middleName = $lastName = $contactNumber = $district = "";
$errors = [];

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $title = htmlspecialchars(trim($_POST['title']));
    $firstName = htmlspecialchars(trim($_POST['firstName']));
    $middleName = htmlspecialchars(trim($_POST['middleName']));
    $lastName = htmlspecialchars(trim($_POST['lastName']));
    $contactNumber = htmlspecialchars(trim($_POST['contactNumber']));
    $district = htmlspecialchars(trim($_POST['district']));

    if (empty($title) || empty($firstName) || empty($lastName) || empty($contactNumber) || empty($district)) {
        $errors[] = "All fields are required except Middle Name.";
    }

    if (!preg_match("/^[0-9]{10}$/", $contactNumber)) {
        $errors[] = "Contact Number must be exactly 10 digits.";
    }

    if (empty($errors)) {
        // Prepare and bind parameters for the SQL query
        $stmt = $conn->prepare("INSERT INTO customer (title, first_name, middle_name, last_name, contact_no, district) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            die('Prepare Error: ' . $conn->error);
        }

        $stmt->bind_param("ssssss", $title, $firstName, $middleName, $lastName, $contactNumber, $district);
        
        // Execute the query
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();

            // Output SweetAlert JavaScript to show alert and redirect
            echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>
                      <meta charset='UTF-8'>
                      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                      <title>Success</title>
                      <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css'>
                      <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                  </head>
                  <body>
                      <script>
                          Swal.fire({
                              title: 'Success!',
                              text: 'Customer registered successfully.',
                              icon: 'success',
                              confirmButtonText: 'OK'
                          }).then((result) => {
                              if (result.isConfirmed) {
                                  window.location.href = 'view_customers.php';
                              }
                          });
                      </script>
                  </body>
                  </html>";
            exit();
        } else {
            die('Execute Error: ' . $stmt->error);
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration</title>
    <link rel="stylesheet" href="../css/styles.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <div class="container">
        <h1>Register Customer</h1>
        <form action="register_customer.php" method="post">
            <label for="title">Title:</label>
            <select id="title" name="title" required>
                <option value="Mr" <?php echo ($title === 'Mr') ? 'selected' : ''; ?>>Mr</option>
                <option value="Mrs" <?php echo ($title === 'Mrs') ? 'selected' : ''; ?>>Mrs</option>
                <option value="Miss" <?php echo ($title === 'Miss') ? 'selected' : ''; ?>>Miss</option>
                <option value="Dr" <?php echo ($title === 'Dr') ? 'selected' : ''; ?>>Dr</option>
            </select>

            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8'); ?>" required>

            <label for="middleName">Middle Name:</label>
            <input type="text" id="middleName" name="middleName" value="<?php echo htmlspecialchars($middleName, ENT_QUOTES, 'UTF-8'); ?>">

            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName, ENT_QUOTES, 'UTF-8'); ?>" required>

            <label for="contactNumber">Contact Number:</label>
            <input type="tel" id="contactNumber" name="contactNumber" pattern="[0-9]{10}" value="<?php echo htmlspecialchars($contactNumber, ENT_QUOTES, 'UTF-8'); ?>" required>

            <label for="district">District:</label>
            <input type="text" id="district" name="district" value="<?php echo htmlspecialchars($district, ENT_QUOTES, 'UTF-8'); ?>" required>

            <button type="submit">Register</button>
        </form>
        <br>
        <a href="view_customers.php">View Customers</a>

        <?php if (!empty($errors)): ?>
            <div class="error-messages">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
