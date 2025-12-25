<?php
include('db_conn.php');

$cid = 1;

// Clear existing for C1
$conn->query("DELETE FROM counsellor_availability WHERE counsellor_id = $cid");

$days = [1,2,3,4,5,6,7];
$stmt = $conn->prepare("INSERT INTO counsellor_availability (counsellor_id, day_of_week, start_time, end_time, is_active) VALUES (?, ?, ?, ?, ?)");

$start = '10:00:00';
$end = '18:00:00';
$active = 1;

foreach($days as $d) {
    // Bind parameters inside loop or re-bind? Bind once, execute multiple.
    // bind_param needs references.
}

// Easier loop with query
foreach($days as $d) {
    if(!$conn->query("INSERT INTO counsellor_availability (counsellor_id, day_of_week, start_time, end_time, is_active) VALUES ($cid, $d, '$start', '$end', $active)")) {
        echo "Error: " . $conn->error . "\n";
    }
}

echo "Inserted availability for Counsellor $cid for days 1-7.\n";
?>
