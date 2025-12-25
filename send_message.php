<?php
// Capture incoming data from the frontend
$data = json_decode(file_get_contents('php://input'), true);
$phoneNumber = $data['phone_number'] ?? '';
$userMessage = $data['message'] ?? '';

// Validate input
if (empty($phoneNumber) || empty($userMessage)) {
    echo json_encode(['reply' => 'Please provide both a phone number and a message.']);
    exit;
}

// CRM API Configuration
$apiBaseUrl = 'https://palaksys.co.in/api';
$vendorUid = 'ee5500d8-da94-422d-8e5f-08719f90f3f8';
$accessToken = '1xcqclPSiN5ywoyaiDw3sJwlTbrwA9ULLieqzxAe3ZMsnmgcLUKP66FKdENTNi1L';

// Construct API endpoint for sending message
$url = "$apiBaseUrl/$vendorUid/contact/send-message";

// Prepare message data
$messageData = [
    'phone_number' => $phoneNumber,
    'message_body' => $userMessage,
    'contact' => [
        'first_name' => 'User',
        'last_name' => 'Visitor',
        'email' => 'visitor@domain.com',
        'country' => 'Unknown',
        'language_code' => 'en',
        'groups' => 'website_chat'
    ]
];

// Initialize cURL and configure options
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json'
]);

// Execute cURL request
$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

// Process response or handle errors
if ($error) {
    echo json_encode(['reply' => 'Failed to send message. Please try again later.']);
} else {
    $responseData = json_decode($response, true);
    // Check if the result is 'success' and the status is 'accepted'
    if (isset($responseData['result']) && $responseData['result'] === 'success' && 
        isset($responseData['data']['status']) && $responseData['data']['status'] === 'accepted') {
        echo json_encode(['reply' => 'Message sent successfully!']);
    } else {
        echo json_encode(['reply' => 'Message processed, but it may not have been delivered.']);
    }
}
