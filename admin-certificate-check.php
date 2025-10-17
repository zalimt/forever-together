<?php
/**
 * Admin-only certificate check page
 * Only accessible to WordPress administrators
 */

// Load WordPress with explicit path
require_once(dirname(__FILE__) . '/wp-load.php');

// Check if user is admin
if (!current_user_can('administrator')) {
    wp_die('Access denied. Admin only.');
}

echo "<h1>Certificate Status - Live Site</h1>";

// Debug database connection
global $wpdb;
echo "<h2>Database Connection Info:</h2>";
echo "<p><strong>Database Host:</strong> " . DB_HOST . "</p>";
echo "<p><strong>Database Name:</strong> " . DB_NAME . "</p>";
echo "<p><strong>Site URL:</strong> " . get_site_url() . "</p>";
echo "<p><strong>WordPress URL:</strong> " . get_option('home') . "</p>";

// Include certificate system
require_once(get_stylesheet_directory() . '/inc/certificate-system.php');

global $wpdb;
$table_name = $wpdb->prefix . 'tf_certificates';

// Check if table exists
$table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;

if (!$table_exists) {
    echo "<p style='color: red;'>❌ Certificate table doesn't exist</p>";
    exit;
}

echo "<p style='color: green;'>✅ Table $table_name exists</p>";

// Get recent certificates - sort by ID DESC to get truly latest
$certificates = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC LIMIT 20");

echo "<h2>Recent Certificates (Last 20):</h2>";

if (empty($certificates)) {
    echo "<p>No certificates found.</p>";
} else {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Code</th><th>Beneficiary</th><th>Giver</th><th>Email</th><th>Amount</th><th>Status</th><th>Created</th></tr>";
    
    foreach ($certificates as $cert) {
        $status = $cert->is_active ? 'Active' : 'Used';
        $status_color = $cert->is_active ? 'green' : 'red';
        
        echo "<tr>";
        echo "<td>{$cert->id}</td>";
        echo "<td><code>{$cert->certificate_code}</code></td>";
        echo "<td>{$cert->beneficiary_name}</td>";
        echo "<td>{$cert->giver_name}</td>";
        echo "<td>{$cert->recipient_email}</td>";
        echo "<td>{$cert->amount}€</td>";
        echo "<td style='color: $status_color;'>$status</td>";
        echo "<td>{$cert->created_at}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

echo "<h2>Stripe Configuration:</h2>";
$stripe_secret_key = get_option('tf_stripe_secret_key');
$stripe_publishable_key = get_option('tf_stripe_publishable_key');
$stripe_webhook_secret = get_option('tf_stripe_webhook_secret');

if ($stripe_secret_key && $stripe_publishable_key) {
    echo "<p style='color: green;'>✅ Stripe keys configured</p>";
} else {
    echo "<p style='color: red;'>❌ Stripe keys not configured</p>";
}

if ($stripe_webhook_secret) {
    echo "<p style='color: green;'>✅ Webhook secret configured</p>";
} else {
    echo "<p style='color: red;'>❌ Webhook secret not configured</p>";
}

echo "<h2>Site Information:</h2>";
echo "<p><strong>Site URL:</strong> " . get_site_url() . "</p>";
echo "<p><strong>Webhook URL:</strong> " . get_site_url() . "/wp-json/tf/v1/stripe-webhook</p>";
echo "<p><strong>Certificate Page:</strong> <a href='" . get_site_url() . "/certificate'>" . get_site_url() . "/certificate</a></p>";
echo "<p><strong>Activation Page:</strong> <a href='" . get_site_url() . "/activate-certificate'>" . get_site_url() . "/activate-certificate</a></p>";

?>
