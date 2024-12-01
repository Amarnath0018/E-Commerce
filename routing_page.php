<?php
session_start();
// Check if user is logged in, otherwise show login form
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <!-- Header Section -->
    <div>
        <?php
            include 'header.php';  // Include header
        ?>
    </div>

    <!-- Role-based Content -->
    <?php if ($role === 'admin'): ?>
        <div>
            <?php
                include 'admin_dashboard.php';  // Include admin dashboard
            ?>
        </div>
    <?php elseif ($role === 'user'): ?>
        <div>
            <?php
                include 'user_dashboard.php';  // Include user dashboard
            ?>
        </div>
    <?php else: ?>
        <div>
            <?php
                include 'logout.php';  // Logout if not an admin or user
            ?>
        </div>
    <?php endif; ?>
    
    <!-- Footer Section -->
    <div>
        <?php
            include 'footer.php';  // Include footer
        ?>
    </div>
</body>
</html>
