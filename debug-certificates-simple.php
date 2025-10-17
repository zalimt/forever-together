<?php
/**
 * Simple debug page to check certificate creation
 * Access: http://forever-together.local/debug-certificates-simple.php
 */

// Load WordPress
require_once('wp-load.php');

global $wpdb;
$table_name = $wpdb->prefix . 'tf_certificates';

echo "<h1>Certificate Debug Page</h1>";

// Check if table exists
$table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");
if (!$table_exists) {
    echo "<p style='color: red;'>❌ Table $table_name does not exist!</p>";
    echo "<p>This means the certificate system hasn't been initialized.</p>";
    echo "<p>Try visiting the certificate page first to trigger table creation.</p>";
} else {
    echo "<p style='color: green;'>✅ Table $table_name exists</p>";
}

// Get recent certificates
$certificates = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC LIMIT 10");

echo "<h2>Recent Certificates (Last 10)</h2>";
if (empty($certificates)) {
    echo "<p>No certificates found in database.</p>";
} else {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Code</th><th>Beneficiary</th><th>Giver</th><th>Email</th><th>Amount</th><th>Status</th><th>Created</th></tr>";
    foreach ($certificates as $cert) {
        $status_color = $cert->is_active ? 'green' : 'red';
        $status_text = $cert->is_active ? 'Active' : 'Used';
        echo "<tr>";
        echo "<td>{$cert->id}</td>";
        echo "<td><code>{$cert->certificate_code}</code></td>";
        echo "<td>{$cert->beneficiary_name}</td>";
        echo "<td>{$cert->giver_name}</td>";
        echo "<td>{$cert->recipient_email}</td>";
        echo "<td>{$cert->amount}€</td>";
        echo "<td style='color: $status_color;'>{$status_text}</td>";
        echo "<td>{$cert->created_at}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Check Stripe settings
echo "<h2>Stripe Configuration</h2>";
$stripe_pub_key = get_option('tf_stripe_publishable_key');
$stripe_secret_key = get_option('tf_stripe_secret_key');
$stripe_webhook_secret = get_option('tf_stripe_webhook_secret');

if ($stripe_pub_key && $stripe_secret_key && $stripe_webhook_secret) {
    echo "<p style='color: green;'>✅ Stripe keys configured</p>";
} else {
    echo "<p style='color: red;'>❌ Stripe keys not fully configured</p>";
    echo "<p>Missing: ";
    if (!$stripe_pub_key) echo "Publishable Key ";
    if (!$stripe_secret_key) echo "Secret Key ";
    if (!$stripe_webhook_secret) echo "Webhook Secret ";
    echo "</p>";
}

// Check webhook endpoint
echo "<h2>Webhook Endpoint</h2>";
echo "<p>Webhook URL: <code>http://forever-together.local/wp-json/tf/v1/stripe-webhook</code></p>";
echo "<p>LocalTunnel URL: <code>https://twenty-wombats-brush.loca.lt/wp-json/tf/v1/stripe-webhook</code></p>";

// Test webhook endpoint
echo "<h2>Webhook Test</h2>";
echo "<p><a href='http://forever-together.local/wp-json/tf/v1/stripe-webhook' target='_blank'>Test webhook endpoint</a></p>";

// Check if Stripe library is loaded
echo "<h2>Stripe Library</h2>";
$stripe_file = get_stylesheet_directory() . '/vendor/stripe/init.php';
if (file_exists($stripe_file)) {
    echo "<p style='color: green;'>✅ Stripe library found</p>";
} else {
    echo "<p style='color: red;'>❌ Stripe library not found at: $stripe_file</p>";
}
?>
