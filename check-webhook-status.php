<?php
/**
 * Check webhook status and process the payment manually
 * Access: http://forever-together.local/check-webhook-status.php
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Check Webhook Status</h1>";

// Get the session ID from URL
$session_id = isset($_GET['session_id']) ? $_GET['session_id'] : '';

if ($session_id) {
    echo "<h2>Processing Session: $session_id</h2>";
    
    // Include Stripe integration
    require_once(get_stylesheet_directory() . '/inc/stripe-integration.php');
    require_once(get_stylesheet_directory() . '/inc/certificate-system.php');
    require_once(get_stylesheet_directory() . '/vendor/stripe/init.php');
    
    // Get Stripe secret key
    $stripe_secret_key = get_option('tf_stripe_secret_key');
    
    if ($stripe_secret_key) {
        \Stripe\Stripe::setApiKey($stripe_secret_key);
        
        try {
            // Retrieve the session
            $session = \Stripe\Checkout\Session::retrieve($session_id);
            
            echo "<h3>Session Details:</h3>";
            echo "<p><strong>Payment Status:</strong> " . $session->payment_status . "</p>";
            echo "<p><strong>Amount Total:</strong> " . ($session->amount_total / 100) . "€</p>";
            echo "<p><strong>Customer Email:</strong> " . $session->customer_details->email . "</p>";
            
            // Check metadata
            if ($session->metadata) {
                echo "<h3>Metadata:</h3>";
                echo "<ul>";
                foreach ($session->metadata as $key => $value) {
                    echo "<li><strong>$key:</strong> $value</li>";
                }
                echo "</ul>";
                
                // Process the certificate manually
                echo "<h3>Processing Certificate Manually:</h3>";
                
                $data = array(
                    'beneficiary_name' => $session->metadata->beneficiary_name ?? 'Unknown',
                    'beneficiary_from' => $session->metadata->beneficiary_from ?? '',
                    'giver_name' => $session->metadata->giver_name ?? 'Unknown',
                    'recipient_email' => $session->metadata->recipient_email ?? $session->customer_details->email,
                    'amount' => $session->amount_total / 100,
                    'payment_intent_id' => $session->payment_intent,
                    'stripe_session_id' => $session_id,
                );
                
                echo "<p>Creating certificate with data:</p>";
                echo "<pre>" . print_r($data, true) . "</pre>";
                
                $certificate_code = tf_save_certificate($data);
                
                if ($certificate_code) {
                    echo "<p style='color: green;'>✅ Certificate created: $certificate_code</p>";
                    
                    // Send email
                    $email_sent = tf_send_certificate_email($certificate_code, $data['recipient_email'], $data);
                    
                    if ($email_sent) {
                        echo "<p style='color: green;'>✅ Email sent successfully</p>";
                    } else {
                        echo "<p style='color: red;'>❌ Email sending failed</p>";
                    }
                } else {
                    echo "<p style='color: red;'>❌ Certificate creation failed</p>";
                }
                
            } else {
                echo "<p style='color: red;'>❌ No metadata found in session</p>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error retrieving session: " . $e->getMessage() . "</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Stripe secret key not configured</p>";
    }
    
} else {
    echo "<p>No session ID provided. Add ?session_id=cs_test_... to the URL</p>";
}

echo "<p><a href='debug-certificates-simple.php'>Check certificates in database</a></p>";
?>
