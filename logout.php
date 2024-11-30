<?php
    session_start();
    require_once 'config/db.php';
    
    // Get the user_id or other relevant session data
    $sessionId = $_SESSION['session_id'];
    $current_timestamp = date('Y-m-d H:i:s'); 
    $pagesVisited = implode(', ', $_SESSION['pagesVisited']);
    
    // Prepare and execute query to update the database (for example, marking user as logged out)
    $sql = "UPDATE user_log SET logout_time = '$current_timestamp', pages_visited='$pagesVisited' WHERE id = '$sessionId'";
    $result = $connect->query($sql);
    
    // Destroy the session to log the user out
    session_unset();      // Remove all session variables
    session_destroy();    // Destroy the session
    
    // Redirect to the index page
    header("Location: index.php");
    exit();
?>