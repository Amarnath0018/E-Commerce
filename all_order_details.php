<?php
// Include the PhpSpreadsheet library
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

// Initialize variables
$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';
$page = isset($_POST['page']) && is_numeric($_POST['page']) ? (int)$_POST['page'] : 1;
$records_per_page = isset($_POST['records_per_page']) && is_numeric($_POST['records_per_page']) ? (int)$_POST['records_per_page'] : 10;

// If 'download_excel' parameter is present, generate the Excel file
if (isset($_POST['download_excel']) && $_POST['download_excel'] == 'true') {
    downloadExcel($from_date, $to_date);
    exit; // Exit after sending the Excel file
}

// Handle pagination and filtering if the Excel export is not requested
$start_from = ($page - 1) * $records_per_page;

if ($from_date != '' && $to_date != '') {
    $sql = "SELECT 
                ud.name,
                pd.product_name,
                pd.rate,
                od.count,
                od.order_date
            FROM 
                order_details od
            JOIN 
                user_details ud ON od.order_by = ud.id
            JOIN 
                product_details pd ON od.product_id = pd.id
            WHERE 
                od.is_active = false
                AND od.order_date BETWEEN '$from_date' AND '$to_date'
            LIMIT $start_from, $records_per_page";
} else {
    $sql = "SELECT 
                ud.name,
                pd.product_name,
                pd.rate,
                od.count,
                od.order_date
            FROM 
                order_details od
            JOIN 
                user_details ud ON od.order_by = ud.id
            JOIN 
                product_details pd ON od.product_id = pd.id
            WHERE 
                od.is_active = false
            LIMIT $start_from, $records_per_page";
}

$result = $connect->query($sql);

// Fetch total number of records for pagination
$total_sql = "SELECT COUNT(*)
            FROM order_details od
            JOIN user_details ud ON od.order_by = ud.id
            JOIN product_details pd ON od.product_id = pd.id
            WHERE od.is_active = false";
$total_result = $connect->query($total_sql);
$total_rows = $total_result->fetch_row()[0];

// Calculate total pages
$total_pages = ceil($total_rows / $records_per_page);

// Prepare data to return for pagination
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Return data as JSON response (if pagination is being requested via AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['download_excel'])) {
    echo json_encode([
        'data' => $data,
        'total_pages' => $total_pages,
        'current_page' => $page
    ]);
    exit;
}

