<?php
require_once '../config/db.php';

session_start();
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update query
    $sql = "UPDATE order_details 
            SET is_active = 0 
            WHERE order_by = ? 
              AND payment_id IS NULL";

    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Redirect or display a success message
        echo "<script>alert('Purchase successful!'); window.location.href='../routing_page.php';</script>";
    } else {
        echo "<script>alert('Error: Unable to process the purchase.'); window.location.href='../routing_page.php';</script>";
    }

    $stmt->close();
}

$connect->close();
?>
