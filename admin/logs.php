<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'e_commerce';

// Create connection
$connect = new mysqli($host, $user, $password, $database);

// Check connection
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// If AJAX request is made, handle it and return JSON data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $page = isset($_POST['page']) && is_numeric($_POST['page']) ? (int)$_POST['page'] : 1;
    $records_per_page = isset($_POST['records_per_page']) && is_numeric($_POST['records_per_page']) ? (int)$_POST['records_per_page'] : 10;

    // Calculate the starting point for the query
    $start_from = ($page - 1) * $records_per_page;

    // Modified SQL query to join user_log and user_details
    $sql = "SELECT user_details.name, user_details.email, user_log.id, user_log.login_time, user_log.logout_time, user_log.pages_visited
            FROM user_log
            INNER JOIN user_details ON user_log.user_id = user_details.id
            LIMIT $start_from, $records_per_page";
    $result = $connect->query($sql);

    // Fetch total number of records for pagination
    $total_sql = "SELECT COUNT(*) FROM user_log INNER JOIN user_details ON user_log.user_id = user_details.id";
    $total_result = $connect->query($total_sql);
    $total_rows = $total_result->fetch_row()[0];

    // Calculate total pages
    $total_pages = ceil($total_rows / $records_per_page);

    // Prepare data to return
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Return data as JSON response
    echo json_encode([
        'data' => $data,
        'total_pages' => $total_pages,
        'current_page' => $page
    ]);
    exit; // Exit the script after sending the JSON response
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagination with AJAX in One PHP File</title>
    <style>
        /* Add your styles here */
        .pagination button {
            padding: 5px 10px;
            margin: 5px;
        }

        * {
            padding: 0;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            /* White background */
            margin: 0;
            padding: 0;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            /* Raised effect shadow */
        }

        th,
        td {
            padding: 12px 20px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #182628;
            /* Black */
            color: #f2f2f2;
            /* White */
            font-weight: bold;
        }

        td {
            background-color: #65ccb8;
            /* Light Green */
            color: #182628;
            /* Black text */
        }

        tr:nth-child(even) td {
            background-color: #57ba98;
            /* Medium Green for even rows */
        }

        tr:hover td {
            background-color: #3b945e;
            /* Dark Green on hover */
            color: #f2f2f2;
            /* White text on hover */
        }

        tr:hover {
            cursor: pointer;
        }

        .caption {
            font-size: 2em;
            margin: 0 auto;
            /* Center the caption horizontally */
            margin-bottom: 15px;
            color: #f2f2f2;
            /* Black */
            background-color: #65ccb8;
            /* Light Green */
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px;
            font-weight: bold;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
            /* Subtle shadow for the caption */
            text-transform: uppercase;
            /* Uppercase text for emphasis */
            width: 50%;
        }


        /* Add a smooth transition effect */
        td,
        th {
            transition: background-color 0.3s, color 0.3s;
        }

        /* Container styling for pagination */
        .pagination-icons {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #f2f2f2;
            /* Black background */
            padding: 15px;
            border-radius: 10px;
            /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); */
            color: #182628;
            width: 75%;
            margin: 0 auto;
        }

        /* Styling for the records per page label and dropdown */
        .pagination-icons label {
            font-size: 1.1em;
            margin-right: 10px;
            font-weight: 500;
            color: #182628;
            /* White text */
        }

        .pagination-icons select {
            padding: 8px;
            background-color: #65ccb8;
            /* Light Green background */
            border: 1px solid #57ba98;
            /* Medium Green border */
            border-radius: 5px;
            color: #182628;
            /* Black text */
            font-size: 1em;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .pagination-icons select:hover {
            background-color: #57ba98;
            /* Hover effect with medium green */
            border-color: #3b945e;
            /* Darker green border on hover */
            cursor: pointer;
        }

        /* Styling for pagination buttons */
        .pagination {
            display: flex;
            gap: 10px;
        }

        .pagination button {
            padding: 10px 20px;
            background-color: #65ccb8;
            /* Light Green background */
            border: 1px solid #57ba98;
            /* Medium Green border */
            border-radius: 5px;
            color: #182628;
            /* Black text */
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        /* Hover effect for pagination buttons */
        .pagination button:hover {
            background-color: #57ba98;
            /* Medium Green background on hover */
            border-color: #3b945e;
            /* Darker green border on hover */
            transform: translateY(-2px);
            /* Slight "lift" effect */
        }

        /* Hover effect for disabled pagination buttons */
        .pagination button:disabled {
            background-color: #f2f2f2;
            color: #c0c0c0;
            border-color: #ddd;
            cursor: not-allowed;
        }

        .pagination button:disabled:hover {
            background-color: #f2f2f2;
            border-color: #ddd;
        }
    </style>
</head>

<body>
    <div class="caption">User Log Details</div>

    <!-- Table to display the user data -->
    <table id="data-table" class="fl-table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Login Time</th>
                <th>Logout Time</th>
                <th>Pages Visited</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data rows will be populated here dynamically -->
        </tbody>
    </table>

    <div class="pagination-icons">
        <!-- Records per page dropdown -->
        <div class="pagination-number">
            <label for="records_per_page">Records per page: </label>
            <select id="records_per_page" onchange="loadData()">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>

        <!-- Pagination buttons -->
        <div class="pagination">
            <button id="prev" onclick="changePage('prev')">Prev</button>
            <button id="next" onclick="changePage('next')">Next</button>
        </div>
    </div>

    <script>
        // Store the current page and records per page
        let currentPage = 1;
        let recordsPerPage = 10;

        // Function to load data
        function loadData() {
            recordsPerPage = document.getElementById('records_per_page').value;
            fetchData();
        }

        // Function to fetch data via AJAX
        function fetchData() {
            const formData = new FormData();
            formData.append('page', currentPage);
            formData.append('records_per_page', recordsPerPage);

            // Make AJAX request to fetch data
            fetch('admin/logs.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    displayData(data.data);
                    updatePagination(data.total_pages, data.current_page);
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        // Function to display data in the table
        function displayData(data) {
            const tableBody = document.querySelector('#data-table tbody');
            tableBody.innerHTML = ''; // Clear previous data

            // Loop through the data and create table rows dynamically
            data.forEach(row => {
                const logoutTime = row.logout_time ? row.logout_time : "Still Active";
                const pagesvisited = row.logout_time ? row.pages_visited : "Still Active";
                const tr = document.createElement('tr');
                tr.innerHTML = `
            <td>${row.id}</td>
            <td>${row.name}</td>
            <td>${row.email}</td>
            <td>${row.login_time}</td>
            <td>${logoutTime}</td>
            <td>${pagesvisited}</td>
        `;
                tableBody.appendChild(tr);
            });
        }

        // Function to update the pagination buttons
        function updatePagination(totalPages, currentPage) {
            const prevButton = document.getElementById('prev');
            const nextButton = document.getElementById('next');

            // Disable buttons if at the start or end of pagination
            prevButton.disabled = currentPage === 1;
            nextButton.disabled = currentPage === totalPages;
        }

        // Function to change page
        function changePage(direction) {
            if (direction === 'prev' && currentPage > 1) {
                currentPage--;
            } else if (direction === 'next') {
                currentPage++;
            }
            fetchData();
        }

        // Initialize the first data load
        fetchData();

    </script>

</body>

</html>