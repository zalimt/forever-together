<?php
/**
 * Test Stripe session creation with metadata
 * Access: http://forever-together.local/test-stripe-session.php
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Test Stripe Session Creation</h1>";

// Include Stripe integration
require_once(get_stylesheet_directory() . '/inc/stripe-integration.php');
require_once(get_stylesheet_directory() . '/vendor/stripe/init.php');

// Get Stripe secret key
$stripe_secret_key = get_option('tf_stripe_secret_key');

if ($stripe_secret_key) {
    \Stripe\Stripe::setApiKey($stripe_secret_key);
    
    // Test data
    $test_data = array(
        'beneficiary_name' => 'Test User',
        'beneficiary_from' => 'Test Giver',
        'giver_name' => 'Test Giver',
        'recipient_email' => 'test@example.com',
        'amount' => 50.00,
    );
    
    echo "<h2>Creating test session with data:</h2>";
    echo "<pre>" . print_r($test_data, true) . "</pre>";
    
    try {
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Together Forever Benefactor Certificate',
                        'description' => 'For: ' . $test_data['beneficiary_name'],
                    ],
                    'unit_amount' => $test_data['amount'] * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => home_url('/certificate-success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => home_url('/certificate') . '?canceled=true',
            'metadata' => $test_data,
        ]);
        
        echo "<h2>Session Created:</h2>";
        echo "<p><strong>Session ID:</strong> " . $session->id . "</p>";
        echo "<p><strong>Metadata:</strong></p>";
        echo "<pre>" . print_r($session->metadata->toArray(), true) . "</pre>";
        
        echo "<h2>Test the session:</h2>";
        echo "<p><a href='" . $session->url . "' target='_blank'>Go to Stripe Checkout</a></p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error creating session: " . $e->getMessage() . "</p>";
    }
    
} else {
    echo "<p style='color: red;'>❌ Stripe secret key not configured</p>";
}
?>
