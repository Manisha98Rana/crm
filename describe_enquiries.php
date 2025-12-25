<?php
include 'db_conn.php';
$result = $conn->query("DESCRIBE enquiries");
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}
?>
