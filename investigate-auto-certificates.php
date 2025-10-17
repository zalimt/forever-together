<?php
/**
 * Investigate automatic certificate creation
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Investigate Automatic Certificate Creation</h1>";

global $wpdb;
$table_name = $wpdb->prefix . 'tf_certificates';

// Get all certificates
$certificates = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");

echo "<h2>All Certificates in Database:</h2>";
echo "<p><strong>Total:</strong> " . count($certificates) . "</p>";

if (!empty($certificates)) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Code</th><th>Amount</th><th>Created</th><th>Session ID</th></tr>";
    
    foreach ($certificates as $cert) {
        echo "<tr>";
        echo "<td>{$cert->id}</td>";
        echo "<td><code>{$cert->certificate_code}</code></td>";
        echo "<td>{$cert->amount}€</td>";
        echo "<td>{$cert->created_at}</td>";
        echo "<td>" . substr($cert->stripe_session_id, 0, 20) . "...</td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

// Check recent Stripe sessions
echo "<h2>Recent Stripe Sessions:</h2>";
echo "<p>Check your Stripe dashboard for recent sessions that might be triggering webhooks.</p>";

// Check webhook endpoint
echo "<h2>Webhook Status:</h2>";
echo "<p><strong>Webhook URL:</strong> " . get_site_url() . "/wp-json/tf/v1/stripe-webhook</p>";
echo "<p>Check your Stripe dashboard to see if webhooks are firing repeatedly.</p>";

echo "<h2>⚠️ Emergency Actions Taken:</h2>";
echo "<ul>";
echo "<li>✅ Automatic certificate processing DISABLED</li>";
echo "<li>✅ Success page will no longer create certificates automatically</li>";
echo "<li>⚠️ Manual certificate creation still available via debug tools</li>";
echo "</ul>";

echo "<h2>Next Steps:</h2>";
echo "<ol>";
echo "<li><strong>Check Stripe Dashboard</strong> - Look for repeated webhook events</li>";
echo "<li><strong>Disable webhook temporarily</strong> - In Stripe dashboard, disable the webhook</li>";
echo "<li><strong>Clear certificates again</strong> - Use the clear script to remove unwanted certificates</li>";
echo "<li><strong>Investigate webhook logs</strong> - See what events are being sent</li>";
echo "</ol>";

echo "<h2>Manual Certificate Creation:</h2>";
echo "<p>If you need to create certificates manually, use the debug tools:</p>";
echo "<p><a href='debug-all-certificates.php'>Check certificates</a></p>";
echo "<p><a href='clear-all-certificates.php'>Clear certificates</a></p>";

?>
