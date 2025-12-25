<?php
// db_conn.php

$servername = "localhost";  // Change this to your database server if not localhost
$username = "root";         // Your database username
$password = "";             // Your database password (leave empty if no password)
$dbname = "u444558326_course_details"; // The name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Set Global Timezone
date_default_timezone_set('Asia/Kolkata');
$conn->query("SET time_zone = '+05:30'");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Close the connection
//mysqli_close($conn); // Uncomment this if you want to close the connection manually in this script
?>
