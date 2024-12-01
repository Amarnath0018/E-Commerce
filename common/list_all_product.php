<?php
require_once 'config/db.php';

// Fetch search query if provided
$searchQuery = isset($_POST['search']) ? $_POST['search'] : '';

// SQL query to fetch products
if ($searchQuery) {
    $sql = "SELECT * FROM product_details WHERE product_name LIKE '%$searchQuery%'";
} else {
    $sql = "SELECT * FROM product_details";
}

$result = $connect->query($sql);

$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Search</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }
    .search-form {
        margin-bottom: 20px;
    }
    .card {
        border: 1px solid #ddd;
        border-radius: 5px;
        margin: 10px;
        padding: 15px;
        text-align: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    img {
        max-width: 50%;
    }
    .grid-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr); /* 2 columns by default */
        gap: 20px;
    }

    /* Media query for smaller screens */
    @media (max-width: 768px) {
        .grid-container {
            grid-template-columns: 1fr; /* 1 column on smaller screens */
        }
    }

    /* Optional: Add some spacing to the cards for better responsiveness */
    @media (max-width: 480px) {
        .card {
            padding: 10px;
        }
    }
    </style>
</head>
<body>

<h1>Product List</h1>

<!-- Search Form -->
<form method="POST" class="search-form">
    <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($searchQuery); ?>">
    <button type="submit">Search</button>
</form>

<div class="grid-container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Only show Add to Cart button if the user is logged in as 'user'
            if ($userRole === 'user') {
                echo "<div class='card'>";
                echo "<img src='" . htmlspecialchars($row['product_image']) . "' alt='" . htmlspecialchars($row['product_name']) . "'>";
                echo "<h3>" . htmlspecialchars($row['product_name']) . "</h3>";
                echo "<p>" . htmlspecialchars($row['product_description']) . "</p>";
                echo "<p><strong>Price: $" . htmlspecialchars($row['rate']) . "</strong></p>";
                echo "<p>Stock Available: " . htmlspecialchars($row['stock_count']) . "</p>";
                
                // Add to Cart Section
                echo "<form method='POST' action='add_to_cart.php'>";
                echo "<label for='quantity'>Quantity:</label>";
                echo "<input type='number' id='quantity' name='quantity' min='1' max='" . $row['stock_count'] . "' value='1'>";
                echo "<input type='hidden' name='product_id' value='" . $row['id'] . "'>";
                echo "<button type='submit'>Add to Cart</button>";
                echo "</form>";

                echo "</div>";
            } else {
                // If user is not logged in as 'user', display the product without cart functionality
                echo "<div class='card'>";
                echo "<img src='" . htmlspecialchars($row['product_image']) . "' alt='" . htmlspecialchars($row['product_name']) . "'>";
                echo "<h3>" . htmlspecialchars($row['product_name']) . "</h3>";
                echo "<p>" . htmlspecialchars($row['product_description']) . "</p>";
                echo "<p><strong>Price: $" . htmlspecialchars($row['rate']) . "</strong></p>";
                echo "<p>Stock Available: " . htmlspecialchars($row['stock_count']) . "</p>";
                echo "</div>";
            }
        }
    } else {
        echo "<p>No products found.</p>";
    }
    ?>
</div>

</body>
</html>
