<?php
/**
 * Test fresh certificate purchase with metadata
 * Access: http://forever-together.local/test-fresh-purchase.php
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Test Fresh Certificate Purchase</h1>";

// Include Stripe integration
require_once(get_stylesheet_directory() . '/inc/stripe-integration.php');
require_once(get_stylesheet_directory() . '/inc/certificate-system.php');
require_once(get_stylesheet_directory() . '/vendor/stripe/init.php');

// Get Stripe secret key
$stripe_secret_key = get_option('tf_stripe_secret_key');

if ($stripe_secret_key) {
    \Stripe\Stripe::setApiKey($stripe_secret_key);
    
    echo "<h2>Create Test Session with Metadata:</h2>";
    
    // Test data
    $test_data = array(
        'beneficiary_name' => 'Fresh Test User',
        'beneficiary_from' => 'Fresh Test Giver',
        'giver_name' => 'Fresh Test Giver',
        'recipient_email' => 'zalim.tsorion@gmail.com',
        'amount' => 75.00,
    );
    
    echo "<p>Creating session with data:</p>";
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
        
        echo "<h3>Session Created Successfully:</h3>";
        echo "<p><strong>Session ID:</strong> " . $session->id . "</p>";
        echo "<p><strong>Metadata:</strong></p>";
        echo "<pre>" . print_r($session->metadata->toArray(), true) . "</pre>";
        
        echo "<h3>Test the Session:</h3>";
        echo "<p><a href='" . $session->url . "' target='_blank' style='background: #6772e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Complete Test Payment</a></p>";
        
        echo "<h3>After Payment:</h3>";
        echo "<p>1. Complete the payment with test card: <code>4242 4242 4242 4242</code></p>";
        echo "<p>2. Check if you receive the certificate email</p>";
        echo "<p>3. If no email, check: <a href='debug-success-processing.php?session_id=" . $session->id . "'>Debug this session</a></p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error creating session: " . $e->getMessage() . "</p>";
    }
    
} else {
    echo "<p style='color: red;'>❌ Stripe secret key not configured</p>";
}
?>
