<?php
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);  // Allow origin
header('Access-Control-Allow-Credentials: true');  // ⭐ IMPORTANT for cookies
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
?>