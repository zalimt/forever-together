<?php
/**
 * Check certificate status and activation details
 * Access: http://forever-together.local/check-certificate-status.php?code=TF-090F-CBE1
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Check Certificate Status</h1>";

$certificate_code = isset($_GET['code']) ? $_GET['code'] : '';

if ($certificate_code) {
    echo "<h2>Checking Certificate: $certificate_code</h2>";
    
    // Include certificate system
    require_once(get_stylesheet_directory() . '/inc/certificate-system.php');
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'tf_certificates';
    
    $certificate = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE certificate_code = %s",
        $certificate_code
    ));
    
    if ($certificate) {
        echo "<h3>Certificate Found:</h3>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Field</th><th>Value</th></tr>";
        foreach ($certificate as $field => $value) {
            echo "<tr>";
            echo "<td><strong>$field</strong></td>";
            echo "<td>$value</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<h3>Status Analysis:</h3>";
        if ($certificate->is_active == 1) {
            echo "<p style='color: green;'>✅ Certificate is ACTIVE (can be activated)</p>";
        } else {
            echo "<p style='color: red;'>❌ Certificate is INACTIVE (already used)</p>";
        }
        
        if ($certificate->activated_at) {
            echo "<p><strong>Activated at:</strong> {$certificate->activated_at}</p>";
        } else {
            echo "<p><strong>Activated at:</strong> Not activated yet</p>";
        }
        
        if ($certificate->activated_by_email) {
            echo "<p><strong>Activated by:</strong> {$certificate->activated_by_email}</p>";
        } else {
            echo "<p><strong>Activated by:</strong> Not activated yet</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Certificate not found: $certificate_code</p>";
    }
    
} else {
    echo "<p>Please provide a certificate code: ?code=TF-XXXX-XXXX</p>";
    
    // Show recent certificates
    echo "<h2>Recent Certificates:</h2>";
    global $wpdb;
    $table_name = $wpdb->prefix . 'tf_certificates';
    
    $certificates = $wpdb->get_results("SELECT certificate_code, is_active, activated_at, activated_by_email FROM $table_name ORDER BY created_at DESC LIMIT 10");
    
    if ($certificates) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Code</th><th>Status</th><th>Activated At</th><th>Activated By</th><th>Action</th></tr>";
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
            echo "<td><a href='?code={$cert->certificate_code}'>Check Status</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

echo "<p><a href='activate-certificate'>Back to Activation Page</a></p>";
?>
