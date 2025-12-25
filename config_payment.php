<?php
/**
 * Payment Gateway Configuration
 * 
 * This file contains Razorpay payment gateway credentials and settings.
 * 
 * IMPORTANT: 
 * - Replace the test credentials with your actual Razorpay credentials
 * - Get credentials from: https://dashboard.razorpay.com/app/keys
 * - For production, set PAYMENT_MODE to 'live' and use live credentials
 */

// Payment Gateway Mode: 'test' or 'live'
define('PAYMENT_MODE', 'test');

// Razorpay Credentials
// Test Mode Credentials (replace with your test keys)
define('RAZORPAY_TEST_KEY_ID', 'rzp_test_XXXXXXXXXXXXXXXX');
define('RAZORPAY_TEST_KEY_SECRET', 'XXXXXXXXXXXXXXXXXXXXXXXX');

// Live Mode Credentials (replace with your live keys after KYC)
define('RAZORPAY_LIVE_KEY_ID', 'rzp_live_XXXXXXXXXXXXXXXX');
define('RAZORPAY_LIVE_KEY_SECRET', 'XXXXXXXXXXXXXXXXXXXXXXXX');

// Active Credentials based on mode
define('RAZORPAY_KEY_ID', PAYMENT_MODE === 'live' ? RAZORPAY_LIVE_KEY_ID : RAZORPAY_TEST_KEY_ID);
define('RAZORPAY_KEY_SECRET', PAYMENT_MODE === 'live' ? RAZORPAY_LIVE_KEY_SECRET : RAZORPAY_TEST_KEY_SECRET);

// Payment Settings
define('PAYMENT_CURRENCY', 'INR');
define('PAYMENT_COMPANY_NAME', 'Student CRM');
define('PAYMENT_COMPANY_LOGO', 'https://yourdomain.com/logo.png'); // Update with your logo URL

// Minimum and Maximum Recharge Amounts
define('MIN_RECHARGE_AMOUNT', 1);
define('MAX_RECHARGE_AMOUNT', 50000);

// Webhook Secret (Get from Razorpay Dashboard -> Webhooks)
define('RAZORPAY_WEBHOOK_SECRET', 'XXXXXXXXXXXXXXXXXXXXXXXX');

?>
