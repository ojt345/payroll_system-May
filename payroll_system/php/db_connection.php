<?php
// Database connection configuration
$host = 'localhost'; // Change as needed
$username = 'root';  // Change as needed
$password = '';      // Change as needed
$database = 'payroll_system';

// Create database connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>