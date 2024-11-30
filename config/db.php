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
?>
