<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Routing App</title>
    <link rel="stylesheet" href="style.css">

    <style>
        /* Reset some basic styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        /* Container for the main content */
        .container {
            display: flex;
            min-height: 88vh;
            flex-direction: row;
        }

        /* Sidebar Navigation */
        .sidebar {
            width: 15vw;
            background-color: #f2f2f2;
            padding: 15px;
            color: #f2f2f2;
        }

        /* Content Area */
        .content {
            flex-grow: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: normal;
            min-height: 88vh;
            background-color: #f2f2f2;
            width: 85vw;
        }

        /* Header Styles inside content */
        header {
            background-color: #4CAF50;
            color: white;
            padding: 10px 0;
            text-align: center;
        }

        /* Footer Styles inside content */
        footer {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 10px;
        }

    ul {
      list-style: none;
      padding-left: 0;
    }

    li {
      background-color: #3b945e;
      list-style: none ;
      border: 1px solid #ddd;
      border-radius: 5px;
      padding: 10px;
      margin-bottom: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    li:hover {
      transform: translateX(10px);
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    }

    li > ul {
      margin-top: 10px;
      padding-left: 20px;
      list-style-type: circle;
    }

    li > ul > li {
      background-color: #f9f9f9;
      border-color: #e0e0e0;
      padding: 8px;
      box-shadow: none;
      margin-bottom: 8px;
    }

    li > ul > li:hover {
      background-color: #e6f7ff;
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    }

    .root-list > li {
      font-size: 18px;
    }

    .nested-list > li {
      font-size: 16px;
    }
    a{
        color: inherit;
        text-decoration: none;
    }

    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="sidebar">
            <?php include 'navigation.php'; ?>
        </div>

        <div class="content">

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
            } else {
                include 'common/list_all_product.php';
            }
            ?>

        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>