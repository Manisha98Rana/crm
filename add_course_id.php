<?php
include 'db_conn.php';

$sql = "ALTER TABLE enquiries ADD COLUMN course_id INT(11) DEFAULT NULL AFTER college_name";

if ($conn->query($sql) === TRUE) {
    echo "Column course_id added successfully";
} else {
    echo "Error adding column: " . $conn->error;
}

$conn->close();
?>
