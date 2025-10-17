<?php
/**
 * Debug success page processing flow
 * Access: http://forever-together.local/debug-success-page-flow.php
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Debug Success Page Processing Flow</h1>";

// Check if we're on the success page
$session_id = isset($_GET['session_id']) ? $_GET['session_id'] : '';

if ($session_id) {
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
    
} else {
    echo "<p>No session ID provided. Add ?session_id=cs_test_... to the URL</p>";
    
    // Show recent sessions
    echo "<h2>Recent Sessions:</h2>";
    echo "<p><a href='check-recent-sessions.php'>Check Recent Sessions</a></p>";
}

echo "<h2>Success Page Template Check:</h2>";
$success_page_path = get_stylesheet_directory() . '/page-certificate-success.php';
if (file_exists($success_page_path)) {
    echo "<p style='color: green;'>✅ Success page template exists: $success_page_path</p>";
} else {
    echo "<p style='color: red;'>❌ Success page template not found</p>";
}

echo "<h2>WordPress Page Check:</h2>";
$success_page = get_page_by_path('certificate-success');
if ($success_page) {
    echo "<p style='color: green;'>✅ WordPress page 'certificate-success' exists (ID: {$success_page->ID})</p>";
    echo "<p>Status: {$success_page->post_status}</p>";
} else {
    echo "<p style='color: red;'>❌ WordPress page 'certificate-success' not found</p>";
}

?>
