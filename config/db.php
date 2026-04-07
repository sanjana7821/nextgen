<?php
// Database connection settings
$host = "localhost";
$user = "root";        // Default XAMPP MySQL user
$password = "";        // Default XAMPP MySQL password (empty)
$database = "nextgen";

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session for authentication
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>