<?php
include 'db_conn.php';

$alter_queries = [
    "ALTER TABLE student_academic_details ADD COLUMN school_name VARCHAR(255) AFTER stream",
    "ALTER TABLE student_academic_details ADD COLUMN current_class VARCHAR(50) AFTER school_name",
    "ALTER TABLE student_academic_details ADD COLUMN section VARCHAR(10) AFTER current_class",
    "ALTER TABLE student_academic_details ADD COLUMN passing_year INT AFTER twelfth_year",
    "ALTER TABLE student_academic_details ADD COLUMN university_name VARCHAR(255)",
    "ALTER TABLE student_academic_details ADD COLUMN college_name VARCHAR(255)",
    "ALTER TABLE student_academic_details ADD COLUMN course_degree VARCHAR(100)",
    "ALTER TABLE student_academic_details ADD COLUMN specialization VARCHAR(100)",
    "ALTER TABLE student_academic_details ADD COLUMN semester VARCHAR(20)"
];

foreach ($alter_queries as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Success: $sql <br>";
    } else {
        echo "Note/Error: " . $conn->error . " (Query: $sql) <br>";
    }
}
?>
