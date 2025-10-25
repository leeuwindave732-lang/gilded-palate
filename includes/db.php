<?php
// Show all errors during development (optional, remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$servername = "127.0.0.1";
$username = "root";
$password = "Leeuwindave732"; // your real password
$database = "gilded_palate";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
