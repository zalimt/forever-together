<?php
/**
 * Test webhook endpoint
 * Access: http://forever-together.local/test-webhook-endpoint.php
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Test Webhook Endpoint</h1>";

// Test the webhook URL
$webhook_url = home_url('/wp-json/tf/v1/stripe-webhook');
echo "<p><strong>Webhook URL:</strong> <a href='$webhook_url' target='_blank'>$webhook_url</a></p>";

// Test with a simple POST request
echo "<h2>Testing Webhook Endpoint:</h2>";

$test_data = json_encode([
    'type' => 'checkout.session.completed',
    'data' => [
        'object' => [
            'id' => 'cs_test_123',
            'payment_intent' => 'pi_test_123',
            'metadata' => [
                'beneficiary_name' => 'Test User',
                'beneficiary_from' => 'Test Giver',
                'giver_name' => 'Test Giver',
                'recipient_email' => 'test@example.com',
                'amount' => '50'
            ]
        ]
    ]
]);

// Use WordPress HTTP API to test
$response = wp_remote_post($webhook_url, [
    'headers' => [
        'Content-Type' => 'application/json',
        'Stripe-Signature' => 'test_signature'
    ],
    'body' => $test_data
]);

if (is_wp_error($response)) {
    echo "<p style='color: red;'>❌ Error: " . $response->get_error_message() . "</p>";
} else {
    $status_code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);
    
    echo "<p><strong>Status Code:</strong> $status_code</p>";
    echo "<p><strong>Response Body:</strong></p>";
    echo "<pre>" . htmlspecialchars($body) . "</pre>";
    
    if ($status_code === 200) {
        echo "<p style='color: green;'>✅ Webhook endpoint working</p>";
    } else {
        echo "<p style='color: red;'>❌ Webhook endpoint returned error: $status_code</p>";
    }
}

echo "<h2>Check Recent Certificates:</h2>";
echo "<p><a href='debug-certificates-simple.php'>Check certificates in database</a></p>";

echo "<h2>Manual Session Processing:</h2>";
echo "<p>If webhook is not working, you can manually process sessions:</p>";
echo "<p><a href='check-recent-sessions.php'>Check recent sessions</a></p>";
?>
