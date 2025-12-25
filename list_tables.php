<?php
include('db_conn.php');

$result = $conn->query("SHOW TABLES");
if ($result) {
    while ($row = $result->fetch_row()) {
        echo $row[0] . "\n";
    }
} else {
    echo "Error listing tables.\n";
}
?>
