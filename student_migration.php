<?php
include 'db_conn.php';

$alter_queries = [
    "ALTER TABLE students ADD COLUMN parent_mobile VARCHAR(20) AFTER mobile",
    "ALTER TABLE students ADD COLUMN parent_occupation VARCHAR(100) AFTER parent_email",
    "ALTER TABLE students ADD COLUMN annual_income VARCHAR(50) AFTER parent_occupation",
    "ALTER TABLE students ADD COLUMN profile_photo VARCHAR(255) AFTER address",
    "ALTER TABLE students ADD COLUMN consent_sms TINYINT(1) DEFAULT 0",
    "ALTER TABLE students ADD COLUMN consent_whatsapp TINYINT(1) DEFAULT 0",
    "ALTER TABLE students ADD COLUMN consent_email TINYINT(1) DEFAULT 0"
];

foreach ($alter_queries as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Success: $sql <br>";
    } else {
        echo "Note/Error: " . $conn->error . " (Query: $sql) <br>";
    }
}
?>
