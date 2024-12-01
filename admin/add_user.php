<?php
require_once 'config/db.php';

// Initialize variables for form values and error messages
$name = $email = $password = $confirm_password = $phone = $role = "";
$errors = [];
$form_success = false;  // Initialize form_success flag

// Form handling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addUserForm'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    // Basic validation checks
    if (empty($name)) {
        $errors[] = 'Name is required.';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email is required.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }
    if (!preg_match('/^\d{10}$/', $phone)) {
        $errors[] = 'Phone number must be 10 digits.';
    }
    if ($role !== 'user' && $role !== 'admin') {
        $errors[] = 'Invalid role selected.';
    }

    // Proceed with insertion if no errors
    if (empty($errors)) {
        $stmt = $connect->prepare("INSERT INTO user_details (name, email, password, phone_number, role) VALUES (?, ?, ?, ?, ?)");
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
        $stmt->bind_param("sssss", $name, $email, $password, $phone, $role);

        if ($stmt->execute()) {
            $form_success = true;
            // Clear form values
            $name = $email = $password = $confirm_password = $phone = $role = "";
        } else {
            $form_success = false;
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .modal-content {
            padding: 20px;
            border-radius: 10px;
        }
        .form-control {
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: white !important;
            opacity: 1 !important;
            cursor: text;
        }
        .btn-primary {
            border-radius: 5px;
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .error-message {
            color: red;
            font-size: 14px;
        }
        .modal-header {
            background-color: #f8f9fa;
        }
        .modal-backdrop {
            z-index: -1050;
        }
    </style>
</head>
<body>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userFormModal">
        Add User
    </button>

    <div class="modal fade" id="userFormModal" tabindex="-1" aria-labelledby="userFormModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userFormModalLabel">User Registration</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>"class="form-control" oninput="validateName()">
                            <span id="nameError" class="error-message"></span>
                        </div>

                        <div class="mb-3">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="form-control" oninput="validateEmail()">
                            <span id="emailError" class="error-message"></span>
                        </div>

                        <div class="mb-3">
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" class="form-control" oninput="validatePassword()">
                            <span id="passwordError" class="error-message"></span>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password">Confirm Password:</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" oninput="validateConfirmPassword()">
                            <span id="confirmPasswordError" class="error-message"></span>
                        </div>

                        <div class="mb-3">
                            <label for="phone">Phone Number:</label>
                            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" class="form-control" oninput="validatePhone()">
                            <span id="phoneError" class="error-message"></span>
                        </div>

                        <div class="mb-3">
                            <label for="role">Role:</label>
                            <select id="role" name="role" class="form-control" oninput="validateRole()">
                                <option value="user" <?php echo $role == 'user' ? 'selected' : ''; ?>>User</option>
                                <option value="admin" <?php echo $role == 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                            <span id="roleError" class="error-message"></span>
                        </div>

                        <button type="submit" name="addUserForm" class="btn btn-primary" id="submitBtn" disabled>Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Validation functions for input events
        function validateName() {
            var name = document.getElementById('name').value;
            var errorElement = document.getElementById('nameError');
            if (name === '') {
                errorElement.textContent = 'Name is required.';
            } else {
                errorElement.textContent = '';
            }
            checkFormValidity();
        }

        function validateEmail() {
            var email = document.getElementById('email').value;
            var errorElement = document.getElementById('emailError');
            if (email === '' || !validateEmailFormat(email)) {
                errorElement.textContent = 'Valid email is required.';
            } else {
                errorElement.textContent = '';
            }
            checkFormValidity();
        }

        function validateEmailFormat(email) {
            var regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            return regex.test(email);
        }

        function validatePassword() {
            var password = document.getElementById('password').value;
            var errorElement = document.getElementById('passwordError');
            if (password.length < 8) {
                errorElement.textContent = 'Password must be at least 8 characters.';
            } else {
                errorElement.textContent = '';
            }
            checkFormValidity();
        }

        function validateConfirmPassword() {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            var errorElement = document.getElementById('confirmPasswordError');
            if (password !== confirmPassword) {
                errorElement.textContent = 'Passwords do not match.';
            } else {
                errorElement.textContent = '';
            }
            checkFormValidity();
        }

        function validatePhone() {
            var phone = document.getElementById('phone').value;
            var errorElement = document.getElementById('phoneError');
            var regex = /^\d{10}$/;
            if (!regex.test(phone)) {
                errorElement.textContent = 'Phone number must be 10 digits.';
            } else {
                errorElement.textContent = '';
            }
            checkFormValidity();
        }

        function validateRole() {
            var role = document.getElementById('role').value;
            var errorElement = document.getElementById('roleError');
            if (role !== 'user' && role !== 'admin') {
                errorElement.textContent = 'Invalid role selected.';
            } else {
                errorElement.textContent = '';
            }
            checkFormValidity();
        }

        // Check if all fields are valid
        function checkFormValidity() {
            var submitBtn = document.getElementById('submitBtn');
            var name = document.getElementById('name').value;
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            var phone = document.getElementById('phone').value;
            var role = document.getElementById('role').value;
            var errors = document.querySelectorAll('.error-message');
            
            var isValid = true;
            for (var i = 0; i < errors.length; i++) {
                if (errors[i].textContent !== '') {
                    isValid = false;
                    break;
                }
            }

            // Enable the submit button only if all fields are valid
            if (name !== '' && email !== '' && password.length >= 8 && password === confirmPassword && /^\d{10}$/.test(phone) && (role === 'user' || role === 'admin') && isValid) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }

        // Ensure the submit button is disabled initially
        document.addEventListener('DOMContentLoaded', function() {
            checkFormValidity();
        });
    </script>
</body>
</html>
