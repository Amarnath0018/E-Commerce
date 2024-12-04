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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/login_styles.css">
    <title>Product Search</title>
    <style>
        .search-form {
            margin-bottom: 20px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        .login_button {
            width: 100%;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: var(--white-color);
            border-radius: 4rem;
            font-weight: 500;
            cursor: pointer;
        }

        .header {
            background-color: #3b945e;
            height: 8vh;
            color: #f2f2f2;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.2);
        }

        .top-right {
            position: absolute;
            top: 12px;
            right: 10px;
            margin-right: 100px;
            font-family: Arial, sans-serif;
        }

        .top-right button {
            margin-left: 20px;
            padding: 10px 20px;
            font-size: 16px;
            color: #3b945e;
            background-color: white;
            border: none;
            cursor: pointer;
        }

        .top-right button:hover {
            background-color: #182628;
        }

        /* Container for the product listing */
        .divbody {
            width: 100%;
            text-align: center;
        }

        /* Header styling */
        .h1 {
            color: #65ccb8;
            /* Light Green */
            margin-top: 20px;
            margin-bottom: 20px;
        }

        /* Search form styling */
        .search-form {
            margin-bottom: 30px;
        }

        .search-form input {
            width: 300px;
            padding: 10px;
            border: 1px solid #57ba98;
            /* Medium Green */
            border-radius: 5px;
            background-color: #f2f2f2;
            /* White */
            color: #182628;
            /* Black */
            transition: all 0.3s ease;
        }

        .search-form input:focus {
            border-color: #3b945e;
            /* Dark Green */
            outline: none;
            box-shadow: 0 4px 8px rgba(59, 148, 94, 0.5);
            /* Subtle green glow */
        }

        .search-form button {
            padding: 10px 15px;
            margin-left: 10px;
            background-color: #65ccb8;
            /* Light Green */
            border: none;
            border-radius: 5px;
            color: #182628;
            /* Black */
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .search-form button:hover {
            background-color: #3b945e;
            /* Dark Green */
            color: #f2f2f2;
            /* White */
            transform: translateY(-2px);
            /* Lift effect */
        }

        /* Main card container */
        main.cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        /* Individual card styling */
        section.card {
            background-color: #f2f2f2;
            /* White */
            color: #182628;
            /* Black */
            border-radius: 15px;
            width: 350px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            /* Subtle shadow */
            transition: all 0.3s ease;
        }

        section.card:hover {
            transform: translateY(-5px);
            /* Lift effect */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
            /* Elevated shadow */
        }

        section.card .icon {
            display: flex;
            justify-content: center;
            /* Horizontally center */
            align-items: center;
            height: 250px;
        }

        /* Product image styling */
        section.card .icon img {
            max-width: 250px;
            max-height: 250px;
            width: auto;
            /* Maintains aspect ratio */
            height: auto;
            /* Maintains aspect ratio */
            object-fit: contain;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Subtle shadow */
        }

        /* Product title */
        section.card h3 {
            font-size: 1.2rem;
            color: #3b945e;
            /* Dark Green */
            margin-top: 10px;
            margin-bottom: 10px;
        }

        /* Product description */
        section.card span {
            font-size: 0.9rem;
            color: #434343;
            /* Grayish text */
            display: block;
            margin-bottom: 15px;
        }

        /* Product price and stock info */
        section.card p {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: #182628;
            /* Black */
        }

        /* Button styles in card */
        section.card button {
            padding: 10px 20px;
            background: linear-gradient(to right, #65ccb8, #57ba98);
            /* Gradient green */
            border: none;
            border-radius: 25px;
            color: #f2f2f2;
            /* White */
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        section.card button:hover {
            background: linear-gradient(to right, #57ba98, #3b945e);
            /* Darker gradient on hover */
            transform: scale(1.05);
            /* Slight zoom */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            /* Elevated shadow */
        }

        /* Media query for responsive design */
        @media screen and (max-width: 720px) {
            main.cards {
                flex-direction: column;
                align-items: center;
            }

            section.card {
                width: 90%;
                /* Full width for small screens */
            }

            .search-form input {
                width: 80%;
                /* Adjust search input width */
            }
        }
    </style>
</head>

<body>
    <div class="divbody">
        <div class="header">
            <div class="top-right">
                <!-- Button to navigate to the Login page -->
                <button class="login_button" onclick="window.location.href='login_page.php'">Login</button>
            </div>
        </div>
        <h1 class="h1">Product List</h1>

        <!-- Search Form -->
        <form method="POST" class="search-form">
            <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit">Search</button>
        </form>

        <main class="cards">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($userRole === 'user') {
                        echo '<section class="card contact">
                            <div class="icon">
                                <img src="' . htmlspecialchars($row['product_image']) . '">
                            </div>
                            <h3> ' . htmlspecialchars($row['product_name']) . '</h3>';
                        echo "<span>" . htmlspecialchars($row['product_description']) . "</span>";
                        echo "<p><strong>Price: $" . htmlspecialchars($row['rate']) . "</strong></p>";
                        echo "<p>Stock Available: " . htmlspecialchars($row['stock_count']) . "</p>";

                        // Add to Cart Section
                        echo "<form method='POST' action='user/add_to_cart.php'>";
                        echo "<label for='quantity'>Quantity:</label>";
                        echo "<input type='number' id='quantity' name='quantity' min='1' max='" . $row['stock_count'] . "' value='1'>";
                        echo "<input type='hidden' name='product_id' value='" . $row['id'] . "'>";
                        echo "<button type='submit'>Add to Cart</button>";
                        echo "</form>";

                        echo '</section>';
                    } else {
                        echo
                        '<section class="card contact">
                            <div class="icon">
                                <img src="' . htmlspecialchars($row['product_image']) . '">
                            </div>
                            <h3> ' . htmlspecialchars($row['product_name']) . '</h3>';
                        echo "<span>" . htmlspecialchars($row['product_description']) . "</span>";
                        echo "<p><strong>Price: $" . htmlspecialchars($row['rate']) . "</strong></p>";
                        echo "<p>Stock Available: " . htmlspecialchars($row['stock_count']) . "</p>";
                        echo '</section>';
                    }
                }
            } else {
                echo "<p>No products found.</p>";
            }
            ?>
        </main>
    </div>
</body>

</html>