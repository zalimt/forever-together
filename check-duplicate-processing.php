<?php
/**
 * Check for duplicate certificate processing
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Check Duplicate Processing</h1>";

global $wpdb;
$table_name = $wpdb->prefix . 'tf_certificates';

// Get the current certificate
$cert = $wpdb->get_row("SELECT * FROM $table_name WHERE id = 26");

if ($cert) {
    echo "<h2>Current Certificate Details:</h2>";
    echo "<p><strong>ID:</strong> {$cert->id}</p>";
    echo "<p><strong>Code:</strong> {$cert->certificate_code}</p>";
    echo "<p><strong>Amount:</strong> {$cert->amount}€</p>";
    echo "<p><strong>Created:</strong> {$cert->created_at}</p>";
    echo "<p><strong>Updated:</strong> {$cert->updated_at}</p>";
    echo "<p><strong>Stripe Session ID:</strong> {$cert->stripe_session_id}</p>";
    
    // Check if there are any duplicate session IDs
    $duplicate_sessions = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name WHERE stripe_session_id = %s",
        $cert->stripe_session_id
    ));
    
    if (count($duplicate_sessions) > 1) {
        echo "<p style='color: red;'>❌ Found duplicate certificates for the same session!</p>";
        foreach ($duplicate_sessions as $dup) {
            echo "<p>ID: {$dup->id}, Code: {$dup->certificate_code}, Created: {$dup->created_at}</p>";
        }
    } else {
        echo "<p style='color: green;'>✅ No duplicate sessions found</p>";
    }
    
    // Check error logs for recent activity
    echo "<h2>Recent Error Log Activity:</h2>";
    $error_log_path = ABSPATH . '../logs/php/error.log';
    if (file_exists($error_log_path)) {
        $error_log = file_get_contents($error_log_path);
        $recent_errors = array_filter(explode("\n", $error_log), function($line) {
            return strpos($line, 'Certificate') !== false || strpos($line, 'TF-') !== false;
        });
        
        if (!empty($recent_errors)) {
            echo "<p><strong>Recent certificate-related log entries:</strong></p>";
            echo "<pre>" . implode("\n", array_slice($recent_errors, -10)) . "</pre>";
        } else {
            echo "<p>No recent certificate-related errors found</p>";
        }
    } else {
        echo "<p>Error log not found at: $error_log_path</p>";
    }
    
} else {
    echo "<p style='color: red;'>❌ Certificate ID 26 not found</p>";
}

echo "<h2>Check Stripe Session Processing:</h2>";
echo "<p>The issue might be that the webhook or success page is processing the same payment multiple times.</p>";
echo "<p>This could happen if:</p>";
echo "<ul>";
echo "<li>Webhook fires multiple times for the same event</li>";
echo "<li>Success page is accessed multiple times</li>";
echo "<li>There's a race condition in the processing</li>";
echo "</ul>";

echo "<h2>Recommendations:</h2>";
echo "<ol>";
echo "<li><strong>Check Stripe webhook logs</strong> - See if the same webhook is firing multiple times</li>";
echo "<li><strong>Add duplicate prevention</strong> - Check if certificate already exists before creating</li>";
echo "<li><strong>Check success page access</strong> - Make sure it's not being accessed multiple times</li>";
echo "</ol>";

?>