// Function to handle the Excel export
function downloadExcel($from_date, $to_date)
{
    global $connect;

    // Set appropriate headers for Excel file
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="orders.xlsx"');
    header('Cache-Control: max-age=0');

    // Create a new spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set headers for the Excel sheet
    $sheet->setCellValue('A1', 'Name');
    $sheet->setCellValue('B1', 'Product Name');
    $sheet->setCellValue('C1', 'Rate');
    $sheet->setCellValue('D1', 'Count');
    $sheet->setCellValue('E1', 'Order Date');

    // Query to fetch all the filtered order details for Excel download
    if ($from_date != '' && $to_date != '') {
        $sql = "SELECT 
                    ud.name,
                    pd.product_name,
                    pd.rate,
                    od.count,
                    od.order_date
                FROM 
                    order_details od
                JOIN 
                    user_details ud ON od.order_by = ud.id
                JOIN 
                    product_details pd ON od.product_id = pd.id
                WHERE 
                    od.is_active = false
                    AND od.order_date BETWEEN '$from_date' AND '$to_date'";
    } else {
        $sql = "SELECT 
                    ud.name,
                    pd.product_name,
                    pd.rate,
                    od.count,
                    od.order_date
                FROM 
                    order_details od
                JOIN 
                    user_details ud ON od.order_by = ud.id
                JOIN 
                    product_details pd ON od.product_id = pd.id
                WHERE 
                    od.is_active = false";
    }

    $result = $connect->query($sql);
    $rowIndex = 2; // Start from row 2 (row 1 is header)

    // Populate the Excel sheet with data
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowIndex, $row['name']);
        $sheet->setCellValue('B' . $rowIndex, $row['product_name']);
        $sheet->setCellValue('C' . $rowIndex, $row['rate']);
        $sheet->setCellValue('D' . $rowIndex, $row['count']);
        $sheet->setCellValue('E' . $rowIndex, $row['order_date']);
        $rowIndex++;
    }

    // Write the file to the output stream
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
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
        }

        th,
        td {
            padding: 12px 20px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #182628;
            color: #f2f2f2;
            font-weight: bold;
        }

        td {
            background-color: #65ccb8;
            color: #182628;
        }

        tr:nth-child(even) td {
            background-color: #57ba98;
        }

        tr:hover td {
            background-color: #3b945e;
            color: #f2f2f2;
        }

        tr:hover {
            cursor: pointer;
        }

        .caption {
            font-size: 2em;
            margin: 0 auto;
            margin-bottom: 15px;
            color: #f2f2f2;
            background-color: #65ccb8;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px;
            font-weight: bold;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
            text-transform: uppercase;
            width: 50%;
        }

        .search-div {
            font-size: 1em;
            margin: 0 auto;
            margin-bottom: 15px;
            color: #65ccb8;
            padding: 10px 20px;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            width: 50%;
        }

        .search-div button {
            padding: 10px 20px;
            background-color: #65ccb8;
            border: 1px solid #57ba98;
            border-radius: 5px;
            color: #182628;
            font-size: 1em;
            cursor: pointer;
        }

        .search-div button:disabled {
            background-color: #f2f2f2;
            color: #c0c0c0;
            border-color: #ddd;
            cursor: not-allowed;
        }

        .pagination-icons {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #f2f2f2;
            padding: 15px;
            border-radius: 10px;
            width: 75%;
            margin: 0 auto;
        }

        .pagination-number {
            font-size: 1.1em;
            margin-right: 10px;
        }

        .excel-download button {
            padding: 10px 20px;
            background-color: #65ccb8;
            border: 1px solid #57ba98;
            border-radius: 5px;
            color: #182628;
            font-size: 1em;
            cursor: pointer;
        }

        .pagination button {
            padding: 10px 20px;
            background-color: #65ccb8;
            border: 1px solid #57ba98;
            border-radius: 5px;
            color: #182628;
            font-size: 1em;
            cursor: pointer;
        }

        .pagination button:disabled {
            background-color: #f2f2f2;
            color: #c0c0c0;
            border-color: #ddd;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <div class="caption">User Order Details</div>

    <!-- Search Form -->
    <div class="search-div">
        <form method="POST" class="search-form" onsubmit="return false;">
            <label for="from_date">From Date:</label>
            <input type="date" id="from_date" name="from_date" required onchange="checkDates();">

            <label for="to_date">To Date:</label>
            <input type="date" id="to_date" name="to_date" required onchange="checkDates();">

            <button type="submit" id="searchButton" onclick="fetchData();" disabled>Search</button>
        </form>
    </div>

    <!-- Table to display the user data -->
    <table id="data-table" class="fl-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Product Name</th>
                <th>Amount</th>
                <th>Count</th>
                <th>Order Date</th>
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
        <div class="excel-download">
            <button onclick="downloadExcel()">Download as Excel</button>
        </div>
        <!-- Pagination buttons -->
        <div class="pagination">
            <button id="prev" onclick="changePage('prev')">Prev</button>
            <button id="next" onclick="changePage('next')">Next</button>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let recordsPerPage = 10;

        // Function to load data
        function loadData() {
            recordsPerPage = document.getElementById('records_per_page').value;
            fetchData();
        }

        // Function to fetch data via AJAX
        function fetchData() {
            const fromDate = document.getElementById('from_date').value;
            const toDate = document.getElementById('to_date').value;

            const formData = new FormData();
            formData.append('page', currentPage);
            formData.append('records_per_page', recordsPerPage);
            formData.append('from_date', fromDate); // Include the from_date
            formData.append('to_date', toDate); // Include the to_date

            fetch('all_order_details.php', {
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

        // Function to display the data in the table
        function displayData(data) {
            const tableBody = document.querySelector('#data-table tbody');
            tableBody.innerHTML = ''; // Clear previous data

            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.name}</td>
                    <td>${row.product_name}</td>
                    <td>${row.rate}</td>
                    <td>${row.count}</td>
                    <td>${row.order_date}</td>
                `;
                tableBody.appendChild(tr);
            });
        }

        // Update pagination buttons
        function updatePagination(totalPages, currentPage) {
            const prevBtn = document.getElementById('prev');
            const nextBtn = document.getElementById('next');

            if (currentPage === 1) {
                prevBtn.disabled = true;
            } else {
                prevBtn.disabled = false;
            }

            if (currentPage === totalPages) {
                nextBtn.disabled = true;
            } else {
                nextBtn.disabled = false;
            }
        }

        // Change page
        function changePage(direction) {
            if (direction === 'prev') {
                if (currentPage > 1) {
                    currentPage--;
                }
            } else if (direction === 'next') {
                currentPage++;
            }
            fetchData();
        }

        // Function to check if both dates are set before enabling the search button
        function checkDates() {
            const fromDate = document.getElementById('from_date').value;
            const toDate = document.getElementById('to_date').value;

            if (fromDate && toDate) {
                document.getElementById('searchButton').disabled = false;
            } else {
                document.getElementById('searchButton').disabled = true;
            }
        }

        // Initial data load
        fetchData();

        function downloadExcel() {
            const fromDate = document.getElementById('from_date').value;
            const toDate = document.getElementById('to_date').value;

            const formData = new FormData();
            formData.append('from_date', fromDate);
            formData.append('to_date', toDate);
            formData.append('download_excel', 'true');

            fetch('all_order_details.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.blob())
                .then(blob => {
                    const link = document.createElement('a');
                    const url = window.URL.createObjectURL(blob);
                    link.href = url;
                    link.download = 'orders.xlsx';
                    link.click();
                    window.URL.revokeObjectURL(url);
                })
                .catch(error => console.error('Error downloading Excel:', error));
        }
    </script>

</body>

</html>