<?php
/**
 * Check how the mystery certificate was created
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Certificate Creation Mystery Investigation</h1>";

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
    echo "<p><strong>Beneficiary:</strong> {$latest_cert->beneficiary_name}</p>";
    echo "<p><strong>From:</strong> {$latest_cert->beneficiary_from}</p>";
    echo "<p><strong>Giver:</strong> {$latest_cert->giver_name}</p>";
    echo "<p><strong>Email:</strong> {$latest_cert->recipient_email}</p>";
    
    // Check if this session exists in Stripe
    echo "<h2>Stripe Session Verification:</h2>";
    $stripe_secret_key = get_option('tf_stripe_secret_key');
    
    if ($stripe_secret_key && !empty($latest_cert->stripe_session_id)) {
        require_once(get_stylesheet_directory() . '/vendor/stripe/init.php');
        \Stripe\Stripe::setApiKey($stripe_secret_key);
        
        try {
            $session = \Stripe\Checkout\Session::retrieve($latest_cert->stripe_session_id);
            echo "<p>✅ <strong>Session exists in Stripe</strong></p>";
            echo "<p><strong>Payment Status:</strong> {$session->payment_status}</p>";
            echo "<p><strong>Amount:</strong> " . ($session->amount_total / 100) . "€</p>";
            echo "<p><strong>Customer Email:</strong> {$session->customer_details->email}</p>";
            echo "<p><strong>Session Created:</strong> " . date('Y-m-d H:i:s', $session->created) . "</p>";
            
            if ($session->metadata) {
                echo "<p><strong>Metadata:</strong></p>";
                echo "<pre>" . print_r($session->metadata->toArray(), true) . "</pre>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ <strong>Session NOT found in Stripe:</strong> " . $e->getMessage() . "</p>";
            echo "<p>This suggests the certificate was created without a valid Stripe session!</p>";
        }
    } else {
        echo "<p>❌ <strong>No Stripe secret key configured</strong> or empty session ID</p>";
    }
    
    // Check WordPress error logs
    echo "<h2>Error Log Analysis:</h2>";
    $error_log_path = ABSPATH . '../logs/php/error.log';
    if (file_exists($error_log_path)) {
        $error_log = file_get_contents($error_log_path);
        
        // Look for certificate-related entries around the creation time
        $cert_time = strtotime($latest_cert->created_at);
        $search_patterns = [
            $latest_cert->certificate_code,
            'Certificate created',
            'Success page processing',
            'tf_save_certificate',
            $latest_cert->stripe_session_id
        ];
        
        $relevant_entries = [];
        $lines = explode("\n", $error_log);
        
        foreach ($lines as $line) {
            if (empty($line)) continue;
            
            foreach ($search_patterns as $pattern) {
                if (strpos($line, $pattern) !== false) {
                    $relevant_entries[] = $line;
                    break;
                }
            }
        }
        
        if (!empty($relevant_entries)) {
            echo "<p><strong>Recent certificate-related log entries:</strong></p>";
            echo "<pre>" . implode("\n", array_slice($relevant_entries, -10)) . "</pre>";
        } else {
            echo "<p>No relevant certificate creation logs found</p>";
        }
    } else {
        echo "<p>Error log file not found at: $error_log_path</p>";
    }
    
    // Check if automatic processing was enabled when this certificate was created
    echo "<h2>System Status Analysis:</h2>";
    echo "<p><strong>Current automatic processing status:</strong> ";
    
    // Check if the success page processing is currently disabled
    $success_page_content = file_get_contents(get_stylesheet_directory() . '/certificate-success.php');
    if (strpos($success_page_content, 'if (false && isset($_GET[\'session_id\']))') !== false) {
        echo "❌ <strong>DISABLED</strong> (automatic processing is currently off)</p>";
    } else {
        echo "✅ <strong>ENABLED</strong> (automatic processing is currently on)</p>";
    }
    
    echo "<p><strong>Possible creation sources:</strong></p>";
    echo "<ol>";
    echo "<li><strong>Webhook processing</strong> - Stripe webhook fired automatically</li>";
    echo "<li><strong>Success page access</strong> - Someone accessed the success page URL</li>";
    echo "<li><strong>Manual creation</strong> - Via debug tools or admin</li>";
    echo "<li><strong>Database direct insert</strong> - Manual database manipulation</li>";
    echo "<li><strong>AJAX call</strong> - Direct function call from frontend</li>";
    echo "</ol>";
    
} else {
    echo "<p>No certificates found in database</p>";
}

echo "<h2>Recommendations:</h2>";
echo "<ol>";
echo "<li><strong>Check browser history</strong> - See if you accidentally accessed a success page URL</li>";
echo "<li><strong>Check if webhook is still active</strong> - In Stripe dashboard</li>";
echo "<li><strong>Monitor for new certificates</strong> - Watch if more appear automatically</li>";
echo "<li><strong>Clear unwanted certificate</strong> - Use the clear script to remove it</li>";
echo "</ol>";

?>
