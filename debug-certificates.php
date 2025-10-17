<?php
/**
 * Debug page to check certificate creation
 * Access: http://forever-together.local/debug-certificates.php
 */

// Load WordPress
require_once('wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    die('Access denied. Admin only.');
}

global $wpdb;
$table_name = $wpdb->prefix . 'tf_certificates';

echo "<h1>Certificate Debug Page</h1>";

// Check if table exists
$table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");
if (!$table_exists) {
    echo "<p style='color: red;'>❌ Table $table_name does not exist!</p>";
    echo "<p>Run this command to create it:</p>";
    echo "<code>tf_create_certificates_table();</code>";
} else {
    echo "<p style='color: green;'>✅ Table $table_name exists</p>";
}

// Get recent certificates
$certificates = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC LIMIT 10");

echo "<h2>Recent Certificates (Last 10)</h2>";
if (empty($certificates)) {
    echo "<p>No certificates found.</p>";
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

// Check email settings
echo "<h2>Email Configuration</h2>";
$smtp_configured = get_option('tf_stripe_publishable_key');
if ($smtp_configured) {
    echo "<p style='color: green;'>✅ Stripe keys configured</p>";
} else {
    echo "<p style='color: red;'>❌ Stripe keys not configured</p>";
}

// Test email function
echo "<h2>Test Email Function</h2>";
echo "<form method='post'>";
echo "<input type='email' name='test_email' placeholder='test@example.com' required>";
echo "<input type='submit' name='send_test_email' value='Send Test Email'>";
echo "</form>";

if (isset($_POST['send_test_email'])) {
    $test_email = sanitize_email($_POST['test_email']);
    $result = wp_mail($test_email, 'Test Email', 'This is a test email from WordPress.');
    if ($result) {
        echo "<p style='color: green;'>✅ Test email sent successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Test email failed. Check SMTP configuration.</p>";
    }
}

// Check webhook endpoint
echo "<h2>Webhook Endpoint</h2>";
echo "<p>Webhook URL: <code>http://forever-together.local/wp-json/tf/v1/stripe-webhook</code></p>";
echo "<p>Test webhook: <a href='http://forever-together.local/wp-json/tf/v1/stripe-webhook' target='_blank'>Click here</a></p>";
?>
