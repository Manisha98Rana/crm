<?php
include('db_conn.php');

// Add 'channel' column if it doesn't exist
$sql = "SHOW COLUMNS FROM counsellor_availability_notifications LIKE 'channel'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // Add channel enum
    $alter_sql = "ALTER TABLE counsellor_availability_notifications 
                  ADD COLUMN channel ENUM('whatsapp', 'sms', 'email', 'in_app') DEFAULT 'whatsapp' AFTER counsellor_id";
    if ($conn->query($alter_sql) === TRUE) {
        echo "Added 'channel' column successfully.\n";
    } else {
        echo "Error adding 'channel' column: " . $conn->error . "\n";
    }
} else {
    echo "'channel' column already exists.\n";
}

// Add 'is_notified' column if it doesn't exist
$sql2 = "SHOW COLUMNS FROM counsellor_availability_notifications LIKE 'is_notified'";
$result2 = $conn->query($sql2);

if ($result2->num_rows == 0) {
    $alter_sql2 = "ALTER TABLE counsellor_availability_notifications 
                   ADD COLUMN is_notified TINYINT(1) DEFAULT 0 AFTER status";
    if ($conn->query($alter_sql2) === TRUE) {
        echo "Added 'is_notified' column successfully.\n";
    } else {
        echo "Error adding 'is_notified' column: " . $conn->error . "\n";
    }
} else {
    echo "'is_notified' column already exists.\n";
}

// Create Notification Logs table if not exists (as per requirements)
$log_table = "CREATE TABLE IF NOT EXISTS notification_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    user_type ENUM('student', 'parent') NOT NULL,
    counsellor_id INT NOT NULL,
    message TEXT,
    channel VARCHAR(20),
    status ENUM('sent', 'failed') DEFAULT 'sent',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($log_table) === TRUE) {
    echo "notification_logs table checked/created.\n";
} else {
    echo "Error checking notification_logs table: " . $conn->error . "\n";
}

echo "Migration script completed.\n";
?>
