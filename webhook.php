<?php
// webhook.php

// Function to send a WhatsApp message
function sendWhatsAppMessage($phoneNumber, $messageText) {
    $url = 'https://palaksys.co.in/api/ee5500d8-da94-422d-8e5f-08719f90f3f8/contact/send-message';
    
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer 1xcqclPSiN5ywoyaiDw3sJwlTbrwA9ULLieqzxAe3ZMsnmgcLUKP66FKdENTNi1L',
    ];

    $data = [
        'phone' => $phoneNumber,
        'message' => $messageText,
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    // Return response for logging or debugging
    return $response;
}

// Process incoming messages
$data = json_decode(file_get_contents('php://input'), true);

// Get the sender's phone number and message content
$sender = $data['messages'][0]['from'];
$message = $data['messages'][0]['text']['body'];

// Determine the response message based on incoming text
if ($message == 'Hello') {
    $responseMessage = 'Hi there! How can I assist you today?';
} else {
    $responseMessage = 'Sorry, I didn\'t understand that. Can you rephrase?';
}

// Send the response message back to the user
sendWhatsAppMessage($sender, $responseMessage);
?>
