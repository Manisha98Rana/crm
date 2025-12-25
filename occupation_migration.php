<?php
include 'db_conn.php';

// Rename parent_occupation to father_occupation if it exists, otherwise add it
$check_col = $conn->query("SHOW COLUMNS FROM students LIKE 'parent_occupation'");
if ($check_col->num_rows > 0) {
    $sql1 = "ALTER TABLE students CHANGE parent_occupation father_occupation VARCHAR(100)";
    if ($conn->query($sql1) === TRUE) echo "Renamed parent_occupation to father_occupation.<br>";
    else echo "Error renaming: " . $conn->error . "<br>";
} else {
    // Check if father_occupation already exists
    $check_father = $conn->query("SHOW COLUMNS FROM students LIKE 'father_occupation'");
    if ($check_father->num_rows == 0) {
        $conn->query("ALTER TABLE students ADD COLUMN father_occupation VARCHAR(100) AFTER parent_email");
        echo "Added father_occupation.<br>";
    }
}

// Add mother_occupation
$check_mother = $conn->query("SHOW COLUMNS FROM students LIKE 'mother_occupation'");
if ($check_mother->num_rows == 0) {
    $sql2 = "ALTER TABLE students ADD COLUMN mother_occupation VARCHAR(100) AFTER father_occupation";
    if ($conn->query($sql2) === TRUE) echo "Added mother_occupation.<br>";
    else echo "Error adding mother_occupation: " . $conn->error . "<br>";
} else {
    echo "mother_occupation already exists.<br>";
}
?>
