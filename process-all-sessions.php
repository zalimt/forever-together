<?php
/**
 * Process all recent sessions without certificates
 * Access: http://forever-together.local/process-all-sessions.php
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Process All Recent Sessions</h1>";

// Include Stripe integration
require_once(get_stylesheet_directory() . '/inc/stripe-integration.php');
require_once(get_stylesheet_directory() . '/inc/certificate-system.php');
require_once(get_stylesheet_directory() . '/vendor/stripe/init.php');

// Get Stripe secret key
$stripe_secret_key = get_option('tf_stripe_secret_key');

if ($stripe_secret_key) {
    \Stripe\Stripe::setApiKey($stripe_secret_key);
    
    // List of sessions to process (from your recent sessions)
    $sessions_to_process = [
        'cs_test_a1ytbzHwgcTiYsGQ3wJZYmtu7mOX7WLotw9D2x6uc0RPZt3RWWJ6DWsaQS', // €250
        'cs_test_a1X8PNDFStcoWPLxxjWfjc1KvQDx8TPVjiKdFxfvyu4HV94o1PKa41tfd8', // €333
        'cs_test_a1MiHJP1WpzWSm9rAcaGqtuiWsiizoQb9qeVigR9xh8annF5dqAbKhXDbA', // €500
        'cs_test_a1aDW449ivZtNt1jQ8jT7v3NvxByMkLcbcOKJLkqxF64FNsA4BGmwdMQKP', // €2341
        'cs_test_a1gMW0fYTAHv0gzgj7tWHT7K9SL5iwtnQCLDDz7QBqfXu3tb6KSqbc0JkS', // €250
        'cs_test_a1gPeAeZZOnAk37VFhoo9z8wTxN58ZAwvqF7WZgXyvdonaNYT5vFilye0c', // €500
        'cs_test_a1IOSKdhd5kcLwedb5VT5aEFkR8rykejOpobPKb4DVYM22n0gIrQa0hoeb', // €25
        'cs_test_a1vtvATuYcLIprOfOb1RqE0yYT32narNrzUGjd5gf5hKQ9oUct5CQH1WRm', // €200
    ];
    
    foreach ($sessions_to_process as $session_id) {
        echo "<div style='border: 1px solid #ccc; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
        echo "<h3>Processing Session: $session_id</h3>";
        
        try {
            // Retrieve the session
            $session = \Stripe\Checkout\Session::retrieve($session_id);
            
            echo "<p><strong>Amount:</strong> " . ($session->amount_total / 100) . "€</p>";
            echo "<p><strong>Customer Email:</strong> " . ($session->customer_details->email ?? 'N/A') . "</p>";
            
            // Check if certificate already exists
            global $wpdb;
            $table_name = $wpdb->prefix . 'tf_certificates';
            $existing_cert = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM $table_name WHERE stripe_session_id = %s",
                $session_id
            ));
            
            if ($existing_cert) {
                echo "<p style='color: orange;'>⚠️ Certificate already exists: {$existing_cert->certificate_code}</p>";
            } else {
                // Create certificate with available data
                $data = array(
                    'beneficiary_name' => 'Certificate Recipient',
                    'beneficiary_from' => '',
                    'giver_name' => 'Anonymous Donor',
                    'recipient_email' => $session->customer_details->email ?? 'test@example.com',
                    'amount' => $session->amount_total / 100,
                    'payment_intent_id' => $session->payment_intent,
                    'stripe_session_id' => $session_id,
                );
                
                $certificate_code = tf_save_certificate($data);
                
                if ($certificate_code) {
                    echo "<p style='color: green;'>✅ Certificate created: <strong>$certificate_code</strong></p>";
                    
                    // Send email
                    $email_result = tf_send_certificate_email($certificate_code, $data['recipient_email'], $data);
                    
                    if ($email_result) {
                        echo "<p style='color: green;'>✅ Email sent to: {$data['recipient_email']}</p>";
                    } else {
                        echo "<p style='color: red;'>❌ Email sending failed</p>";
                    }
                } else {
                    echo "<p style='color: red;'>❌ Certificate creation failed</p>";
                }
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
        }
        
        echo "</div>";
    }
    
} else {
    echo "<p style='color: red;'>❌ Stripe secret key not configured</p>";
}

echo "<h2>Summary:</h2>";
echo "<p><a href='debug-certificates-simple.php'>Check all certificates in database</a></p>";
?>
