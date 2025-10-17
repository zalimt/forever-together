<?php
/**
 * Debug success page processing
 * Access: http://forever-together.local/debug-success-page.php?session_id=YOUR_SESSION_ID
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Debug Success Page Processing</h1>";

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
                
                // Check if certificate already exists
                global $wpdb;
                $table_name = $wpdb->prefix . 'tf_certificates';
                $existing_cert = $wpdb->get_row($wpdb->prepare(
                    "SELECT * FROM $table_name WHERE stripe_session_id = %s",
                    $session_id
                ));
                
                if ($existing_cert) {
                    echo "<h3>Certificate Status:</h3>";
                    echo "<p style='color: orange;'>⚠️ Certificate already exists: <strong>{$existing_cert->certificate_code}</strong></p>";
                    echo "<p>This means the webhook or success page already processed this payment.</p>";
                } else {
                    echo "<h3>Certificate Status:</h3>";
                    echo "<p style='color: red;'>❌ No certificate found for this session</p>";
                    echo "<p>This means neither the webhook nor the success page processed this payment.</p>";
                    
                    // Process the certificate manually
                    echo "<h3>Processing Certificate Now:</h3>";
                    
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
                        echo "<p style='color: green;'>✅ Certificate created: <strong>$certificate_code</strong></p>";
                        
                        // Send email
                        $email_result = tf_send_certificate_email($certificate_code, $data['recipient_email'], $data);
                        
                        if ($email_result) {
                            echo "<p style='color: green;'>✅ Email sent successfully to: {$data['recipient_email']}</p>";
                        } else {
                            echo "<p style='color: red;'>❌ Email sending failed</p>";
                        }
                    } else {
                        echo "<p style='color: red;'>❌ Certificate creation failed</p>";
                    }
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
