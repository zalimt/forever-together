<?php
/**
 * Send test emails for existing certificates
 * Access: http://forever-together.local/send-test-emails.php
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Send Test Emails for Existing Certificates</h1>";

// Include certificate system
require_once(get_stylesheet_directory() . '/inc/certificate-system.php');

global $wpdb;
$table_name = $wpdb->prefix . 'tf_certificates';

// Get recent certificates
$certificates = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC LIMIT 5");

echo "<h2>Recent Certificates:</h2>";

if (empty($certificates)) {
    echo "<p>No certificates found.</p>";
} else {
    foreach ($certificates as $cert) {
        echo "<div style='border: 1px solid #ccc; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
        echo "<h3>Certificate: {$cert->certificate_code}</h3>";
        echo "<p><strong>Beneficiary:</strong> {$cert->beneficiary_name}</p>";
        echo "<p><strong>Giver:</strong> {$cert->giver_name}</p>";
        echo "<p><strong>Email:</strong> {$cert->recipient_email}</p>";
        echo "<p><strong>Amount:</strong> {$cert->amount}€</p>";
        echo "<p><strong>Created:</strong> {$cert->created_at}</p>";
        
        // Test sending email
        echo "<h4>Test Email Sending:</h4>";
        
        $data = array(
            'beneficiary_name' => $cert->beneficiary_name,
            'beneficiary_from' => $cert->beneficiary_from,
            'giver_name' => $cert->giver_name,
            'recipient_email' => $cert->recipient_email,
            'amount' => $cert->amount,
        );
        
        $email_result = tf_send_certificate_email($cert->certificate_code, $cert->recipient_email, $data);
        
        if ($email_result) {
            echo "<p style='color: green;'>✅ Email sent successfully to: {$cert->recipient_email}</p>";
        } else {
            echo "<p style='color: red;'>❌ Email sending failed for: {$cert->recipient_email}</p>";
        }
        
        echo "</div>";
    }
}

echo "<h2>Test Individual Email Function:</h2>";
echo "<form method='post'>";
echo "<p>Send test email to: <input type='email' name='test_email' placeholder='test@example.com' required></p>";
echo "<p><input type='submit' name='send_test' value='Send Test Email'></p>";
echo "</form>";

if (isset($_POST['send_test'])) {
    $test_email = sanitize_email($_POST['test_email']);
    
    $test_data = array(
        'beneficiary_name' => 'Test User',
        'beneficiary_from' => 'Test Giver',
        'giver_name' => 'Test Giver',
        'recipient_email' => $test_email,
        'amount' => 50.00,
    );
    
    $result = tf_send_certificate_email('TF-TEST-1234', $test_email, $test_data);
    
    if ($result) {
        echo "<p style='color: green;'>✅ Test email sent successfully to: $test_email</p>";
    } else {
        echo "<p style='color: red;'>❌ Test email failed for: $test_email</p>";
    }
}

echo "<p><a href='debug-certificates-simple.php'>Back to debug page</a></p>";
?>
