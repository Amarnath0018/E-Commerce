<?php

require_once 'config/db.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT 
    ud.name,
    pd.product_name,
    pd.product_image,
    pd.product_description,
    pd.rate,
    od.count
FROM 
    order_details od
JOIN 
    user_details ud ON od.order_by = ud.id
JOIN 
    product_details pd ON od.product_id = pd.id
JOIN 
    payment_details pt ON od.payment_id = pt.id
WHERE 
    od.is_active = TRUE
    AND od.payment_id IS NOT NULL AND od.order_by = '$user_id';
";
$result = $connect->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <div>
        <?php
            include 'payment.php';
        ?>
    </div>
<div class="grid-container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Only show Add to Cart button if the user is logged in as 'user'
                echo "<div class='card'>";
                echo "<img src='" . htmlspecialchars($row['product_image']) . "' alt='" . htmlspecialchars($row['product_name']) . "'>";
                echo "<h3>" . htmlspecialchars($row['product_name']) . "</h3>";
                echo "<p>" . htmlspecialchars($row['product_description']) . "</p>";
                echo "<p><strong>Price: $" . htmlspecialchars($row['rate']) . "</strong></p>";
                echo "<p>Quantity: " . htmlspecialchars($row['count']) . "</p>";
                
                // echo "<form method='POST' action='user/add_to_cart.php'>";
                // echo "<label for='quantity'>Quantity:</label>";
                // echo "<input type='number' id='quantity' name='quantity' min='1' max='" . $row['stock_count'] . "' value='1'>";
                // echo "<input type='hidden' name='product_id' value='" . $row['id'] . "'>";
                // echo "<button type='submit'>Add to Cart</button>";
                // echo "</form>";

                // echo "</div>";
        }
    } else {
        echo "<p>No products found.</p>";
    }
    ?>
</div>
</body>
</html>
