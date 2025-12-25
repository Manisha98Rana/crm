<?php
include('db_conn.php');

echo "=== PayU Configuration Status ===\n\n";

$result = $conn->query("SELECT * FROM payment_methods WHERE type = 'payu'");
if($row = $result->fetch_assoc()) {
    $config = json_decode($row['config'], true);
    
    echo "Status: " . ($row['is_active'] ? "ACTIVE" : "INACTIVE") . "\n";
    echo "Merchant Key: " . ($config['merchant_key'] ?? 'NOT SET') . "\n";
    echo "Merchant Salt: " . (isset($config['merchant_salt']) && !empty($config['merchant_salt']) ? 'SET (' . $config['merchant_salt'] . ')' : 'NOT SET') . "\n";
    echo "Test Mode: " . (($config['test_mode'] ?? 0) ? 'YES' : 'NO') . "\n\n";
    
    if(!empty($config['merchant_key']) && !empty($config['merchant_salt'])) {
        echo "✅ PayU is CONFIGURED PROPERLY!\n";
    } else {
        echo "❌ PayU NOT CONFIGURED - Missing credentials!\n";
    }
} else {
    echo "❌ PayU payment method not found!\n";
}

$conn->close();
?>
