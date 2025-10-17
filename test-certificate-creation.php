<?php
/**
 * Test certificate creation
 * Access: http://forever-together.local/test-certificate-creation.php
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Test Certificate Creation</h1>";

// Include certificate system
require_once(get_stylesheet_directory() . '/inc/certificate-system.php');

// Test data
$test_data = array(
    'beneficiary_name' => 'Test User',
    'beneficiary_from' => 'Test Giver',
    'giver_name' => 'Test Giver',
    'recipient_email' => 'test@example.com',
    'amount' => 50.00,
    'payment_intent_id' => 'pi_test_123',
    'stripe_session_id' => 'cs_test_123',
);

echo "<h2>Testing with data:</h2>";
echo "<pre>" . print_r($test_data, true) . "</pre>";

// Test certificate creation
$certificate_code = tf_save_certificate($test_data);

if ($certificate_code) {
    echo "<p style='color: green;'>✅ Certificate created successfully: <strong>$certificate_code</strong></p>";
    
    // Check if it's in the database
    global $wpdb;
    $table_name = $wpdb->prefix . 'tf_certificates';
    $certificate = $wpdb->get_row("SELECT * FROM $table_name WHERE certificate_code = '$certificate_code'");
    
    if ($certificate) {
        echo "<h3>Certificate in database:</h3>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Field</th><th>Value</th></tr>";
        foreach ($certificate as $field => $value) {
            echo "<tr><td>$field</td><td>$value</td></tr>";
        }
        echo "</table>";
    }
    
} else {
    echo "<p style='color: red;'>❌ Certificate creation failed</p>";
    
    // Show database error
    global $wpdb;
    echo "<p><strong>Database Error:</strong> " . $wpdb->last_error . "</p>";
    
    // Check table structure
    $table_name = $wpdb->prefix . 'tf_certificates';
    $columns = $wpdb->get_results("DESCRIBE $table_name");
    
    echo "<h3>Table Structure:</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>{$column->Field}</td>";
        echo "<td>{$column->Type}</td>";
        echo "<td>{$column->Null}</td>";
        echo "<td>{$column->Key}</td>";
        echo "<td>{$column->Default}</td>";
        echo "<td>{$column->Extra}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<p><a href='check-webhook-status.php?session_id=cs_test_a1gMW0fYTAHv0gzgj7tWHT7K9SL5iwtnQCLDDz7QBqfXu3tb6KSqbc0JkS'>Try processing your payment again</a></p>";
?>
