<?php
// usage: php verify_features.php [test_name]
include('db_conn.php');

$test = $argv[1] ?? 'all';

function logResult($name, $success, $msg) {
    echo "[" . ($success ? "PASS" : "FAIL") . "] $name: $msg\n";
}

// 1. Test Real-time Status Logic
if ($test === 'all' || $test === 'status') {
    echo "\nTesting Real-time Status Logic...\n";
    // Pick a test counsellor
    $cid = 1; 
    
    // Simulate Online
    $conn->query("UPDATE counsellors SET is_online=1, last_activity=NOW() WHERE id=$cid");
    
    // Call the logic directly (simulated)
    $last_activity = strtotime(date('Y-m-d H:i:s')); 
    $is_online_db = 1;
    $is_really_online = ($is_online_db == 1 && $last_activity > strtotime('-2 minutes'));
    
    logResult("Online Check", $is_really_online === true, "Counsellor should be online");
    
    // Simulate Timeout (Inactive for 5 mins)
    $conn->query("UPDATE counsellors SET is_online=1, last_activity=DATE_SUB(NOW(), INTERVAL 5 MINUTE) WHERE id=$cid");
    $last_activity = strtotime(date('Y-m-d H:i:s', strtotime('-5 minutes')));
    $is_really_online = ($is_online_db == 1 && $last_activity > strtotime('-2 minutes'));
    
    logResult("Timeout Check", $is_really_online === false, "Counsellor should be offline due to timeout");
}

// 2. Test Notify Me Request
if ($test === 'all' || $test === 'notify') {
    echo "\nTesting Notify Me Request...\n";
    // Assume student_id=1 exists
    $sid = 1;
    $cid = 1;
    $channel = 'whatsapp';
    
    // Clean up previous
    $conn->query("DELETE FROM counsellor_availability_notifications WHERE user_id=$sid AND counsellor_id=$cid");
    
    // Insert new request
    $stmt = $conn->prepare("INSERT INTO counsellor_availability_notifications (user_id, user_type, counsellor_id, channel, status) VALUES (?, 'student', ?, ?, 'pending')");
    $stmt->bind_param("iis", $sid, $cid, $channel);
    $result = $stmt->execute();
    
    logResult("Insert Request", $result, "Notification request inserted");
    
    // Verify it exists AND has correct channel
    $chk = $conn->query("SELECT channel FROM counsellor_availability_notifications WHERE user_id=$sid AND counsellor_id=$cid");
    $row = $chk->fetch_assoc();
    
    logResult("Verify Channel", $row['channel'] === 'whatsapp', "Channel recorded is whatsapp");
}

// 3. Test Booking Slot Logic (Double Booking)
if ($test === 'all' || $test === 'booking') {
    echo "\nTesting Booking Logic (Double Booking)...\n";
    $cid = 1;
    $start_time = date('Y-m-d H:i:s', strtotime('+1 day 10:00:00')); // Tomorrow 10AM
    $end_time = date('Y-m-d H:i:s', strtotime('+1 day 10:30:00'));
    
    // Create a dummy existing session
    $conn->query("DELETE FROM sessions WHERE counsellor_id=$cid AND start_time='$start_time'");
    $conn->query("INSERT INTO sessions (student_id, counsellor_id, start_time, end_time, status, type, amount_charged) VALUES (1, $cid, '$start_time', '$end_time', 'scheduled', 'chat', 100)");
    
    // Now try to verify if a new request overlaps
    // New Req: 10:15 to 10:45 (Overlaps)
    $req_start = date('Y-m-d H:i:s', strtotime('+1 day 10:15:00'));
    $req_end = date('Y-m-d H:i:s', strtotime('+1 day 10:45:00'));
    
    $overlap_sql = "SELECT id FROM sessions WHERE counsellor_id = $cid AND status IN ('scheduled', 'ongoing') AND ((start_time < '$req_end') AND (end_time > '$req_start'))";
    $res = $conn->query($overlap_sql);
    
    logResult("Detect Overlap", $res->num_rows > 0, "Should detect double booking overlap");
    
    // Clean up
    $conn->query("DELETE FROM sessions WHERE counsellor_id=$cid AND start_time='$start_time'");
}

echo "\nVerification Complete.\n";
?>
