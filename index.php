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
    <title>Glassmorphism Login Page</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.2)),
                        url('https://source.unsplash.com/1600x900/?abstract,glass');
            background-size: cover;
            backdrop-filter: blur(8px);
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 100%;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 30px 40px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            width: 300px;
            text-align: center;
            color: #fff;
        }

        .glass-card h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            font-weight: bold;
            color: #ffffff;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.4);
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        /*label {
            font-size: 0.9rem;
            margin-bottom: 5px;
            display: inline-block;
        } */

        /* input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            outline: none;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 1rem;
        } */

        /* input::placeholder {
            color: #ddd;
        } */

        /* input:focus {
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        } */

        .btn {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: linear-gradient(135deg, #6a5af9, #8058f1);
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: linear-gradient(135deg, #8058f1, #6a5af9);
            transform: scale(1.05);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
        }


    </style>
</head>
<body>
    <div class="container">
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
    </div>
</body>
</html>

