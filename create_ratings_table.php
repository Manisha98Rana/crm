<?php
include('db_conn.php');

$sql = "CREATE TABLE IF NOT EXISTS counsellor_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    counsellor_id INT NOT NULL,
    student_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    quality_rating INT DEFAULT NULL CHECK (quality_rating BETWEEN 1 AND 5),
    comm_rating INT DEFAULT NULL CHECK (comm_rating BETWEEN 1 AND 5),
    knowledge_rating INT DEFAULT NULL CHECK (knowledge_rating BETWEEN 1 AND 5),
    review TEXT,
    is_anonymous TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES sessions(id),
    FOREIGN KEY (counsellor_id) REFERENCES counsellors(id),
    FOREIGN KEY (student_id) REFERENCES students(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table counsellor_ratings created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
?>
