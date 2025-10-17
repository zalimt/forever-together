<?php
/**
 * Simple certificate check for live site
 * This will show which database is being used
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Live Site Certificate Check</h1>";

// Show database info
echo "<h2>Database Information:</h2>";
echo "<p><strong>Database Host:</strong> " . DB_HOST . "</p>";
echo "<p><strong>Database Name:</strong> " . DB_NAME . "</p>";
echo "<p><strong>Site URL:</strong> " . get_site_url() . "</p>";
echo "<p><strong>WordPress URL:</strong> " . get_option('home') . "</p>";

// Check if this is the live site
$site_url = get_site_url();
if (strpos($site_url, 'red-grouse-914732.hostingersite.com') !== false) {
    echo "<p style='color: green;'>✅ This is the LIVE site</p>";
} else {
    echo "<p style='color: red;'>❌ This is NOT the live site - URL: $site_url</p>";
}

// Check certificates
global $wpdb;
$table_name = $wpdb->prefix . 'tf_certificates';

echo "<h2>Certificate Table Check:</h2>";

// Check if table exists
$table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;

if (!$table_exists) {
    echo "<p style='color: red;'>❌ Certificate table doesn't exist</p>";
    exit;
}

echo "<p style='color: green;'>✅ Table $table_name exists</p>";

// Get certificate count
$total_certificates = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
echo "<p><strong>Total Certificates:</strong> $total_certificates</p>";

// Get recent certificates - sort by ID DESC to get truly latest
$certificates = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC LIMIT 10");

echo "<h2>Recent Certificates (Last 10):</h2>";

if (empty($certificates)) {
    echo "<p>No certificates found.</p>";
} else {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Code</th><th>Amount</th><th>Email</th><th>Created</th><th>Status</th></tr>";
    
    foreach ($certificates as $cert) {
        $status = $cert->is_active ? 'Active' : 'Used';
        $status_color = $cert->is_active ? 'green' : 'red';
        
        echo "<tr>";
        echo "<td>{$cert->id}</td>";
        echo "<td><code>{$cert->certificate_code}</code></td>";
        echo "<td>{$cert->amount}€</td>";
        echo "<td>{$cert->recipient_email}</td>";
        echo "<td>{$cert->created_at}</td>";
        echo "<td style='color: $status_color;'>$status</td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

echo "<h2>Stripe Configuration:</h2>";
$stripe_secret_key = get_option('tf_stripe_secret_key');
if ($stripe_secret_key) {
    echo "<p style='color: green;'>✅ Stripe configured</p>";
} else {
    echo "<p style='color: red;'>❌ Stripe not configured</p>";
}

?>
