<?php
// db_conn.php EXAMPLE
// Rename this file to db_conn.php and update with your actual credentials

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Set Global Timezone
date_default_timezone_set('Asia/Kolkata');
$conn->query("SET time_zone = '+05:30'");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
