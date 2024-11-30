<?php

// If the user is logged in, show the logout option
if (isset($_SESSION['user'])) {
    // Log out logic
    if (isset($_GET['logout'])) {
        session_destroy();
        header("Location: login.php"); // Redirect to login page after logging out
        exit;
    }
}

// Track pages visited
$current_page = basename($_SERVER['PHP_SELF']);
if (!in_array($current_page, $_SESSION['pagesVisited'])) {
    $_SESSION['pagesVisited'][] = $current_page;
}
$pagesVisited = implode(', ', $_SESSION['pagesVisited']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Add Font Awesome CDN for icons -->
    <link href="assets/icons/font awesome/css/solid.css" rel="stylesheet">
    <style>
        /* Basic styling for the header */
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 24px;
        }
        .logout {
            cursor: pointer;
            font-size: 20px;
            display: flex;
            align-items: center;
        }
        .logout i {
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">My Website</div>
            <div class="logout">
                <button onclick="window.location.href='logout.php'">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </div>
        </div>
    </div>
</body>
</html>
