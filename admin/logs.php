<?php

require_once 'config/db.php';

// Fetch user logs
$sql = "SELECT user_details.name, user_details.email, user_log.id, user_log.login_time, user_log.logout_time, user_log.pages_visited 
        FROM user_log
        INNER JOIN user_details ON user_log.user_id = user_details.id";
$result = $connect->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Logs</title>
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">User Logs</h1>
    <table>
        <thead>
            <tr>
                <th>Session Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Login Time</th>
                <th>Logout Time</th>
                <th>Pages Visited</th>
            </tr>
        </thead>
        <tbody>
            
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['login_time']; ?></td>
            <td><?php echo $row['logout_time'] ?? 'Still Active'; ?></td>
            <td><?php echo $row['pages_visited'] ?? 'Still Active'; ?></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
