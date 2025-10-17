<?php
/**
 * Test and fix activation issue
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Test Certificate Activation Fix</h1>";

// Include certificate system
require_once(get_stylesheet_directory() . '/inc/certificate-system.php');

global $wpdb;
$table_name = $wpdb->prefix . 'tf_certificates';

// Test certificate code
$test_code = 'TF-87A1-6BF0';

echo "<h2>Testing Certificate: $test_code</h2>";

// Get current status
$cert = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM $table_name WHERE certificate_code = %s",
    $test_code
));

if ($cert) {
    echo "<h3>Current Status:</h3>";
    echo "<p><strong>ID:</strong> {$cert->id}</p>";
    echo "<p><strong>Code:</strong> {$cert->certificate_code}</p>";
    echo "<p><strong>Amount:</strong> {$cert->amount}€</p>";
    echo "<p><strong>is_active:</strong> {$cert->is_active}</p>";
    echo "<p><strong>activated_at:</strong> {$cert->activated_at}</p>";
    echo "<p><strong>activated_by_email:</strong> {$cert->activated_by_email}</p>";
    
    if ($cert->is_active == 1) {
        echo "<h3>Activating Certificate:</h3>";
        
        // Try manual activation
        $result = $wpdb->update(
            $table_name,
            array(
                'is_active' => 0,
                'activated_at' => current_time('mysql'),
                'activated_by_email' => 'test@example.com'
            ),
            array('certificate_code' => $test_code),
            array('%d', '%s', '%s'),
            array('%s')
        );
        
        if ($result !== false) {
            echo "<p style='color: green;'>✅ Manual activation successful! Updated $result rows.</p>";
            
            // Check new status
            $updated_cert = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM $table_name WHERE certificate_code = %s",
                $test_code
            ));
            
            echo "<h3>Updated Status:</h3>";
            echo "<p><strong>is_active:</strong> {$updated_cert->is_active}</p>";
            echo "<p><strong>activated_at:</strong> {$updated_cert->activated_at}</p>";
            echo "<p><strong>activated_by_email:</strong> {$updated_cert->activated_by_email}</p>";
            
        } else {
            echo "<p style='color: red;'>❌ Manual activation failed!</p>";
            echo "<p><strong>Database Error:</strong> " . $wpdb->last_error . "</p>";
        }
        
    } else {
        echo "<p style='color: orange;'>⚠️ Certificate is already inactive</p>";
    }
    
} else {
    echo "<p style='color: red;'>❌ Certificate not found: $test_code</p>";
}

echo "<h2>Test Using Activation Function:</h2>";

// Test using the actual activation function
$activation_result = tf_activate_certificate($test_code, 'test@example.com');

echo "<h3>Activation Function Result:</h3>";
echo "<pre>" . print_r($activation_result, true) . "</pre>";

// Check final status
$final_cert = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM $table_name WHERE certificate_code = %s",
    $test_code
));

echo "<h3>Final Status:</h3>";
echo "<p><strong>is_active:</strong> {$final_cert->is_active}</p>";
echo "<p><strong>activated_at:</strong> {$final_cert->activated_at}</p>";
echo "<p><strong>activated_by_email:</strong> {$final_cert->activated_by_email}</p>";

?>
