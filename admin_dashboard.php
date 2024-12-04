<?php
// Check if user is logged in, otherwise show login form
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
$page_number = isset($_GET['page']) ? intval($_GET['page']) : 1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple PHP Routing</title>

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            /* Ensure full height for 100vh calculation */
        }

        .container {
            display: flex;
            width: 100%;
            height: 100vh;
            /* Set the desired height */
        }

        .navigation-div {
            flex-shrink: 0;
            /* Prevent shrinking */
            flex-grow: 0;
            /* Prevent growing */
            background-color: lightblue;
            width: 200px;
            /* Fixed width for navigation */
            overflow: auto;
            /* Prevent overflow */
        }

        .content-div {
            display: flex;
            flex-direction: column;
            /* Arrange header, router, and footer vertically */
            flex-grow: 1;
            /* Allow this container to grow and take remaining space */
            height: 100%;
            /* Full height */
            overflow: hidden;
            /* Prevent overflow from child elements */
        }

        .header-div {
            height: 50px;
            /* Fixed height for header */
            background-color: lightcoral;
            flex-shrink: 0;
            /* Prevent shrinking */
            flex-grow: 0;
            /* Prevent growing */
        }

        .router-div {
            flex-grow: 1;
            /* Take up remaining vertical space */
            overflow-y: auto;
            /* Make this area scrollable */
            background-color: lightgoldenrodyellow;
        }

        .footer-div {
            height: 50px;
            /* Fixed height for footer */
            background-color: lightseagreen;
            flex-shrink: 0;
            /* Prevent shrinking */
            flex-grow: 0;
            /* Prevent growing */
        }


        .vertical-nav {
            display: flex;
            flex-direction: column;
            width: 200px;
            /* Optional: Set width for the nav */
            border: 1px solid #ccc;
            /* Optional: Add border for styling */
            padding: 10px;
            /* Optional: Add padding */
        }

        .vertical-nav a {
            margin: 5px 0;
            /* Space between links */
            text-decoration: none;
            color: #000;
            padding: 5px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .vertical-nav a:hover {
            background-color: #f0f0f0;
            /* Optional: Hover effect */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="navigation-div">
            <nav class="vertical-nav">
                <a href="?page=admin/users">User</a>
                <a href="?page=common/list_all_product">Products</a>
                <a href="?module=admin/logs">User Logs</a>
                <a href="?module=admin/all_order_details">Product Details</a>
            </nav>
        </div>
        <div class="content-div">
            <div class="header-div">
                <?php
                include 'header.php';
                ?>
            </div>
            <div class="router-div">
                <main>
                    <?php
                    // Include pages and modules based on query parameters
                    if (isset($_GET['page'])) {
                        $page = $_GET['page'];
                        $pageFile = "$page.php";

                        if (file_exists($pageFile)) {
                            include $pageFile;
                        } else {
                            echo "Page not found.";
                        }
                    } elseif (isset($_GET['module'])) {
                        $module = $_GET['module'];
                        $moduleFile = "$module.php";

                        if (file_exists($moduleFile)) {
                            include $moduleFile;
                        } else {
                            echo "Module not found.";
                        }
                    } else {
                        echo "Default page"; // Default page
                    }
                    ?>
                </main>
            </div>
            <div class="footer-div">
                <?php
                include 'footer.php';
                ?>
            </div>
        </div>
    </div>

</body>

</html>