<?php
/**
 * Check Stripe configuration
 * Access: http://forever-together.local/check-stripe-config.php
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Stripe Configuration Check</h1>";

// Check Stripe keys
$stripe_secret_key = get_option('tf_stripe_secret_key');
$stripe_publishable_key = get_option('tf_stripe_publishable_key');
$stripe_webhook_secret = get_option('tf_stripe_webhook_secret');

echo "<h2>Stripe API Keys:</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Key Type</th><th>Status</th><th>Value (masked)</th></tr>";

echo "<tr>";
echo "<td>Secret Key</td>";
if ($stripe_secret_key) {
    echo "<td style='color: green;'>✅ Configured</td>";
    echo "<td>" . substr($stripe_secret_key, 0, 8) . "..." . substr($stripe_secret_key, -4) . "</td>";
} else {
    echo "<td style='color: red;'>❌ Not configured</td>";
    echo "<td>-</td>";
}
echo "</tr>";

echo "<tr>";
echo "<td>Publishable Key</td>";
if ($stripe_publishable_key) {
    echo "<td style='color: green;'>✅ Configured</td>";
    echo "<td>" . substr($stripe_publishable_key, 0, 8) . "..." . substr($stripe_publishable_key, -4) . "</td>";
} else {
    echo "<td style='color: red;'>❌ Not configured</td>";
    echo "<td>-</td>";
}
echo "</tr>";

echo "<tr>";
echo "<td>Webhook Secret</td>";
if ($stripe_webhook_secret) {
    echo "<td style='color: green;'>✅ Configured</td>";
    echo "<td>" . substr($stripe_webhook_secret, 0, 8) . "..." . substr($stripe_webhook_secret, -4) . "</td>";
} else {
    echo "<td style='color: red;'>❌ Not configured</td>";
    echo "<td>-</td>";
}
echo "</tr>";

echo "</table>";

// Check Stripe library
echo "<h2>Stripe Library:</h2>";
$stripe_lib_path = get_stylesheet_directory() . '/vendor/stripe/init.php';
if (file_exists($stripe_lib_path)) {
    echo "<p style='color: green;'>✅ Stripe library found at: $stripe_lib_path</p>";
} else {
    echo "<p style='color: red;'>❌ Stripe library not found at: $stripe_lib_path</p>";
}

// Test Stripe session creation
echo "<h2>Test Stripe Session Creation:</h2>";
if ($stripe_secret_key && $stripe_publishable_key) {
    echo "<p style='color: green;'>✅ Stripe keys are configured - system should use real Stripe payments</p>";
    
    // Include Stripe integration
    require_once(get_stylesheet_directory() . '/inc/stripe-integration.php');
    require_once(get_stylesheet_directory() . '/vendor/stripe/init.php');
    
    try {
        \Stripe\Stripe::setApiKey($stripe_secret_key);
        
        $test_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Test Certificate',
                    ],
                    'unit_amount' => 1000, // €10.00
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => home_url('/certificate-success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => home_url('/certificate') . '?canceled=true',
            'metadata' => [
                'test' => 'true',
            ],
        ]);
        
        echo "<p style='color: green;'>✅ Stripe session creation works!</p>";
        echo "<p><strong>Test Session ID:</strong> " . $test_session->id . "</p>";
        
        // Clean up test session
        $test_session->expire();
        echo "<p>Test session expired and cleaned up.</p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Stripe session creation failed: " . $e->getMessage() . "</p>";
    }
    
} else {
    echo "<p style='color: orange;'>⚠️ Stripe keys not configured - system is in demo mode</p>";
}

echo "<h2>Recommendations:</h2>";
if (!$stripe_secret_key || !$stripe_publishable_key) {
    echo "<p style='color: red;'>❌ Configure Stripe API keys in WordPress admin:</p>";
    echo "<ol>";
    echo "<li>Go to WordPress Admin → Settings → Stripe Settings</li>";
    echo "<li>Enter your Stripe API keys</li>";
    echo "<li>Save the settings</li>";
    echo "</ol>";
} else {
    echo "<p style='color: green;'>✅ Stripe is properly configured. The certificate form should redirect to real Stripe payments.</p>";
}

echo "<p><a href='certificate'>Test Certificate Form</a></p>";
?>
