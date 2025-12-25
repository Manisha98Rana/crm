<?php
// ⭐ MUST BE FIRST - No output before this!
ob_start();
session_start();

// Set headers FIRST
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: ' . (isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*'));
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Stop output buffering to prevent HTML errors
ob_end_clean();

// Disable error display to prevent HTML output
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Set timezone
date_default_timezone_set('Asia/Kolkata');

// Include database connection
include('db_conn.php');

// Initialize response
$response = [
    'success' => false,
    'message' => 'Something went wrong.'
];

try {
    // Verify POST request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Get mobile from session
    $mobile = isset($_SESSION['mobile']) ? $_SESSION['mobile'] : '';
    
    // Get OTP from POST
    $otp_entered = isset($_POST['otp_entered']) ? trim($_POST['otp_entered']) : '';

    // Log for debugging
    error_log("[verify_login_otp] Mobile: $mobile, OTP: $otp_entered");

    // Validate inputs
    if (empty($otp_entered)) {
        throw new Exception('Please enter the OTP');
    }

    if (empty($mobile)) {
        http_response_code(401);
        throw new Exception('Session expired. Please try again.');
    }

    // Validate OTP format (6 digits only)
    if (!preg_match('/^[0-9]{6}$/', $otp_entered)) {
        throw new Exception('Invalid OTP format. Must be 6 digits.');
    }

    // Check database connection
    if (!$conn) {
        http_response_code(500);
        throw new Exception('Database connection failed');
    }

    // Sanitize mobile
    $mobile = mysqli_real_escape_string($conn, $mobile);
    $otp_entered = mysqli_real_escape_string($conn, $otp_entered);

    // Query database
    $query = "SELECT id, name, email, otp, otp FROM students WHERE mobile = '$mobile' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        http_response_code(500);
        throw new Exception('Database query failed: ' . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) === 0) {
        http_response_code(404);
        throw new Exception('User not found. Please register first.');
    }

    $row = mysqli_fetch_assoc($result);
    $stored_otp = $row['otp'];
    $otp_created_at = $row['otp_created_at'];
    $user_id = $row['id'];
    $user_name = $row['name'];
    $user_email = $row['email'];

    // Check if OTP is empty (already used or not generated)
    if (empty($stored_otp)) {
        throw new Exception('No active OTP found. Please request a new OTP.');
    }

    // Check OTP expiry (2 minutes = 120 seconds)
    if (!empty($otp_created_at)) {
        $current_time = time();
        $otp_time = strtotime($otp_created_at);
        $time_diff = $current_time - $otp_time;
        $expiry_seconds = 120; // 2 minutes

        if ($time_diff > $expiry_seconds) {
            throw new Exception('OTP has expired. Please request a new OTP.');
        }
    }

    // Verify OTP
    if ($otp_entered !== $stored_otp) {
        throw new Exception('Invalid OTP. Please try again.');
    }

    // ✅ OTP is valid - Clear it from database
    $update_query = "UPDATE students SET otp = NULL WHERE id = '$user_id'";
    
    if (!mysqli_query($conn, $update_query)) {
        http_response_code(500);
        throw new Exception('Failed to update database: ' . mysqli_error($conn));
    }

    // ✅ Set session variables
    $_SESSION['loggedin'] = true;
    $_SESSION['user_id'] = $user_id;
    $_SESSION['mobile'] = $mobile;
    $_SESSION['name'] = $user_name;
    $_SESSION['email'] = $user_email;

    // Success response
    http_response_code(200);
    $response['success'] = true;
    $response['message'] = 'Login successful!';
    $response['user_id'] = $user_id;
    $response['name'] = $user_name;

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    error_log('[verify_login_otp] Error: ' . $e->getMessage());
}

// Output JSON response
echo json_encode($response, JSON_UNESCAPED_SLASHES);
exit;
?>