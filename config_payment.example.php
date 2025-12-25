<?php
/**
 * Payment Gateway Configuration EXAMPLE
 * 
 * Rename this file to config_payment.php and update with your actual credentials
 */

// Payment Gateway Mode: 'test' or 'live'
define('PAYMENT_MODE', 'test');

// Razorpay Credentials
// Test Mode Credentials
define('RAZORPAY_TEST_KEY_ID', 'rzp_test_XXXXXXXXXXXXXXXX');
define('RAZORPAY_TEST_KEY_SECRET', 'XXXXXXXXXXXXXXXXXXXXXXXX');

// Live Mode Credentials
define('RAZORPAY_LIVE_KEY_ID', 'rzp_live_XXXXXXXXXXXXXXXX');
define('RAZORPAY_LIVE_KEY_SECRET', 'XXXXXXXXXXXXXXXXXXXXXXXX');

// Active Credentials based on mode
define('RAZORPAY_KEY_ID', PAYMENT_MODE === 'live' ? RAZORPAY_LIVE_KEY_ID : RAZORPAY_TEST_KEY_ID);
define('RAZORPAY_KEY_SECRET', PAYMENT_MODE === 'live' ? RAZORPAY_LIVE_KEY_SECRET : RAZORPAY_TEST_KEY_SECRET);

// Payment Settings
define('PAYMENT_CURRENCY', 'INR');
define('PAYMENT_COMPANY_NAME', 'Student CRM');
define('PAYMENT_COMPANY_LOGO', 'https://yourdomain.com/logo.png');

// Minimum and Maximum Recharge Amounts
define('MIN_RECHARGE_AMOUNT', 10);
define('MAX_RECHARGE_AMOUNT', 50000);

// Webhook Secret
define('RAZORPAY_WEBHOOK_SECRET', 'XXXXXXXXXXXXXXXXXXXXXXXX');

?>
