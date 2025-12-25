<?php
include 'db_conn.php';

$sql = "CREATE TABLE IF NOT EXISTS counsellor_availability_notifications (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    user_type ENUM('student', 'parent') NOT NULL,
    counsellor_id INT(11) NOT NULL,
    status ENUM('pending', 'notified') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (user_id),
    INDEX (counsellor_id),
    INDEX (status)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'counsellor_availability_notifications' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
?>
