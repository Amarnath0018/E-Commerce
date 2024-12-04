<?php
require_once 'config/db.php';

// Initialize variables for form values and error messages
$product_name = $product_description = $rate = $specification = $stock_count = $brand_name = "";
$errors = [];
$form_success = false;

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
        $imagePath = 'uploads/' . $imageName;

        if (move_uploaded_file($imageTmpName, $imagePath)) {
            $sql = "INSERT INTO product_details (product_name, product_image, product_description, rate, specification, stock_count, brand_name) VALUES ('$product_name', '$imagePath', '$product_description', '$rate', '$specification', '$stock_count', '$brand_name')";
            $result = $connect->query($sql);
            echo "<script>window.location.href = 'routing_page.php';</script>";
            exit;
        } else {
            $errors['image'] = 'Error uploading image.';
        }
    } else {
        $errors['image'] = 'No image selected or error with the upload.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Container styling */
        .form-container {
            max-width: 500px;
            margin: auto auto;
            width: 100%;
            background-color: #f2f2f2;
            /* White background */
            border-radius: 15px;
            padding: 25px;
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
        <form method="POST" action="" enctype="multipart/form-data">
            <div>
                <label for="product_name">Product Name:</label>
                <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>" class="form-control" oninput="validateProductName()">
                <span id="productNameError" class="error-message"></span>
            </div>

            <div>
                <label for="product_image">Product Image:</label>
                <input type="file" id="product_image" name="product_image" class="form-control" accept="image/*" oninput="validateProductImage()">
                <span id="productImageError" class="error-message"></span>
            </div>

            <div>
                <label for="product_description">Product Description:</label>
                <input type="text" id="product_description" name="product_description" value="<?php echo htmlspecialchars($product_description); ?>" class="form-control" oninput="validateProductDescription()">
                <span id="productDescriptionError" class="error-message"></span>
            </div>

            <div>
                <label for="rate">Rate:</label>
                <input type="number" id="rate" name="rate" value="<?php echo htmlspecialchars($rate); ?>" class="form-control" oninput="validateRate()">
                <span id="rateError" class="error-message"></span>
            </div>

            <div>
                <label for="specification">Specification:</label>
                <input type="text" id="specification" name="specification" value="<?php echo htmlspecialchars($specification); ?>" class="form-control" oninput="validateSpecification()">
                <span id="specificationError" class="error-message"></span>
            </div>

            <div>
                <label for="stock_count">Stock Count:</label>
                <input type="number" id="stock_count" name="stock_count" value="<?php echo htmlspecialchars($stock_count); ?>" class="form-control" oninput="validateStockCount()">
                <span id="stockCountError" class="error-message"></span>
            </div>

            <div>
                <label for="brand_name">Brand Name:</label>
                <input type="text" id="brand_name" name="brand_name" value="<?php echo htmlspecialchars($brand_name); ?>" class="form-control" oninput="validateBrandName()">
                <span id="brandNameError" class="error-message"></span>
            </div>

            <button type="submit" name="addProductForm" class="btn-primary" id="submitBtn" disabled>Submit</button>
        </form>
    </div>

    <script>
        function validateProductName() {
            var product_name = document.getElementById('product_name').value;
            var errorElement = document.getElementById('productNameError');
            if (product_name.trim() === '') {
                errorElement.textContent = 'Product name is required.';
            } else {
                errorElement.textContent = '';
            }
            checkFormValidity();
        }

        function validateProductImage() {
            var product_image = document.getElementById('product_image').value;
            var errorElement = document.getElementById('productImageError');
            if (product_image.trim() === '') {
                errorElement.textContent = 'Product image is required.';
            } else {
                errorElement.textContent = '';
            }
            checkFormValidity();
        }

        function validateProductDescription() {
            var product_description = document.getElementById('product_description').value;
            var errorElement = document.getElementById('productDescriptionError');
            if (product_description.trim() === '') {
                errorElement.textContent = 'Product description is required.';
            } else {
                errorElement.textContent = '';
            }
            checkFormValidity();
        }

        function validateRate() {
            var rate = document.getElementById('rate').value;
            var errorElement = document.getElementById('rateError');
            if (rate === '' || rate < 0) {
                errorElement.textContent = 'Rate must be a positive number.';
            } else {
                errorElement.textContent = '';
            }
            checkFormValidity();
        }

        function validateSpecification() {
            var specification = document.getElementById('specification').value;
            var errorElement = document.getElementById('specificationError');
            if (specification.trim() === '') {
                errorElement.textContent = 'Specification is required.';
            } else {
                errorElement.textContent = '';
            }
            checkFormValidity();
        }

        function validateStockCount() {
            var stock_count = document.getElementById('stock_count').value;
            var errorElement = document.getElementById('stockCountError');
            if (stock_count === '' || stock_count <= 0) {
                errorElement.textContent = 'Stock count must be greater than 0.';
            } else {
                errorElement.textContent = '';
            }
            checkFormValidity();
        }

        function validateBrandName() {
            var brand_name = document.getElementById('brand_name').value;
            var errorElement = document.getElementById('brandNameError');
            if (brand_name.trim() === '') {
                errorElement.textContent = 'Brand name is required.';
            } else {
                errorElement.textContent = '';
            }
            checkFormValidity();
        }

        function checkFormValidity() {
            var isValid = Array.from(document.querySelectorAll('.error-message')).every(e => e.textContent === '');
            var isComplete = Array.from(document.querySelectorAll('.form-control')).every(e => e.value.trim() !== '');
            document.getElementById('submitBtn').disabled = !(isValid && isComplete);
        }
        v
    </script>
</body>

</html>