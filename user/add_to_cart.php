<?php
    require_once '../config/db.php';
    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $product_id = $_POST['product_id'];
        $user_id = $_SESSION['user_id'];
        $count = $_POST['quantity'];
        $current_timestamp = date('Y-m-d H:i:s'); 

        $sql="INSERT INTO order_details (order_date, order_by, product_id, count) VALUES ('$current_timestamp', '$user_id', '$product_id', '$count')";
        $result = $connect->query($sql);

        $get_product = "SELECT stock_count FROM  product_details WHERE id = '$product_id'";
        $get_product_result = $connect->query($get_product)->fetch_assoc();
        $stock_count = $get_product_result['stock_count'];
        
        $updated_count = $stock_count-$count;
        $product_sql = "UPDATE product_details SET stock_count='$updated_count' WHERE id = '$product_id'";
        $update_result = $connect->query($product_sql);
        
        echo "<script>window.location.href = '../routing_page.php';</script>";
        exit;
    }
?>
