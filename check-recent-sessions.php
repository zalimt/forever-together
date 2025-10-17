<?php
/**
 * Check recent Stripe sessions
 * Access: http://forever-together.local/check-recent-sessions.php
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Recent Stripe Sessions</h1>";

// Include Stripe integration
require_once(get_stylesheet_directory() . '/vendor/stripe/init.php');

// Get Stripe secret key
$stripe_secret_key = get_option('tf_stripe_secret_key');

if ($stripe_secret_key) {
    \Stripe\Stripe::setApiKey($stripe_secret_key);
    
    try {
        // Get recent checkout sessions
        $sessions = \Stripe\Checkout\Session::all([
            'limit' => 10,
            'expand' => ['data.customer']
        ]);
        
        echo "<h2>Last 10 Checkout Sessions:</h2>";
        
        foreach ($sessions->data as $session) {
            echo "<div style='border: 1px solid #ccc; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
            echo "<h3>Session: " . $session->id . "</h3>";
            echo "<p><strong>Created:</strong> " . date('Y-m-d H:i:s', $session->created) . "</p>";
            echo "<p><strong>Payment Status:</strong> " . $session->payment_status . "</p>";
            echo "<p><strong>Amount:</strong> " . ($session->amount_total / 100) . "€</p>";
            echo "<p><strong>Customer Email:</strong> " . ($session->customer_details->email ?? 'N/A') . "</p>";
            
            if ($session->metadata) {
                echo "<p><strong>Metadata:</strong></p>";
                echo "<ul>";
                foreach ($session->metadata as $key => $value) {
                    echo "<li>$key: $value</li>";
                }
                echo "</ul>";
            } else {
                echo "<p style='color: red;'>❌ No metadata</p>";
            }
            
            // Check if certificate exists
            global $wpdb;
            $table_name = $wpdb->prefix . 'tf_certificates';
            $existing_cert = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM $table_name WHERE stripe_session_id = %s",
                $session->id
            ));
            
            if ($existing_cert) {
                echo "<p style='color: green;'>✅ Certificate exists: {$existing_cert->certificate_code}</p>";
            } else {
                echo "<p style='color: red;'>❌ No certificate found</p>";
                echo "<p><a href='debug-success-processing.php?session_id={$session->id}' target='_blank'>Debug this session</a></p>";
            }
            
            echo "</div>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error retrieving sessions: " . $e->getMessage() . "</p>";
    }
    
} else {
    echo "<p style='color: red;'>❌ Stripe secret key not configured</p>";
}
?>
