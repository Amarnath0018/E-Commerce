<?php
require_once 'config/db.php';

// Initialize variables for form values and error messages
$product_name = $product_description = $rate = $specification = $stock_count = $brand_name = "";
$errors = [];
$form_success = false;  // Initialize form_success flag

// Form handling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addProductForm'])) {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $rate = $_POST['rate'];
    $specification = $_POST['specification'];
    $stock_count = $_POST['stock_count'];
    $brand_name = $_POST['brand_name'];

    // Handle file upload
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $imageTmpName = $_FILES['product_image']['tmp_name'];
        $imageName = $_FILES['product_image']['name'];
        $imagePath = 'uploads/' . $imageName;  // Set the file path

        // Move the uploaded image to the 'uploads' directory
        if (move_uploaded_file($imageTmpName, $imagePath)) {
            // Insert form data into the database, including the image path (URL)
            $stmt = $connect->prepare("INSERT INTO product_details (product_name, product_image, product_description, rate, specification, stock_count, brand_name) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssis", $product_name, $imagePath, $product_description, $rate, $specification, $stock_count, $brand_name);

            if ($stmt->execute()) {
                $form_success = true; // Set flag indicating success
                // Clear form values after submission
                $product_name = $product_description = $rate = $specification = $stock_count = $brand_name = "";
                echo "<script>window.location.href = 'admin_dashboard.php';</script>";
                exit;
            } else {
                $form_success = false; // Set flag indicating failure
            }

            $stmt->close();
        } else {
            $form_success = false;
            $errors['image'] = 'Error uploading image.';
        }
    } else {
        $form_success = false;
        $errors['image'] = 'No image selected or error with the upload.';
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
        .error-message-product {
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
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productFormModal">
        Buy Now
    </button>
    
    <div class="modal fade" id="productFormModal" tabindex="-1" aria-labelledby="productFormModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productFormModalLabel">Product Registration</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <!-- Address Details -->
                        <div class="mb-3">
                            <label for="door_number" class="form-label">Door Number</label>
                            <input type="text" class="form-control" id="door_number" name="door_number" required>
                            <div class="error-message" id="doorNumberError"></div>
                        </div>
                <div class="mb-3">
                    <label for="street" class="form-label">Street</label>
                    <input type="text" class="form-control" id="street" name="street" required>
                    <div class="error-message" id="streetError"></div>
                </div>
                <div class="mb-3">
                    <label for="landmark" class="form-label">Landmark</label>
                    <input type="text" class="form-control" id="landmark" name="landmark">
                    <div class="error-message" id="landmarkError"></div>
                </div>
                <div class="mb-3">
                    <label for="area" class="form-label">Area</label>
                    <input type="text" class="form-control" id="area" name="area" required>
                    <div class="error-message" id="areaError"></div>
                </div>
                <div class="mb-3">
                    <label for="district" class="form-label">District</label>
                    <input type="text" class="form-control" id="district" name="district" required>
                    <div class="error-message" id="districtError"></div>
                </div>
                <div class="mb-3">
                    <label for="state" class="form-label">State</label>
                    <input type="text" class="form-control" id="state" name="state" required>
                    <div class="error-message" id="stateError"></div>
                </div>
                <div class="mb-3">
                    <label for="pincode" class="form-label">Pincode</label>
                    <input type="text" class="form-control" id="pincode" name="pincode" required>
                    <div class="error-message" id="pincodeError"></div>
                </div>

                <!-- Payment Method -->
                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <select class="form-select" id="payment_method" name="payment_method" onchange="togglePaymentFields()" required>
                        <option value="" selected disabled>Select Payment Method</option>
                        <option value="upi">UPI</option>
                        <option value="cod">Cash on Delivery</option>
                        <option value="card">Credit/Debit Card</option>
                    </select>
                    <div class="error-message" id="paymentMethodError"></div>
                </div>
                <div class="mb-3" id="upi_details" style="display: none;">
                    <label for="upi_id" class="form-label">UPI ID</label>
                    <input type="text" class="form-control" id="upi_id" name="upi_id">
                    <div class="error-message" id="upiError"></div>
                </div>
                <div class="mb-3" id="card_details" style="display: none;">
                    <label for="card_number" class="form-label">Card Number</label>
                    <input type="text" class="form-control" id="card_number" name="card_number">
                    <div class="error-message" id="cardNumberError"></div>
                    <label for="expiry_date" class="form-label mt-2">Expiry Date</label>
                    <input type="month" class="form-control" id="expiry_date" name="expiry_date">
                    <div class="error-message" id="expiryDateError"></div>
                    <label for="cvv" class="form-label mt-2">CVV</label>
                    <input type="text" class="form-control" id="cvv" name="cvv">
                    <div class="error-message" id="cvvError"></div>
                </div>



        <button type="submit" id="addProduct" class="btn btn-primary mt-3" disabled>Add Product</button>
    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Validation for Door Number
        function validateDoorNumber() {
            var doorNumber = document.getElementById('door_number').value;
            var errorElement = document.getElementById('doorNumberError');
            if (doorNumber.trim() === '') {
                errorElement.textContent = 'Door number is required.';
            } else {
                errorElement.textContent = '';
            }
            checkFormValidity();
        }

        // Validation for Street
        function validateStreet() {
            var street = document.getElementById('street').value;
            var errorElement = document.getElementById('streetError');
            if (street.trim() === '') {
                errorElement.textContent = 'Street is required.';
            } else {
                errorElement.textContent = '';
            }
            checkFormValidity();
        }

        // Validation for Landmark (optional)
        function validateLandmark() {
            var landmark = document.getElementById('landmark').value;
            var errorElement = document.getElementById('landmarkError');
            // No validation required for optional field
            errorElement.textContent = '';
            checkFormValidity();
        }

        // Validation for Area
        function validateArea() {
            var area = document.getElementById('area').value;
            var errorElement = document.getElementById('areaError');
            if (area.trim() === '') {
                errorElement.textContent = 'Area is required.';
            } else {
                errorElement.textContent = '';
            }
            checkFormValidity();
        }

        // Validation for District
        function validateDistrict() {
            var district = document.getElementById('district').value;
            var errorElement = document.getElementById('districtError');
            if (district.trim() === '') {
                errorElement.textContent = 'District is required.';
            } else {
                errorElement.textContent = '';
            }
            checkFormValidity();
        }

        // Validation for State
        function validateState() {
            var state = document.getElementById('state').value;
            var errorElement = document.getElementById('stateError');
            if (state.trim() === '') {
                errorElement.textContent = 'State is required.';
            } else {
                errorElement.textContent = '';
            }
            checkFormValidity();
        }

        // Validation for Pincode
        function validatePincode() {
            var pincode = document.getElementById('pincode').value;
            var errorElement = document.getElementById('pincodeError');
            if (pincode.trim() === '' || isNaN(pincode) || pincode.length !== 6) {
                errorElement.textContent = 'Pincode must be a 6-digit number.';
            } else {
                errorElement.textContent = '';
            }
            checkFormValidity();
        }

        // Validation for Payment Method
        function validatePaymentMethod() {
            var paymentMethod = document.getElementById('payment_method').value;
            var errorElement = document.getElementById('paymentMethodError');
            if (paymentMethod === '') {
                errorElement.textContent = 'Please select a payment method.';
            } else {
                errorElement.textContent = '';
            }

            if (paymentMethod === 'upi') {
                validateUPI();
            } else if (paymentMethod === 'card') {
                validateCardDetails();
            }
            checkFormValidity();
        }

        // Validation for UPI
        function validateUPI() {
            var upiId = document.getElementById('upi_id').value;
            var errorElement = document.getElementById('upiError');
            if (upiId.trim() === '') {
                errorElement.textContent = 'UPI ID is required.';
            } else {
                errorElement.textContent = '';
            }
        }

        // Validation for Card Details
        function validateCardDetails() {
            var cardNumber = document.getElementById('card_number').value;
            var expiryDate = document.getElementById('expiry_date').value;
            var cvv = document.getElementById('cvv').value;

            var cardError = document.getElementById('cardNumberError');
            var expiryError = document.getElementById('expiryDateError');
            var cvvError = document.getElementById('cvvError');

            if (cardNumber.trim() === '' || cardNumber.length !== 16 || isNaN(cardNumber)) {
                cardError.textContent = 'Card number must be a 16-digit number.';
            } else {
                cardError.textContent = '';
            }

            if (expiryDate.trim() === '') {
                expiryError.textContent = 'Expiry date is required.';
            } else {
                expiryError.textContent = '';
            }

            if (cvv.trim() === '' || cvv.length !== 3 || isNaN(cvv)) {
                cvvError.textContent = 'CVV must be a 3-digit number.';
            } else {
                cvvError.textContent = '';
            }
        }

        // Check if all fields are valid
        function checkFormValidity() {
            var addProduct = document.getElementById('addProduct');
            var errorElements = document.querySelectorAll('.error-message');
            var inputs = document.querySelectorAll('form input, form select');
            var isValid = true;

            // Check for any error messages
            errorElements.forEach(function (error) {
                if (error.textContent.trim() !== '') {
                    isValid = false;
                }
            });

            // Ensure all required inputs are filled
            inputs.forEach(function (input) {
                if (input.required && input.value.trim() === '') {
                    isValid = false;
                }
            });

            // Enable or disable the button based on overall validity
            addProduct.disabled = !isValid;
        }

        // Attach validation to events
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('door_number').addEventListener('blur', validateDoorNumber);
            document.getElementById('street').addEventListener('blur', validateStreet);
            document.getElementById('landmark').addEventListener('blur', validateLandmark);
            document.getElementById('area').addEventListener('blur', validateArea);
            document.getElementById('district').addEventListener('blur', validateDistrict);
            document.getElementById('state').addEventListener('blur', validateState);
            document.getElementById('pincode').addEventListener('blur', validatePincode);
            document.getElementById('payment_method').addEventListener('change', validatePaymentMethod);
            document.getElementById('upi_id').addEventListener('blur', validateUPI);
            document.getElementById('card_number').addEventListener('blur', validateCardDetails);
            document.getElementById('expiry_date').addEventListener('blur', validateCardDetails);
            document.getElementById('cvv').addEventListener('blur', validateCardDetails);

            checkFormValidity();
        });
        
        function togglePaymentFields() {
            var paymentMethod = document.getElementById("payment_method").value;
            var upiDetails = document.getElementById("upi_details");
            var cardDetails = document.getElementById("card_details");

            upiDetails.style.display = (paymentMethod === "upi") ? "block" : "none";
            cardDetails.style.display = (paymentMethod === "card") ? "block" : "none";
    }

    </script>
</body>
</html>