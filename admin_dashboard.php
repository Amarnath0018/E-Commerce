<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabbed Interface</title>
    <style>
        /* Basic styles for tabs */
        .tabs {
            display: flex;
            border-bottom: 1px solid #ccc;
        }
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border: 1px solid #ccc;
            border-bottom: none;
            background-color: #f9f9f9;
        }
        .tab.active {
            background-color: white;
            font-weight: bold;
        }
        .tab-content {
            display: none;
            padding: 20px;
            border: 1px solid #ccc;
        }
        .tab-content.active {
            display: block;
        }
    </style>
    <script>
        function openTab(evt, tabName) {
            // Hide all tab content
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.remove('active'));

            // Deactivate all tabs
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => tab.classList.remove('active'));

            // Show the current tab content and activate the tab
            document.getElementById(tabName).classList.add('active');
            evt.currentTarget.classList.add('active');
        }
    </script>
</head>
<body>
    <div class="tabs">
        <div class="tab active" onclick="openTab(event, 'tab1')">Users</div>
        <div class="tab" onclick="openTab(event, 'tab2')">Products</div>
        <div class="tab" onclick="openTab(event, 'tab3')">Logs</div>
        <div class="tab" onclick="openTab(event, 'tab4')">Orders</div>
    </div>

    <div id="tab1" class="tab-content active">
        <?php
            include 'admin/add_user.php';
            include 'admin/users.php';
        ?>
    </div>

    <div id="tab2" class="tab-content">
        <?php
            include 'admin/add_product.php';
            include 'common/list_all_product.php';
        ?>
    </div>

    <div id="tab3" class="tab-content">
        <?php
            include 'admin/logs.php';
        ?>
    </div>

    <div id="tab4" class="tab-content">
        <?php
            include 'admin/all_order_details.php';
        ?>
    </div>
</body>
</html>
