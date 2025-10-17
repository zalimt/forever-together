<?php
/**
 * Investigate how certificates are being created
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Investigate Certificate Creation Source</h1>";

global $wpdb;
$table_name = $wpdb->prefix . 'tf_certificates';

// Get the latest certificate
$latest_cert = $wpdb->get_row("SELECT * FROM $table_name ORDER BY id DESC LIMIT 1");

if ($latest_cert) {
    echo "<h2>Latest Certificate Details:</h2>";
    echo "<p><strong>ID:</strong> {$latest_cert->id}</p>";
    echo "<p><strong>Code:</strong> {$latest_cert->certificate_code}</p>";
    echo "<p><strong>Amount:</strong> {$latest_cert->amount}€</p>";
    echo "<p><strong>Created:</strong> {$latest_cert->created_at}</p>";
    echo "<p><strong>Stripe Session ID:</strong> {$latest_cert->stripe_session_id}</p>";
    echo "<p><strong>Payment Intent ID:</strong> {$latest_cert->payment_intent_id}</p>";
    
    // Check if this session exists in Stripe
    echo "<h2>Stripe Session Check:</h2>";
    echo "<p>Session ID: <code>{$latest_cert->stripe_session_id}</code></p>";
    echo "<p>Payment Intent: <code>{$latest_cert->payment_intent_id}</code></p>";
    
    // Check error logs for certificate creation
    echo "<h2>Error Log Analysis:</h2>";
    $error_log_path = ABSPATH . '../logs/php/error.log';
    if (file_exists($error_log_path)) {
        $error_log = file_get_contents($error_log_path);
        $certificate_entries = array_filter(explode("\n", $error_log), function($line) use ($latest_cert) {
            return strpos($line, $latest_cert->certificate_code) !== false || 
                   strpos($line, 'Certificate created') !== false ||
                   strpos($line, 'Success page processing') !== false;
        });
        
        if (!empty($certificate_entries)) {
            echo "<p><strong>Recent certificate-related log entries:</strong></p>";
            echo "<pre>" . implode("\n", array_slice($certificate_entries, -5)) . "</pre>";
        } else {
            echo "<p>No recent certificate creation logs found</p>";
        }
    }
    
    // Check if certificate was created via webhook or success page
    echo "<h2>Creation Method Analysis:</h2>";
    if (strpos($latest_cert->stripe_session_id, 'cs_test_') === 0) {
        echo "<p>✅ Valid Stripe session ID format</p>";
        
        // Try to retrieve session details
        $stripe_secret_key = get_option('tf_stripe_secret_key');
        if ($stripe_secret_key) {
            require_once(get_stylesheet_directory() . '/vendor/stripe/init.php');
            \Stripe\Stripe::setApiKey($stripe_secret_key);
            
            try {
                $session = \Stripe\Checkout\Session::retrieve($latest_cert->stripe_session_id);
                echo "<p><strong>Session Details:</strong></p>";
                echo "<p>Payment Status: {$session->payment_status}</p>";
                echo "<p>Amount: " . ($session->amount_total / 100) . "€</p>";
                echo "<p>Customer Email: {$session->customer_details->email}</p>";
                echo "<p>Created: " . date('Y-m-d H:i:s', $session->created) . "</p>";
                
                if ($session->metadata) {
                    echo "<p><strong>Metadata:</strong></p>";
                    echo "<pre>" . print_r($session->metadata->toArray(), true) . "</pre>";
                }
                
            } catch (Exception $e) {
                echo "<p style='color: red;'>❌ Error retrieving session: " . $e->getMessage() . "</p>";
            }
        }
    }
    
} else {
    echo "<p>No certificates found</p>";
}

echo "<h2>Possible Creation Sources:</h2>";
echo "<ol>";
echo "<li><strong>Manual creation</strong> - Via debug tools or admin</li>";
echo "<li><strong>Webhook processing</strong> - Stripe webhook (but you said no repeated events)</li>";
echo "<li><strong>Success page access</strong> - Direct URL access with session ID</li>";
echo "<li><strong>AJAX call</strong> - Direct function call</li>";
echo "<li><strong>Database direct insert</strong> - Manual database insertion</li>";
echo "</ol>";

echo "<h2>Recommendations:</h2>";
echo "<ol>";
echo "<li><strong>Check browser history</strong> - See if you accidentally accessed a success page URL</li>";
echo "<li><strong>Check debug tool usage</strong> - See if any debug scripts were run</li>";
echo "<li><strong>Monitor in real-time</strong> - Watch for new certificate creation</li>";
echo "<li><strong>Enable detailed logging</strong> - Add more logging to certificate creation</li>";
echo "</ol>";

?>
