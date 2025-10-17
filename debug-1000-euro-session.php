<?php
/**
 * Debug the €1000 session specifically
 * Access: http://forever-together.local/debug-1000-euro-session.php
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Debug €1000 Session</h1>";

$session_id = 'cs_test_a1zWrNatpWeTSQy1jM2EwSLFmWESOrrA41fpidwpGdl88X1wHf4u07eSe9';

echo "<h2>Processing Session: $session_id</h2>";

// Include required files
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
        echo "<p><strong>Customer Email:</strong> " . ($session->customer_details->email ?? 'N/A') . "</p>";
        echo "<p><strong>Created:</strong> " . date('Y-m-d H:i:s', $session->created) . "</p>";
        
        // Check metadata
        if ($session->metadata && !empty($session->metadata->toArray())) {
            echo "<h3>Metadata:</h3>";
            echo "<pre>" . print_r($session->metadata->toArray(), true) . "</pre>";
        } else {
            echo "<p style='color: red;'>❌ No metadata found in session</p>";
        }
        
        // Check if certificate already exists
        global $wpdb;
        $table_name = $wpdb->prefix . 'tf_certificates';
        $existing_cert = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE stripe_session_id = %s",
            $session_id
        ));
        
        echo "<h3>Certificate Check:</h3>";
        if ($existing_cert) {
            echo "<p style='color: orange;'>⚠️ Certificate already exists: <strong>{$existing_cert->certificate_code}</strong></p>";
            echo "<p>Created: {$existing_cert->created_at}</p>";
        } else {
            echo "<p style='color: red;'>❌ No certificate found for this session</p>";
            
            // Check if payment was successful
            if ($session->payment_status === 'paid') {
                echo "<h3>Processing Certificate Now:</h3>";
                
                // Get metadata from session, with fallbacks
                $beneficiary_name = '';
                $beneficiary_from = '';
                $giver_name = '';
                $recipient_email = '';
                
                if ($session->metadata && !empty($session->metadata->toArray())) {
                    $beneficiary_name = $session->metadata->beneficiary_name ?? '';
                    $beneficiary_from = $session->metadata->beneficiary_from ?? '';
                    $giver_name = $session->metadata->giver_name ?? '';
                    $recipient_email = $session->metadata->recipient_email ?? '';
                }
                
                // Use fallback values if metadata is empty
                $data = array(
                    'beneficiary_name' => !empty($beneficiary_name) ? $beneficiary_name : 'Certificate Recipient',
                    'beneficiary_from' => $beneficiary_from,
                    'giver_name' => !empty($giver_name) ? $giver_name : 'Anonymous Donor',
                    'recipient_email' => !empty($recipient_email) ? $recipient_email : $session->customer_details->email,
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
            } else {
                echo "<p style='color: red;'>❌ Payment not completed (Status: {$session->payment_status})</p>";
            }
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error retrieving session: " . $e->getMessage() . "</p>";
    }
    
} else {
    echo "<p style='color: red;'>❌ Stripe secret key not configured</p>";
}

echo "<h2>Success Page Test:</h2>";
$success_url = home_url('/certificate-success') . '?session_id=' . $session_id;
echo "<p><strong>Success URL:</strong> <a href='$success_url' target='_blank'>$success_url</a></p>";
echo "<p>Click this link to test if the success page processes the session automatically.</p>";

?>
