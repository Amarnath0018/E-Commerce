<?php
require_once 'config/db.php';

// Initialize variables for form values and error messages
$name = $email = $password = $confirm_password = $phone = $role = "";
$errors = [];
$form_success = false;

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
        $sql = "INSERT INTO user_details (name, email, password, phone_number, role) VALUES ('$name', '$email', '$password', '$phone', '$role')";
        $result = $connect->query($sql);
        echo "<script>window.location.href = '?page=admin/users';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* General body styles */
        body {

            /* Container styling */
            .form-container {
                max-width: 500px;
                width: 100%;
                background-color: #f2f2f2;
                /* White background */
                border-radius: 15px;
                padding: 25px;
                margin: auto auto;
                box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
                /* Subtle shadow */
                transition: transform 0.3s ease-in-out;
            }

            .form-container:hover {
                transform: scale(1.02);
                /* Slight zoom on hover */
            }

            /* Label styles */
            label {
                font-weight: bold;
                color: #3b945e;
                /* Dark Green */
                margin-bottom: 5px;
                display: block;
            }

            /* Input styles */
            .form-control {
                border: 1px solid #57ba98;
                /* Medium Green border */
                border-radius: 5px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                /* Subtle shadow */
                padding: 10px;
                width: 100%;
                margin-bottom: 15px;
                background-color: #f2f2f2;
                /* White background */
                color: #182628;
                /* Black text */
                transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            }

            .form-control:focus {
                border-color: #3b945e;
                /* Dark Green border on focus */
                box-shadow: 0 4px 8px rgba(59, 148, 94, 0.5);
                /* Green glow */
                outline: none;
            }

            /* Button styles */
            .btn-primary {
                background-color: #65ccb8;
                /* Light Green background */
                color: #182628;
                /* Black text */
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                font-size: 1em;
                font-weight: bold;
                cursor: pointer;
                transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out;
                display: block;
                margin: auto;
            }

            .btn-primary:hover {
                background-color: #57ba98;
                /* Medium Green on hover */
                transform: translateY(-3px);
                /* Raised effect */
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                /* Elevated shadow */
            }

            .btn-primary:disabled {
                background-color: #ccc;
                /* Gray for disabled state */
                cursor: not-allowed;
            }

            /* Error message styles */
            .error-message {
                color: red;
                /* Red text for error messages */
                font-size: 14px;
                margin-top: -10px;
                margin-bottom: 10px;
            }

            /* File input hover effects */
            #product_image:hover {
                border-color: #65ccb8;
                /* Light Green border on hover */
            }

            /* Styling for the form on hover */
            .form-container:hover .btn-primary {
                background-color: #3b945e;
                /* Dark Green */
                color: #f2f2f2;
                /* White text */
            }
    </style>
</head>

<body>
    <div class="form-container">
        <form method="POST" action="">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" class="form-control" oninput="validateName()">
                <span id="nameError" class="error-message"></span>
            </div>

            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="form-control" oninput="validateEmail()">
                <span id="emailError" class="error-message"></span>
            </div>

            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" oninput="validatePassword()">
                <span id="passwordError" class="error-message"></span>
            </div>

            <div>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" oninput="validateConfirmPassword()">
                <span id="confirmPasswordError" class="error-message"></span>
            </div>

            <div>
                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" class="form-control" oninput="validatePhone()">
                <span id="phoneError" class="error-message"></span>
            </div>

            <div>
                <label for="role">Role:</label>
                <select id="role" name="role" class="form-control" oninput="validateRole()">
                    <option value="">Select Role</option>
                    <option value="user" <?php echo $role == 'user' ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo $role == 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
                <span id="roleError" class="error-message"></span>
            </div>

            <button type="submit" name="addUserForm" class="btn-primary" id="submitBtn" disabled>Submit</button>
        </form>
    </div>

    <script>
        // JavaScript validation functions
        function validateName() {
            var name = document.getElementById('name').value;
            var errorElement = document.getElementById('nameError');
            errorElement.textContent = name === '' ? 'Name is required.' : '';
            checkFormValidity();
        }

        function validateEmail() {
            var email = document.getElementById('email').value;
            var errorElement = document.getElementById('emailError');
            errorElement.textContent = (email === '' || !/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(email)) ? 'Valid email is required.' : '';
            checkFormValidity();
        }

        function validatePassword() {
            var password = document.getElementById('password').value;
            var errorElement = document.getElementById('passwordError');
            errorElement.textContent = password.length < 8 ? 'Password must be at least 8 characters.' : '';
            checkFormValidity();
        }

        function validateConfirmPassword() {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            var errorElement = document.getElementById('confirmPasswordError');
            errorElement.textContent = password !== confirmPassword ? 'Passwords do not match.' : '';
            checkFormValidity();
        }

        function validatePhone() {
            var phone = document.getElementById('phone').value;
            var errorElement = document.getElementById('phoneError');
            errorElement.textContent = !/^\d{10}$/.test(phone) ? 'Phone number must be 10 digits.' : '';
            checkFormValidity();
        }

        function validateRole() {
            var role = document.getElementById('role').value;
            var errorElement = document.getElementById('roleError');
            errorElement.textContent = (role !== 'user' && role !== 'admin') ? 'Invalid role selected.' : '';
            checkFormValidity();
        }

        function checkFormValidity() {
            var name = document.getElementById('name').value;
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            var phone = document.getElementById('phone').value;
            var role = document.getElementById('role').value;
            var errors = document.querySelectorAll('.error-message');

            var isValid = true;
            errors.forEach(function(error) {
                if (error.textContent !== '') isValid = false;
            });

            document.getElementById('submitBtn').disabled = !(
                name && email && password && confirmPassword && phone && role && isValid
            );
        }

        document.addEventListener('DOMContentLoaded', checkFormValidity);
    </script>
</body>

</html>