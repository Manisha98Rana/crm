<?php
// session_config.php - Template
// Rename this file to session_config.php on your server
// Centralized session configuration for long-term persistence (1 Year)

if (session_status() === PHP_SESSION_NONE) {
    // Set session cookie lifetime to 1 year (31536000 seconds)
    ini_set('session.gc_maxlifetime', 31536000);
    
    // Set cookie parameters: lifetime, path, domain, secure, httponly
    // Note: 'secure' => true is generally safe for modern sites, but locally you might need false if not on https
    session_set_cookie_params(31536000, '/');

    session_start(); 
}
?>
