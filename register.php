<?php 
session_start();
include('db_conn.php'); // Database connection
require_once './cors.php'; // CORS headers

// Enable error reporting
ini_set('display_errors', 1);
header('Access-Control-Allow-Headers: Authorization, Content-Type, X-Requested-With');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit(); }
// Function to generate OTP
function generateOTP($length = 6) {
    return rand(100000, 999999);
}

// Function to log debug info
function debugLog($message, $data = null) {
    error_log("[REGISTER] " . $message . ($data ? " | " . json_encode($data) : ""));
}

// Get current timestamp
$created_date = date('Y-m-d H:i:s');

// Receive JSON data from the fetch request
$rawData = file_get_contents("php://input");
debugLog("Raw Input Received", $rawData);

$data = json_decode($rawData, true);

// Check if data is valid JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid JSON format: ' . json_last_error_msg()
    ]);
    exit;
}

// Validate required fields
$requiredFields = ['name', 'mobile', 'email', 'address'];
foreach ($requiredFields as $field) {
    if (!isset($data[$field]) || empty(trim($data[$field]))) {
        http_response_code(400);
        echo json_encode([
            'success' => false, 
            'message' => ucfirst($field) . ' is required'
        ]);
        exit;
    }
}

// Sanitize input
$name = mysqli_real_escape_string($conn, trim($data['name']));
$mobile = mysqli_real_escape_string($conn, trim($data['mobile']));
$email = mysqli_real_escape_string($conn, trim($data['email']));
$address = mysqli_real_escape_string($conn, trim($data['address']));

// Validate mobile number (10 digits)
if (!preg_match('/^[0-9]{10}$/', $mobile)) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'Mobile number must be 10 digits'
    ]);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid email format'
    ]);
    exit;
}

// Check if mobile or email already exists
$checkQuery = "SELECT mobile, email FROM students WHERE mobile = '$mobile' OR email = '$email'";
$checkResult = mysqli_query($conn, $checkQuery);

if (!$checkResult) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Database error: ' . mysqli_error($conn)
    ]);
    exit;
}

if (mysqli_num_rows($checkResult) > 0) {
    $row = mysqli_fetch_assoc($checkResult);
    $message = ($row['mobile'] === $mobile) ? 
        'Mobile number already registered.' : 
        'Email already registered.';
    
    http_response_code(409);
    echo json_encode([
        'success' => false, 
        'message' => $message
    ]);
    exit;
}

// Generate OTP
$otp = generateOTP();
debugLog("OTP Generated", $otp);

// Insert user record
$insert_query = "INSERT INTO students (name, mobile, email, address, otp, created_date) 
                 VALUES ('$name', '$mobile', '$email', '$address', '$otp', '$created_date')";

if (!mysqli_query($conn, $insert_query)) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Database error: ' . mysqli_error($conn)
    ]);
    exit;
}

debugLog("User registered in database", ['name' => $name, 'mobile' => $mobile, 'email' => $email]);

// ============ CREATE WHATSAPP CONTACT ============
$contactApiUrl = "https://palaksys.co.in/api/ee5500d8-da94-422d-8e5f-08719f90f3f8/contact/create";
$accessToken = "R5iPHgXnG4idxpombfYdEj2OfRLFlCZ11RytyV3alw0b59Kpted4HdwJ6206gYbX";

$contactData = [
    'phone_number' => '91' . $mobile,  // Add country code 91
    'first_name' => $name,
    'last_name' => 'User',             // Changed from '.' to 'User'
    'email' => $email,
    'country' => 'India',
    'language_code' => 'en'
];

debugLog("Creating WhatsApp Contact", $contactData);

$ch = curl_init($contactApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // For testing only
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // For testing only
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $accessToken
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($contactData));

$contactResponse = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

debugLog("WhatsApp Contact Response", [
    'httpCode' => $httpCode,
    'response' => $contactResponse,
    'curlError' => $curlError
]);

// Check contact creation response
if ($httpCode != 200 && $httpCode != 201) {
    debugLog("Failed to create WhatsApp contact", [
        'httpCode' => $httpCode,
        'response' => $contactResponse
    ]);
    
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'Failed to create WhatsApp contact. HTTP Code: ' . $httpCode,
        'details' => $contactResponse  // Remove in production
    ]);
    exit;
}

// ============ SEND OTP VIA WHATSAPP ============
$apiUrl = "https://palaksys.co.in/api/ee5500d8-da94-422d-8e5f-08719f90f3f8/contact/send-template-message";

$messageData = [
    'phone_number' => '91' . $mobile,
    'template_name' => 'palaksys_otp',
    'template_language' => 'en',
    'field_1' => (string)$otp,
    'button_0' => (string)$otp
];

debugLog("Sending WhatsApp OTP", $messageData);

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // For testing only
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // For testing only
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $accessToken
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

debugLog("WhatsApp OTP Send Response", [
    'httpCode' => $httpCode,
    'response' => $response,
    'curlError' => $curlError
]);

if ($httpCode == 200 || $httpCode == 201) {
    $_SESSION['otp'] = $otp;
    $_SESSION['mobile'] = $mobile;
    $_SESSION['name'] = $name;
    
    // ============ SEND WELCOME NOTIFICATION ============
    // We need the user ID. We can get it via mobile since we just inserted it.
    // Or we can assume ID is auto-increment but we don't have it from INSERT query result easily without insert_id
    // But verify_otp.php actually finishes the "Verified" status. 
    // Usually welcome email goes AFTER verification?
    // User request: "Successful registration". Is this Pre-OTP or Post-OTP?
    // Post-OTP is safer for "Welcome". But "OTP delivery" is also a notification type.
    // Let's stick to "Welcome" here or maybe just initiate it. 
    // Actually, `register.php` just sends OTP. Usage is incomplete.
    // BUT the user record IS inserted on line 110. So they are "registered" but not "verified".
    // Let's send the notification asynchronously or here if fast.
    
    // For now, let's keep it simple and assume we send it later or here if desired.
    // Triggering it here might slow down OTP response. 
    // Ideally queue it or send after OTP verify. 
    // HOWEVER, the user asked for "Successful registration" trigger.
    // I will add it to `verify_otp.php` instead if that exists, as that confirms registration.
    // Let's check verify_otp.php first. 
    
    // For this step, I'll just log/comment that we are doing it in verify_otp or similar.
    // Wait, the prompt implies "Trigger-Based Notifications -> Successful registration".
    // Creating the account is "Successful registration" technically.
    
    $student_id = mysqli_insert_id($conn);
    if ($student_id) {
        require_once 'classes/NotificationService.php';
        $svc = new NotificationService($conn);
        // Using 'welcome_student' template. 
        // We do this non-blocking if possible, or accept the slight delay (SMTP).
        $svc->sendTemplate($student_id, 'student', 'welcome_student', ['name' => $name]);
    }

    http_response_code(200);
    echo json_encode([
        'success' => true, 
        'message' => 'OTP has been sent to your WhatsApp. Please enter the OTP to complete registration.'
    ]);
} else {
    debugLog("Failed to send OTP", [
        'httpCode' => $httpCode,
        'response' => $response
    ]);
    
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'Failed to send OTP. HTTP Code: ' . $httpCode,
        'details' => $response  // Remove in production
    ]);
}
?>