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
        Add Product
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
                        <div class="mb-3">
                            <label for="product_name">Product Name:</label>
                            <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>" class="form-control" oninput="validateProductName()">
                            <span id="productNameError" class="error-message-product"></span>
                        </div>

                        <div class="mb-3">
                            <label for="product_image">Product Image:</label>
                            <input type="file" id="product_image" name="product_image" class="form-control" accept="image/*" oninput="validateProductImage()">
                            <span id="productImageError" class="error-message-product"></span>
                        </div>

                        <div class="mb-3">
                            <label for="product_description">Product Description:</label>
                            <input type="text" id="product_description" name="product_description" value="<?php echo htmlspecialchars($product_description); ?>" class="form-control" oninput="validateProductDescription()">
                            <span id="productDescriptionError" class="error-message-product"></span>
                        </div>

                        <div class="mb-3">
                            <label for="rate">Rate:</label>
                            <input type="number" id="rate" name="rate" value="<?php echo htmlspecialchars($rate); ?>" class="form-control" oninput="validateRate()">
                            <span id="rateError" class="error-message-product"></span>
                        </div>

                        <div class="mb-3">
                            <label for="specification">Specification:</label>
                            <input type="text" id="specification" name="specification" value="<?php echo htmlspecialchars($specification); ?>" class="form-control" oninput="validateSpecification()">
                            <span id="specificationError" class="error-message-product"></span>
                        </div>

                        <div class="mb-3">
                            <label for="stock_count">Stock Count:</label>
                            <input type="number" id="stock_count" name="stock_count" value="<?php echo htmlspecialchars($stock_count); ?>" class="form-control" oninput="validateStockCount()">
                            <span id="stockCountError" class="error-message-product"></span>
                        </div>

                        <div class="mb-3">
                            <label for="brand_name">Brand Name:</label>
                            <input type="text" id="brand_name" name="brand_name" value="<?php echo htmlspecialchars($brand_name); ?>" class="form-control" oninput="validateBrandName()">
                            <span id="brandNameError" class="error-message-product"></span>
                        </div>

                        <button type="submit" name="addProductForm" id="addProduct" class="btn btn-primary" disabled>Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Validation functions
        function validateProductName() {
            var product_name = document.getElementById('product_name').value;
            var errorElement = document.getElementById('productNameError');
            if (product_name === '') {
                errorElement.textContent = 'Product name is required.';
            } else {
                errorElement.textContent = '';
            }
            checkProductFormValidity();
        }

        function validateProductImage() {
            var product_image = document.getElementById('product_image').value;
            var errorElement = document.getElementById('productImageError');
            if (product_image === '') {
                errorElement.textContent = 'Product image is required.';
            } else {
                errorElement.textContent = '';
            }
            checkProductFormValidity();
        }

        function validateProductDescription() {
            var product_description = document.getElementById('product_description').value;
            var errorElement = document.getElementById('productDescriptionError');
            if (product_description === '') {
                errorElement.textContent = 'Product description is required.';
            } else {
                errorElement.textContent = '';
            }
            checkProductFormValidity();
        }

        function validateRate() {
            var rate = document.getElementById('rate').value;
            var errorElement = document.getElementById('rateError');
            if (rate < 0) {
                errorElement.textContent = 'Rate must be a positive number.';
            } else {
                errorElement.textContent = '';
            }
            checkProductFormValidity();
        }

        function validateSpecification() {
            var specification = document.getElementById('specification').value;
            var errorElement = document.getElementById('specificationError');
            if (specification === '') {
                errorElement.textContent = 'Specification is required.';
            } else {
                errorElement.textContent = '';
            }
            checkProductFormValidity();
        }

        function validateStockCount() {
            var stock_count = document.getElementById('stock_count').value;
            var errorElement = document.getElementById('stockCountError');
            if (stock_count <= 0) {
                errorElement.textContent = 'Stock count must be a positive number.';
            } else {
                errorElement.textContent = '';
            }
            checkProductFormValidity();
        }

        function validateBrandName() {
            var brand_name = document.getElementById('brand_name').value;
            var errorElement = document.getElementById('brandNameError');
            if (brand_name === '') {
                errorElement.textContent = 'Brand name is required.';
            } else {
                errorElement.textContent = '';
            }
            checkProductFormValidity();
        }
// Check if all fields are valid
function checkProductFormValidity() {
            var addProduct = document.getElementById('addProduct');
            var product_name = document.getElementById('product_name').value;
            var product_image = document.getElementById('product_image').value;
            var product_description = document.getElementById('product_description').value;
            var rate = document.getElementById('rate').value;
            var specification = document.getElementById('specification').value;
            var stock_count = document.getElementById('stock_count').value;
            var brand_name = document.getElementById('brand_name').value;
            var producterrors = document.querySelectorAll('.error-message-product');

            var isValid = true;
            for (var i = 0; i < producterrors.length; i++) {
                if (producterrors[i].textContent !== '') {
                    isValid = false;
                    break;
                }
            }

            // Enable the submit button only if all fields are valid
            if ((product_name !== '' && product_image !== '' && product_description !== '' && rate >= 0 && specification !== '' && stock_count > 0 && brand_name !== '') && isValid) {
                addProduct.disabled = false;
            } else {
                addProduct.disabled = true;
            }
        }

        // Ensure the submit button is disabled initially
        document.addEventListener('DOMContentLoaded', function() {
            checkProductFormValidity();
        });
    </script>
</body>
</html>