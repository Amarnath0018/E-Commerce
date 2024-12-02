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
    <link rel="stylesheet" href="style.css">
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

        body {
            background: #F6F9FF;
            height: 100vh;
            width: 100%;

            display: flex;
            justify-content: center;
            align-items: center;

            color: #434343;

            font-size: 16px;
        }

        main.cards {
            display: flex;
            padding: 32px;
        }

        main.cards section.card {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            max-height: 468px;
            margin-left: 32px;
        }

        main.cards section.card:first-child {
            margin-left: 0;
        }

        main.cards section.card .icon {
            width: 64px;
            height: 64px;
        }

        main.cards section.card img {
            width: 100%;
        }

        main.cards section.card h3 {
            font-size: 100%;
            margin: 16px 0;
        }

        main.cards section.card span {
            font-weight: 300;
            max-width: 240px;
            font-size: 80%;
            margin-bottom: 16px;
        }

        main.cards section.card button {
            padding: 0.5rem 1rem;
            text-transform: uppercase;
            border-radius: 32px;
            border: 0;
            cursor: pointer;
            font-size: 80%;
            font-weight: 500;
            color: #fff;
            margin-bottom: 16px 0;
        }

        main.cards section.card.contact button {
            background: linear-gradient(to right, #9F66FF, #DFCBFF);
        }

        main.cards section.card.shop button {
            background: linear-gradient(to right, #3E8AFF, #BBDEFF);
        }

        main.cards section.card.about button {
            background: linear-gradient(to right, #FE5F8F, #FFC7D9);
        }

        main.cards section.card.contact {
            box-shadow: 20px 20px 50px -30px #DBC4FF;
        }

        main.cards section.card.shop {
            box-shadow: 20px 20px 50px -30px #AFD6FF;
        }

        main.cards section.card.about {
            box-shadow: 20px 20px 50px -30px #FFC1D5;
        }

        @media screen and (max-width: 720px) {
            main.cards {
                flex-direction: column;
            }

            main.cards section.card {
                margin-left: 0;
                margin-bottom: 32px;
            }

            main.cards section.card:last-child {
                margin-bottom: 0;
            }

            main.cards section.card button {
                font-size: 70%;
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

    <main class="cards">
        <section class="card contact">
            <div class="icon">
                <img src="assets/Chat.png" alt="Contact us.">
            </div>
            <h3>Contact us.</h3>
            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</span>
            <button>Learn More</button>
        </section>
        <section class="card shop">
            <div class="icon">
                <img src="assets/Bag.png" alt="Shop here.">
            </div>
            <h3>Shop here.</h3>
            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</span>
            <button>Learn More</button>
        </section>
        <section class="card about">
            <div class="icon">
                <img src="assets/Play.png" alt="About us.">
            </div>
            <h3>About us.</h3>
            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</span>
            <button>Learn More</button>
        </section>
    </main>

</body>

</html>