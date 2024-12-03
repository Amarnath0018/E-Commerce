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
</head>
<body>

    <div class="login">
         <img src="assets/img/login-bg.png" alt="image" class="login__bg">

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

               <a href="#" class="login__forgot">Forgot Password?</a>
            </div>

            <button type="submit" class="login__button">Login</button>

            <div class="login__register">
               Don't have an account? <a href="#">Register</a>
            </div>
         </form>
      </div>

    <!-- <div class="container">
        <div class="glass-card">
            <h1>Login</h1>
            <form action="login_handler.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn">Login</button>

                <?php if (!empty($error_message)): ?>
                    <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
                <?php endif; ?>

            </form>
        </div>
    </div> -->
</body>
</html>

