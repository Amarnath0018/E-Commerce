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
WHERE 
    od.is_active = 1
    AND od.payment_id IS NULL AND od.order_by = $user_id;
";
$result = $connect->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Product Search</title>
    <style>
        /* General reset and font */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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

        <h1 class="h1">Cart Details</h1>

        <main class="cards">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                        
                echo '<section class="card contact">
                <div class="icon">
                    <img src="' . htmlspecialchars($row['product_image']) . '">
                </div>
                <h3> ' . htmlspecialchars($row['product_name']) . '</h3>';
                echo "<p>" . htmlspecialchars($row['product_description']) . "</p>";
                echo "<p><strong>Price: $" . htmlspecialchars($row['rate']) . "</strong></p>";
                echo "<p>Quantity: " . htmlspecialchars($row['count']) . "</p>";

                echo '</section>';
                }
                $hasItems = true; 
            } else {
                echo "<p>No products found.</p>";
                $hasItems = false; 
            }
            ?>
        </main>

        <!-- "Buy Now" button -->
        <form method="POST" action="buy_now.php" style="margin-top: 20px;">
            <button type="submit" 
                style="padding: 10px 20px; background-color: <?= $hasItems ? '#3b945e' : '#ccc'; ?>; color: white; border: none; border-radius: 5px; cursor: <?= $hasItems ? 'pointer' : 'not-allowed'; ?>; font-size: 1rem;" 
                <?= $hasItems ? '' : 'disabled'; ?>>
                Buy Now
            </button>
        </form>
    </div>
</body>

</html>