<?php
// Your API credentials
$accessToken = '1xcqclPSiN5ywoyaiDw3sJwlTbrwA9ULLieqzxAe3ZMsnmgcLUKP66FKdENTNi1L';
$apiBaseUrl = 'https://palaksys.co.in/api/ee5500d8-da94-422d-8e5f-08719f90f3f8/contact/send-message';

// Function to send a WhatsApp message
function sendMessageToWhatsApp($phone, $message) {
    global $accessToken, $apiBaseUrl;

    // Ensure the phone number is in full international format
    if (substr($phone, 0, 1) !== '+') {
        $phone = '+91' . $phone; // Defaulting to India’s code; adjust if needed
    }

    // Prepare data for the API request in JSON format
    $data = [
        'phone' => $phone, // Student's phone number in international format
        'message' => $message // Message to send
    ];

    // Initialize cURL
    $ch = curl_init($apiBaseUrl);

    // Set cURL options for sending JSON data
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
    curl_setopt($ch, CURLOPT_POST, true); // POST request
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Send data as JSON

    // Set the Authorization header with the access token
    $headers = [
        'Authorization: Bearer ' . $accessToken, // Adding the token in the header
        'Content-Type: application/json', // Content type is now JSON
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        $error_message = 'cURL Error: ' . curl_error($ch);
        file_put_contents('api_error_log.txt', $error_message . "\n", FILE_APPEND);
        echo $error_message;
    } else {
        // Decode the response and log for further inspection
        $responseDecoded = json_decode($response, true);
        file_put_contents('api_response_log.txt', $response . "\n", FILE_APPEND);

        // Check if the response is valid and contains a success result
        if (isset($responseDecoded['result']) && $responseDecoded['result'] === 'success') {
            echo 'Message sent successfully!';
        } else {
            $error_message = isset($responseDecoded['message']) ? $responseDecoded['message'] : 'Unknown error';
            file_put_contents('api_error_log.txt', "Failed to send message: $error_message\n", FILE_APPEND);
            echo 'Failed to send message: ' . $error_message;
        }
    }

    // Close cURL session
    curl_close($ch);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Validate phone number format (basic validation)
    if (empty($phone) || !preg_match("/^\+?[0-9]{1,3}[0-9]{7,15}$/", $phone)) {
        echo "Invalid phone number. Please enter a valid international phone number.";
    } else {
        // Send message
        sendMessageToWhatsApp($phone, $message);
    }
}
?>
