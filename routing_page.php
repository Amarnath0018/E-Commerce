<?php
session_start();
// Check if user is logged in, otherwise show login form
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div>
        <?php
            include 'header.php';
        ?>
    </div>
    <?php if ($role==='admin'): ?>
        <div>
        <?php
            include 'admin_dashboard.php';
        ?>
        </div>
    <?php elseif ($role==='user'): ?>
        <div>
        <?php
            include 'user_dashboard.php';
        ?>
        </div>
    <?php else: ?>
        <div>
        <?php
            include 'logout.php';
        ?>
        </div>
    <?php endif; ?>
    
    <div>
        <?php
            include 'footer.php';
        ?>
    </div>
</body>
</html>
