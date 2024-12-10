<?php
// Database connection configuration
$servername = "localhost"; // Change to your database server name or IP
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "equipment_db";  // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Uncomment for debugging successful connection
// echo "Connected successfully";
?>
