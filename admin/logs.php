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
    <title>User Details</title>
    <style>
        * {
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        body {
            font-family: Helvetica;
            -webkit-font-smoothing: antialiased;
            background: rgba(71, 147, 227, 1);
        }

        h2 {
            text-align: center;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: white;
            padding: 30px 0;
        }

        /* Table Styles */

        .table-wrapper {
            margin: 10px 70px 70px;
            box-shadow: 0px 35px 50px rgba(0, 0, 0, 0.2);
        }

        .fl-table {
            border-radius: 5px;
            font-size: 12px;
            font-weight: normal;
            border: none;
            border-collapse: collapse;
            width: 100%;
            max-width: 100%;
            white-space: nowrap;
            background-color: white;
        }

        .fl-table td,
        .fl-table th {
            text-align: center;
            padding: 8px;
        }

        .fl-table td {
            border-right: 1px solid #f8f8f8;
            font-size: 12px;
        }

        .fl-table thead th {
            color: #ffffff;
            background: #4e73df !important;
        }


        .fl-table thead th:nth-child(odd) {
            color: #000000 !important;
            background: #f2f2ff !important;
        }

        .fl-table tr:nth-child(even) {
            background: #F8F8F8;
        }

        /* Responsive */

        @media (max-width: 767px) {
            .fl-table {
                display: block;
                width: 100%;
            }

            .table-wrapper:before {
                content: "Scroll horizontally >";
                display: block;
                text-align: right;
                font-size: 11px;
                color: white;
                padding: 0 0 10px;
            }

            .fl-table thead,
            .fl-table tbody,
            .fl-table thead th {
                display: block;
            }

            .fl-table thead th:last-child {
                border-bottom: none;
            }

            .fl-table thead {
                float: left;
            }

            .fl-table tbody {
                width: auto;
                position: relative;
                overflow-x: auto;
            }

            .fl-table td,
            .fl-table th {
                padding: 20px .625em .625em .625em;
                height: 60px;
                vertical-align: middle;
                box-sizing: border-box;
                overflow-x: hidden;
                overflow-y: auto;
                width: 120px;
                font-size: 13px;
                text-overflow: ellipsis;
            }

            .fl-table thead th {
                text-align: left;
                border-bottom: 1px solid #f7f7f9;
            }

            .fl-table tbody tr {
                display: table-cell;
            }

            .fl-table tbody tr:nth-child(odd) {
                background: none;
            }

            .fl-table tr:nth-child(even) {
                background: transparent;
            }

            .fl-table tr td:nth-child(odd) {
                background: #F8F8F8;
                border-right: 1px solid #E6E4E4;
            }

            .fl-table tr td:nth-child(even) {
                border-right: 1px solid #E6E4E4;
            }

            .fl-table tbody td {
                display: block;
                text-align: center;
            }
        }
    </style>
    <script>
        const logsPerPage = 20;
        let currentLogPage = 1;

        function changeLogPage(logDirection) {
            currentLogPage += logDirection;
            displayLogTable();
        }

        function displayLogTable() {
            const logTable = document.getElementById("log-table");
            const logRows = logTable.getElementsById("log-tr");
            const logStart = (currentLogPage - 1) * logsPerPage + 1;
            const logEnd = currentLogPage * logsPerPage;

            // Hide all rows first
            for (let i = 1; i < logRows.length; i++) {
                logRows[i].style.display = "none";
            }

            // Show the rows for the current page
            for (let i = logStart; i <= logEnd; i++) {
                if (logRows[i]) {
                    logRows[i].style.display = "";
                }
            }

            // Update page number display
            document.getElementById("page-number").textContent = `Page ${currentLogPage}`;

            // Disable/Enable buttons
            document.getElementById("prev").disabled = currentLogPage === 1;
            document.getElementById("next").disabled = logEnd >= logRows.length;
        }

        // Initialize the table with the first page
        displayLogTable();
    </script>
</head>

<body>
    <h1 style="text-align: center;">User Logs</h1>
    <table class="fl-table" id="log-table">
        <thead>
            <tr id="log-tr">
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
                <tr id="log-tr">
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

    <!-- Pagination Controls -->
    <div id="pagination-controls">
        <button id="prev" onclick="changeLogPage(-1)">Prev</button>
        <span id="page-number">Page 1</span>
        <button id="next" onclick="changeLogPage(1)">Next</button>
    </div>
</body>

</html>