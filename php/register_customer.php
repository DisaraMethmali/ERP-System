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
    $title = isset($_POST['title']) ? htmlspecialchars(trim($_POST['title'])) : '';
    $firstName = isset($_POST['firstName']) ? htmlspecialchars(trim($_POST['firstName'])) : '';
    $middleName = isset($_POST['middleName']) ? htmlspecialchars(trim($_POST['middleName'])) : '';
    $lastName = isset($_POST['lastName']) ? htmlspecialchars(trim($_POST['lastName'])) : '';
    $contactNumber = isset($_POST['contactNumber']) ? htmlspecialchars(trim($_POST['contactNumber'])) : '';
    $district = isset($_POST['district']) ? htmlspecialchars(trim($_POST['district'])) : '';

    if (empty($title) || empty($firstName) || empty($lastName) || empty($contactNumber) || empty($district)) {
        $errors[] = "All fields are required except Middle Name.";
    }

    if (!preg_match("/^[0-9]{10}$/", $contactNumber)) {
        $errors[] = "Contact Number must be exactly 10 digits.";
    }

    if (!preg_match("/^[a-zA-Z]+$/", $firstName)) {
        $errors[] = "First Name must contain only letters.";
    }

    if (!preg_match("/^[a-zA-Z]*$/", $middleName)) {
        $errors[] = "Middle Name must contain only letters.";
    }

    if (!preg_match("/^[a-zA-Z]+$/", $lastName)) {
        $errors[] = "Last Name must contain only letters.";
    }

    if (!preg_match("/^[1-9][0-9]*$/", $district)) {
        $errors[] = "District must be a number.";
    } else if ((int)$district < 1 || (int)$district > 25) {
        $errors[] = "District must be between 1 and 25.";
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

            // Redirect with Bootstrap alert
            echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>
                      <meta charset='UTF-8'>
                      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                      <title>Success</title>
                      <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css'>
                  </head>
                  <body>
                      <div class='container mt-5'>
                          <div class='alert alert-success alert-dismissible fade show' role='alert'>
                              Customer registered successfully.
                              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                          </div>
                          <a href='view_customers.php' class='btn btn-primary'>View Customers</a>
                      </div>
                      <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        .form-control.is-valid {
            border-color: #198754;
        }
    </style>
</head>
<body>
<div id="alertContainer"></div>

    <div class="container mt-5">
        <h1>Register Customer</h1>
        <form action="register_customer.php" method="post" id="registrationForm">

            <div class="mb-3">
                <label for="title" class="form-label">Title:</label>
                <select id="title" name="title" class="form-select" required>
                    <option value="">Select Title</option>
                    <option value="Mr" <?php echo ($title === 'Mr') ? 'selected' : ''; ?>>Mr</option>
                    <option value="Mrs" <?php echo ($title === 'Mrs') ? 'selected' : ''; ?>>Mrs</option>
                    <option value="Miss" <?php echo ($title === 'Miss') ? 'selected' : ''; ?>>Miss</option>
                    <option value="Dr" <?php echo ($title === 'Dr') ? 'selected' : ''; ?>>Dr</option>
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="firstName" class="form-label">First Name:</label>
                    <input type="text" id="firstName" name="firstName" class="form-control" value="<?php echo htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="middleName" class="form-label">Middle Name:</label>
                    <input type="text" id="middleName" name="middleName" class="form-control" value="<?php echo htmlspecialchars($middleName, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="lastName" class="form-label">Last Name:</label>
                <input type="text" id="lastName" name="lastName" class="form-control" value="<?php echo htmlspecialchars($lastName, ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="mb-3">
                <label for="contactNumber" class="form-label">Contact Number:</label>
                <input type="text" id="contactNumber" name="contactNumber" class="form-control" value="<?php echo htmlspecialchars($contactNumber, ENT_QUOTES, 'UTF-8'); ?>" maxlength="10" pattern="\d{10}" required>
                <div class="invalid-feedback">
                    Please enter a valid 10-digit contact number.
                </div>
            </div>

            <div class="mb-3">
                <label for="district" class="form-label">District:</label>
                <input type="text" id="district" name="district" class="form-control" value="<?php echo htmlspecialchars($district, ENT_QUOTES, 'UTF-8'); ?>" required>
                <div class="invalid-feedback">
                    Please enter a valid district number between 1 and 25.
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <br>
        <a href="view_customers.php" class="btn btn-secondary">View Customers</a>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger mt-3">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
   
    <script>
    // Restrict district field to numbers only and add validation
    const districtInput = document.getElementById('district');
    const contactNumberInput = document.getElementById('contactNumber');
    const nameFields = ['firstName', 'middleName', 'lastName'];

    // Restrict district field to numbers only
    districtInput.addEventListener('input', function(event) {
        // Allow only digits
        this.value = this.value.replace(/[^0-9]/g, '');

        // Ensure the value doesn't exceed 25
        if (this.value > 25) {
            this.value = 25;
        }
    });

    // Restrict contact number to exactly 10 digits
    contactNumberInput.addEventListener('input', function(event) {
        // Allow only digits
        this.value = this.value.replace(/[^0-9]/g, '');

        // Ensure the value is exactly 10 digits
        if (this.value.length > 10) {
            this.value = this.value.slice(0, 10);
        }
    });

    // Restrict name fields to letters only
    nameFields.forEach(fieldId => {
        const input = document.getElementById(fieldId);
        input.addEventListener('input', function(event) {
            this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
        });
    });

    // Validate form before submission
    document.getElementById('registrationForm').addEventListener('submit', function(event) {
        let isValid = true;

        // Validate district input
        const districtValue = parseInt(districtInput.value, 10);
        if (isNaN(districtValue) || districtValue < 1 || districtValue > 25) {
            event.preventDefault();
            districtInput.classList.add('is-invalid');
            document.getElementById('alertContainer').innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    District must be between 1 and 25.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            isValid = false;
        } else {
            districtInput.classList.remove('is-invalid');
        }

        // Validate contact number input
        if (contactNumberInput.value.length !== 10 || isNaN(contactNumberInput.value)) {
            event.preventDefault();
            contactNumberInput.classList.add('is-invalid');
            contactNumberInput.focus();
            document.getElementById('alertContainer').innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Contact number must be exactly 10 digits.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            isValid = false;
        } else {
            contactNumberInput.classList.remove('is-invalid');
        }

        // Validate name inputs
        function validateName(input, name) {
            if (!/^[A-Za-z\s]*$/.test(input.value)) {
                event.preventDefault();
                input.classList.add('is-invalid');
                document.getElementById('alertContainer').innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ${name} cannot contain numbers or symbols.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                return false;
            } else {
                input.classList.remove('is-invalid');
            }
            return true;
        }

        if (!validateName(document.getElementById('firstName'), 'First Name') ||
            !validateName(document.getElementById('middleName'), 'Middle Name') ||
            !validateName(document.getElementById('lastName'), 'Last Name')) {
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
    });
    </script>
</body>
</html>
