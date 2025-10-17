<?php
/**
 * Reset certificates to active status for testing
 * Access: http://forever-together.local/reset-certificates.php
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Reset Certificates for Testing</h1>";

global $wpdb;
$table_name = $wpdb->prefix . 'tf_certificates';

// Get all certificates
$certificates = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");

echo "<h2>Current Certificate Status:</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Code</th><th>Status</th><th>Activated At</th><th>Activated By</th></tr>";

foreach ($certificates as $cert) {
    $status = $cert->is_active ? 'Active' : 'Used';
    $status_color = $cert->is_active ? 'green' : 'red';
    $activated_at = $cert->activated_at ?: 'Not activated';
    $activated_by = $cert->activated_by_email ?: 'Not activated';
    
    echo "<tr>";
    echo "<td><code>{$cert->certificate_code}</code></td>";
    echo "<td style='color: $status_color;'>$status</td>";
    echo "<td>$activated_at</td>";
    echo "<td>$activated_by</td>";
    echo "</tr>";
}

echo "</table>";

// Reset all certificates to active status
echo "<h2>Reset All Certificates to Active Status:</h2>";

$result = $wpdb->update(
    $table_name,
    array(
        'is_active' => 1,
        'activated_at' => NULL,
        'activated_by_email' => ''
    ),
    array(),
    array('%d', '%s', '%s')
);

if ($result !== false) {
    echo "<p style='color: green;'>✅ Reset $result certificates to active status</p>";
} else {
    echo "<p style='color: red;'>❌ Failed to reset certificates</p>";
}

echo "<h2>Updated Certificate Status:</h2>";
$certificates_after = $wpdb->get_results("SELECT certificate_code, is_active, activated_at, activated_by_email FROM $table_name ORDER BY created_at DESC");

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Code</th><th>Status</th><th>Activated At</th><th>Activated By</th></tr>";

foreach ($certificates_after as $cert) {
    $status = $cert->is_active ? 'Active' : 'Used';
    $status_color = $cert->is_active ? 'green' : 'red';
    $activated_at = $cert->activated_at ?: 'Not activated';
    $activated_by = $cert->activated_by_email ?: 'Not activated';
    
    echo "<tr>";
    echo "<td><code>{$cert->certificate_code}</code></td>";
    echo "<td style='color: $status_color;'>$status</td>";
    echo "<td>$activated_at</td>";
    echo "<td>$activated_by</td>";
    echo "</tr>";
}

echo "</table>";

echo "<p><a href='activate-certificate'>Try Certificate Activation Again</a></p>";
?>
