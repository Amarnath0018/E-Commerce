<?php

require_once 'config/db.php';

// Fetch user logs
$sql = "SELECT name, email, id, phone_number, role 
        FROM user_details";
$result = $connect->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <style>
        *{
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
        }
        body{
            font-family: Helvetica;
            -webkit-font-smoothing: antialiased;
            background: rgba( 71, 147, 227, 1);
        }
        h2{
            text-align: center;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: white;
            padding: 30px 0;
        }

        /* Table Styles */

        .table-wrapper{
            margin: 10px 70px 70px;
            box-shadow: 0px 35px 50px rgba( 0, 0, 0, 0.2 );
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

        .fl-table td, .fl-table th {
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
            background: #0808ff !important;
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
            .table-wrapper:before{
                content: "Scroll horizontally >";
                display: block;
                text-align: right;
                font-size: 11px;
                color: white;
                padding: 0 0 10px;
            }
            .fl-table thead, .fl-table tbody, .fl-table thead th {
                display: block;
            }
            .fl-table thead th:last-child{
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
            .fl-table td, .fl-table th {
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
        const rowsPerPage = 5;
        let currentPage = 1;

        function changePage(direction) {
            currentPage += direction;
            displayTable();
        }

        function displayTable() {
            const table = document.getElementById("data-table");
            const rows = table.getElementsByTagName("tr");
            const start = (currentPage - 1) * rowsPerPage + 1;
            const end = currentPage * rowsPerPage;

            // Hide all rows first
            for (let i = 1; i < rows.length; i++) {
                rows[i].style.display = "none";
            }

            // Show the rows for the current page
            for (let i = start; i <= end; i++) {
                if (rows[i]) {
                    rows[i].style.display = "";
                }
            }

            // Update page number display
            document.getElementById("page-number").textContent = `Page ${currentPage}`;

            // Disable/Enable buttons
            document.getElementById("prev").disabled = currentPage === 1;
            document.getElementById("next").disabled = end >= rows.length;
        }

        // Initialize the table with the first page
        displayTable();

    </script>
</head>
<body>
    <h1 style="text-align: center;">User Details</h1>
    <table class="fl-table" id="data-table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['phone_number']; ?></td>
            <td><?php echo $row['role'] ?? 'Still Active'; ?></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    
<!-- Pagination Controls -->
<div id="pagination-controls">
    <button id="prev" onclick="changePage(-1)">Prev</button>
    <span id="page-number">Page 1</span>
    <button id="next" onclick="changePage(1)">Next</button>
</div>
</body>
</html>
