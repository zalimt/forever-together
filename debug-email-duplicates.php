<?php
/**
 * Debug email duplicates - certificates that don't exist
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Email Duplicates Investigation</h1>";

global $wpdb;
$table_name = $wpdb->prefix . 'tf_certificates';

// Get all certificates
$certificates = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");

echo "<h2>Certificates in Database:</h2>";
echo "<p><strong>Total certificates:</strong> " . count($certificates) . "</p>";

if (empty($certificates)) {
    echo "<p style='color: red;'>❌ <strong>NO CERTIFICATES IN DATABASE!</strong></p>";
    echo "<p>This confirms the issue: emails are being sent but certificates are not being created.</p>";
} else {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Code</th><th>Amount</th><th>Created</th><th>Email</th></tr>";
    foreach ($certificates as $cert) {
        echo "<tr>";
        echo "<td>{$cert->id}</td>";
        echo "<td>{$cert->certificate_code}</td>";
        echo "<td>{$cert->amount}€</td>";
        echo "<td>{$cert->created_at}</td>";
        echo "<td>{$cert->recipient_email}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<h2>Email Function Analysis:</h2>";

// Check if the email function has error handling
$certificate_system_file = get_stylesheet_directory() . '/inc/certificate-system.php';
if (file_exists($certificate_system_file)) {
    $content = file_get_contents($certificate_system_file);
    
    // Look for the email sending function
    if (strpos($content, 'function tf_send_certificate_email') !== false) {
        echo "<p>✅ Email function exists</p>";
        
        // Check if email sending is wrapped in error handling
        if (strpos($content, 'try {') !== false && strpos($content, 'catch') !== false) {
            echo "<p>✅ Email function has error handling</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Email function may not have proper error handling</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Email function not found</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Certificate system file not found</p>";
}

echo "<h2>Possible Causes:</h2>";
echo "<ol>";
echo "<li><strong>Database connection fails</strong> - Email sends but certificate doesn't save</li>";
echo "<li><strong>Certificate creation fails</strong> - Email sends before database error</li>";
echo "<li><strong>Email queue system</strong> - Emails are queued and sent later</li>";
echo "<li><strong>SMTP plugin retry</strong> - WP Mail SMTP is retrying failed sends</li>";
echo "<li><strong>Multiple webhook calls</strong> - Same session processed multiple times</li>";
echo "</ol>";

echo "<h2>Recent Error Logs:</h2>";
$error_log_path = ABSPATH . '../logs/php/error.log';
if (file_exists($error_log_path)) {
    $error_log = file_get_contents($error_log_path);
    $lines = explode("\n", $error_log);
    
    // Look for recent certificate-related errors
    $recent_errors = array_filter($lines, function($line) {
        return strpos($line, 'certificate') !== false || 
               strpos($line, 'tf_save_certificate') !== false ||
               strpos($line, 'database error') !== false;
    });
    
    if (!empty($recent_errors)) {
        echo "<p><strong>Recent certificate-related errors:</strong></p>";
        echo "<pre>" . implode("\n", array_slice($recent_errors, -10)) . "</pre>";
    } else {
        echo "<p>No recent certificate-related errors found</p>";
    }
} else {
    echo "<p>Error log not accessible</p>";
}

echo "<h2>Immediate Solutions:</h2>";
echo "<ol>";
echo "<li><strong>Check SMTP plugin settings</strong> - Look for retry/queue settings</li>";
echo "<li><strong>Disable webhook temporarily</strong> - Stop any automatic processing</li>";
echo "<li><strong>Check email queue</strong> - Clear any pending emails</li>";
echo "<li><strong>Test certificate creation manually</strong> - Use debug tools</li>";
echo "</ol>";

?>
