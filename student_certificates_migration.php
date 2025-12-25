<?php
include 'db_conn.php';

$sql = "CREATE TABLE IF NOT EXISTS student_certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    category VARCHAR(50),
    title VARCHAR(255),
    issuing_authority VARCHAR(255),
    year INT,
    level VARCHAR(50),
    file_path VARCHAR(255),
    status ENUM('Pending', 'Verified', 'Rejected') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table student_certificates created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
?>
