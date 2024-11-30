<?php

require_once 'config/db.php';

session_start();
// Replace with actual database handling logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

     // Validate user credentials
     $sql="SELECT id, name, email, password, role FROM user_details WHERE name = '$username'";
     $result = $connect->query($sql);
 
     if ($result->num_rows === 1) {
         $user = $result->fetch_assoc();

         if($user['password']===$password){

            // Store user information in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'];
            $_SESSION['role'] = $user['role'];
 
            // Log login event in `user_log` table
            $userId = $user['id'];
            $logSql = "INSERT INTO user_log (user_id) VALUES ('$userId')";
            $logResult = $connect->query($logSql);

            
            //  Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit;
 
         }else{
            $_SESSION['error_message'] = 'Wrong Password';
            header('Location: index.php'); // Redirect back to login page
         }
 
     } else {
        $_SESSION['error_message'] = 'Username not found';
        header('Location: index.php'); // Redirect back to login page
     }
}
?>
