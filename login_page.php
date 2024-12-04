<?php
require_once 'config/db.php';

session_start();
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
// Clear the error message after displaying it
unset($_SESSION['error_message']);

// Initialize $errors as an empty array to avoid the undefined variable warning
$errors = array();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=============== REMIXICONS ===============-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="assets/css/login_styles.css">
    <style>

        .top-right {
            position: absolute;
            top: 20px;
            right: 50px;
            font-family: Arial, sans-serif;
            z-index: 5;
        }

        /* .top-right button {
            margin-left: 20px;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #007bff;
            border: none;
            cursor: pointer;
        } */

        /* .top-right button:hover {
            background-color: #0056b3;
        } */
    </style>
</head>

<body>
    <div class="login">

            <div class="top-right">
                <!-- Button to navigate to the Login page -->
                <button class="login__button" onclick="window.location.href='index.php'">Home</button>
            </div>
        <img src="assets/img/login-bg2.jpg" alt="image" class="login__bg">

        <form action="login_handler.php" method="POST" class="login__form">
            <h1 class="login__title">Login</h1>

            <div class="login__inputs">
                <div class="login__box">
                    <input type="email" name="username" placeholder="Email ID" required class="login__input">
                    <i class="ri-mail-fill"></i>
                </div>

                <div class="login__box">
                    <input type="password" name="password" placeholder="Password" required class="login__input">
                    <i class="ri-lock-2-fill"></i>
                </div>
            </div>

            <div class="login__check">
                <div class="login__check-box">
                    <input type="checkbox" class="login__check-input" id="user-check">
                    <label for="user-check" class="login__check-label">Remember me</label>
                </div>

            </div>

            <button type="submit" class="login__button">Login</button>

            <!-- <div class="login__register">
               Don't have an account? <a href="#">Register</a>
            </div> -->
        </form>
    </div>
</body>

</html>