<?php

// Example role from session (for demo purposes, replace with your actual session logic)
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user'; // Default to 'user' if no role is set

if ($role === 'admin') {
    // Display admin navigation
    echo '<nav>
            <ul class="root-list">
                <li>
                    <span>Users</span>
                    <ul class="nested-list">
                        <li><a style="color:#3b945e;" href="?page=admin/users">User</a></li>
                        <li><a style="color:#3b945e;" href="?page=admin/add_user">Add User</a></li>
                    </ul>
                </li>
                <li>
                    <span>Products</span>
                    <ul class="nested-list">
                        <li><a style="color:#3b945e;" href="?page=common/list_all_product">View Products</a></li>
                        <li><a style="color:#3b945e;" href="?page=admin/add_product">Add Products</a></li>
                    </ul>
                </li>

                <li><a href="?page=admin/logs">User Logs</a></li>
                <li><a href="?page=all_order_details">Order Summary</a></li>
            </ul>
          </nav>';
} elseif ($role === 'user') {
    // Display user navigation
    echo '<nav>
            <ul class="root-list">
                <li><a href="?page=common/list_all_product">Products</a></li>
                <li><a href="?page=user/cart_details">Cart</a></li>
                <li><a href="?page=user_order_details">Order Summary</a></li>s
            </ul>
          </nav>';
} else {
    // Optionally, handle case when the user has no role set or is an unknown user
    echo '<nav>
            <ul>
                <li><a href="?page=common/list_all_product">Products</a></li>
            </ul>
          </nav>';
}
