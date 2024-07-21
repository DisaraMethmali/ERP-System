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
    $id = $_POST['id'] ?? '';
    $title = $_POST['title'] ?? '';
    $firstName = $_POST['firstName'] ?? '';
    $middleName = $_POST['middleName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $contactNumber = $_POST['contactNumber'] ?? '';
    $district = $_POST['district'] ?? '';

    // Check if required fields are set
    if (empty($id) || empty($title) || empty($firstName) || empty($lastName) || empty($contactNumber) || empty($district)) {
        $alertMessage = "Please fill all required fields.";
    } else {
        $stmt = $conn->prepare("UPDATE customer SET title = ?, first_name = ?, middle_name = ?, last_name = ?, contact_no = ?, district = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $title, $firstName, $middleName, $lastName, $contactNumber, $district, $id);

        if ($stmt->execute()) {
            $alertMessage = "Customer updated successfully!";
            $alertType = "success";
            $redirectUrl = "view_customers.php";
        } else {
            $alertMessage = "Error updating record: " . $stmt->error;
            $alertType = "danger";
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .invalid-feedback {
            display: block;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Customer</h2>

        <?php if (isset($alertMessage)): ?>
            <div class="alert alert-<?php echo $alertType ?? 'info'; ?> alert-dismissible fade show" role="alert">
                <?php echo $alertMessage; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php if (isset($redirectUrl)): ?>
                <meta http-equiv="refresh" content="2;url=<?php echo $redirectUrl; ?>">
            <?php endif; ?>
        <?php endif; ?>

        <form method="POST" action="" id="editCustomerForm">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Title:</label>
                    <select name="title" id="title" class="form-select" required>
                        <option value="Mr" <?php echo ($row['title'] == 'Mr') ? 'selected' : ''; ?>>Mr</option>
                        <option value="Mrs" <?php echo ($row['title'] == 'Mrs') ? 'selected' : ''; ?>>Mrs</option>
                        <option value="Miss" <?php echo ($row['title'] == 'Miss') ? 'selected' : ''; ?>>Miss</option>
                        <option value="Dr" <?php echo ($row['title'] == 'Dr') ? 'selected' : ''; ?>>Dr</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="district" class="form-label">District:</label>
                    <input type="text" id="district" name="district" class="form-control" value="<?php echo htmlspecialchars($row['district'], ENT_QUOTES, 'UTF-8'); ?>" min="1" max="25" required>
                    <div class="invalid-feedback">
                        Please enter a valid district (numbers only between 1 and 25).
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="firstName" class="form-label">First Name:</label>
                    <input type="text" id="firstName" name="firstName" class="form-control" value="<?php echo htmlspecialchars($row['first_name'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="middleName" class="form-label">Middle Name:</label>
                    <input type="text" id="middleName" name="middleName" class="form-control" value="<?php echo htmlspecialchars($row['middle_name'], ENT_QUOTES, 'UTF-8'); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="lastName" class="form-label">Last Name:</label>
                    <input type="text" id="lastName" name="lastName" class="form-control" value="<?php echo htmlspecialchars($row['last_name'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="contactNumber" class="form-label">Contact Number:</label>
                    <input type="text" id="contactNumber" name="contactNumber" class="form-control" value="<?php echo htmlspecialchars($row['contact_no'], ENT_QUOTES, 'UTF-8'); ?>" maxlength="10" pattern="\d{10}" required>
                    <div class="invalid-feedback">
                        Please enter a valid 10-digit contact number.
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Customer</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Restrict district field to numbers only and add validation
        const districtInput = document.getElementById('district');

        districtInput.addEventListener('input', function(event) {
            // Allow only digits
            this.value = this.value.replace(/[^0-9]/g, '');

            // Ensure the value is between 1 and 25
            const value = parseInt(this.value, 10);
            if (value > 25) {
                this.value = 25; // Set the value to 25
                this.disabled = true; // Disable the input field
                // Use Bootstrap alert
                document.getElementById('alertContainer').innerHTML = `
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        District cannot be more than 25. The input field is now disabled.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
            } else if (value < 1) {
                this.value = 1;
            }
        });

        // Validate form before submission
        document.getElementById('editCustomerForm').addEventListener('submit', function(event) {
            var districtValue = parseInt(districtInput.value, 10);
            var contactNumberInput = document.getElementById('contactNumber');
            var firstNameInput = document.getElementById('firstName');
            var middleNameInput = document.getElementById('middleName');
            var lastNameInput = document.getElementById('lastName');

            // Validate district input
            if (isNaN(districtValue) || districtValue < 1 || districtValue > 25) {
                event.preventDefault();
                districtInput.classList.add('is-invalid');
                document.getElementById('alertContainer').innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        District must be between 1 and 25.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                return;
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
                return;
            } else {
                contactNumberInput.classList.remove('is-invalid');
            }

            // Validate name inputs
            function validateName(input, name) {
                if (!/^[A-Za-z\s]+$/.test(input.value)) {
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

            if (!validateName(firstNameInput, 'First Name') || !validateName(middleNameInput, 'Middle Name') || !validateName(lastNameInput, 'Last Name')) {
                return;
            }
        });
    </script>
</body>

</html>
