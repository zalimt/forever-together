<?php
/**
 * Stripe Payment Integration for Certificates
 * 
 * @package Together_Forever
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create Stripe Checkout Session
 */
function tf_create_stripe_session() {
    check_ajax_referer('tf_stripe_nonce', 'nonce');
    
    // Get form data
    $beneficiary_name = sanitize_text_field($_POST['beneficiary_name']);
    $beneficiary_from = sanitize_text_field($_POST['beneficiary_from']);
    $giver_name = sanitize_text_field($_POST['giver_name']);
    $recipient_email = sanitize_email($_POST['recipient_email']);
    $amount = floatval($_POST['amount']);
    
    // Validate data
    if (empty($beneficiary_name) || empty($giver_name) || empty($recipient_email) || $amount <= 0) {
        wp_send_json_error(array('message' => 'Please fill in all required fields.'));
        return;
    }
    
    // Get Stripe API key from settings (you'll need to add this in admin)
    $stripe_secret_key = get_option('tf_stripe_secret_key');
    $stripe_publishable_key = get_option('tf_stripe_publishable_key');
    
    if (empty($stripe_secret_key)) {
        wp_send_json_error(array('message' => 'Stripe is not configured. Please contact the administrator.'));
        return;
    }
    
    // Initialize Stripe (you'll need to install Stripe PHP SDK via Composer)
    // For now, we'll return the data structure
    
    // In production, you would create a Stripe session like this:
    
    require_once get_stylesheet_directory() . '/vendor/stripe/init.php';
    \Stripe\Stripe::setApiKey($stripe_secret_key);
    
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => 'Together Forever Benefactor Certificate',
                    'description' => 'For: ' . $beneficiary_name,
                ],
                'unit_amount' => $amount * 100, // Amount in cents
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => home_url('/certificate-success') . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => home_url('/certificate') . '?canceled=true',
        'metadata' => [
            'beneficiary_name' => $beneficiary_name,
            'beneficiary_from' => $beneficiary_from,
            'giver_name' => $giver_name,
            'recipient_email' => $recipient_email,
            'amount' => $amount,
        ],
    ]);
    
    wp_send_json_success(array(
        'session_id' => $session->id,
        'publishable_key' => $stripe_publishable_key
    ));
    
    // For demonstration, return success with demo data
    /*
    wp_send_json_success(array(
        'demo_mode' => true,
        'message' => 'Stripe integration ready. Install Stripe PHP SDK and configure API keys.',
        'data' => array(
            'beneficiary_name' => $beneficiary_name,
            'beneficiary_from' => $beneficiary_from,
            'giver_name' => $giver_name,
            'recipient_email' => $recipient_email,
            'amount' => $amount,
        )
    ));
    */
}
add_action('wp_ajax_create_stripe_session', 'tf_create_stripe_session');
add_action('wp_ajax_nopriv_create_stripe_session', 'tf_create_stripe_session');

/**
 * Handle Stripe Webhook
 */
function tf_stripe_webhook() {
    // Get Stripe webhook secret
    $webhook_secret = get_option('tf_stripe_webhook_secret');
    
    if (empty($webhook_secret)) {
        http_response_code(400);
        exit();
    }
    
    $payload = @file_get_contents('php://input');
    $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
    
    try {
        // Verify webhook signature
        // In production with Stripe SDK:
        // $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $webhook_secret);
        
        $event = json_decode($payload);
        
        // Handle the checkout.session.completed event
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            
            // Retrieve metadata
            $metadata = $session->metadata;
            
            // Save certificate to database
            $certificate_data = array(
                'beneficiary_name' => $metadata->beneficiary_name,
                'beneficiary_from' => $metadata->beneficiary_from,
                'giver_name' => $metadata->giver_name,
                'recipient_email' => $metadata->recipient_email,
                'amount' => $metadata->amount,
                'payment_intent_id' => $session->payment_intent,
                'stripe_session_id' => $session->id,
            );
            
            $certificate_code = tf_save_certificate($certificate_data);
            
            if ($certificate_code) {
                // Send email
                tf_send_certificate_email($certificate_code, $metadata->recipient_email, $certificate_data);
            }
        }
        
        http_response_code(200);
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }
    
    exit();
}

// Add webhook endpoint
add_action('rest_api_init', function() {
    register_rest_route('tf/v1', '/stripe-webhook', array(
        'methods' => 'POST',
        'callback' => 'tf_stripe_webhook',
        'permission_callback' => '__return_true'
    ));
});

/**
 * Admin Settings Page
 */
function tf_stripe_settings_page() {
    add_options_page(
        'Stripe Settings',
        'Stripe Settings',
        'manage_options',
        'tf-stripe-settings',
        'tf_stripe_settings_page_html'
    );
}
add_action('admin_menu', 'tf_stripe_settings_page');

/**
 * Settings Page HTML
 */
function tf_stripe_settings_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Save settings
    if (isset($_POST['tf_stripe_settings_nonce']) && 
        wp_verify_nonce($_POST['tf_stripe_settings_nonce'], 'tf_stripe_settings')) {
        update_option('tf_stripe_secret_key', sanitize_text_field($_POST['stripe_secret_key']));
        update_option('tf_stripe_publishable_key', sanitize_text_field($_POST['stripe_publishable_key']));
        update_option('tf_stripe_webhook_secret', sanitize_text_field($_POST['stripe_webhook_secret']));
        echo '<div class="notice notice-success"><p>Settings saved!</p></div>';
    }
    
    $secret_key = get_option('tf_stripe_secret_key', '');
    $publishable_key = get_option('tf_stripe_publishable_key', '');
    $webhook_secret = get_option('tf_stripe_webhook_secret', '');
    ?>
    <div class="wrap">
        <h1>Stripe Payment Settings</h1>
        
        <form method="post">
            <?php wp_nonce_field('tf_stripe_settings', 'tf_stripe_settings_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="stripe_publishable_key">Publishable Key</label>
                    </th>
                    <td>
                        <input 
                            type="text" 
                            id="stripe_publishable_key" 
                            name="stripe_publishable_key" 
                            value="<?php echo esc_attr($publishable_key); ?>" 
                            class="regular-text"
                        >
                        <p class="description">Your Stripe publishable key (starts with pk_)</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="stripe_secret_key">Secret Key</label>
                    </th>
                    <td>
                        <input 
                            type="password" 
                            id="stripe_secret_key" 
                            name="stripe_secret_key" 
                            value="<?php echo esc_attr($secret_key); ?>" 
                            class="regular-text"
                        >
                        <p class="description">Your Stripe secret key (starts with sk_)</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="stripe_webhook_secret">Webhook Secret</label>
                    </th>
                    <td>
                        <input 
                            type="password" 
                            id="stripe_webhook_secret" 
                            name="stripe_webhook_secret" 
                            value="<?php echo esc_attr($webhook_secret); ?>" 
                            class="regular-text"
                        >
                        <p class="description">Your Stripe webhook signing secret (starts with whsec_)</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Webhook URL</th>
                    <td>
                        <code><?php echo rest_url('tf/v1/stripe-webhook'); ?></code>
                        <p class="description">Add this URL to your Stripe webhook endpoints. Listen for: checkout.session.completed</p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button('Save Settings'); ?>
        </form>
        
        <hr>
        
        <h2>Setup Instructions</h2>
        <ol>
            <li>Get your API keys from <a href="https://dashboard.stripe.com/apikeys" target="_blank">Stripe Dashboard</a></li>
            <li>Add your Publishable Key and Secret Key above</li>
            <li>Create a webhook in <a href="https://dashboard.stripe.com/webhooks" target="_blank">Stripe Webhooks</a></li>
            <li>Add the webhook URL shown above</li>
            <li>Select the event: <code>checkout.session.completed</code></li>
            <li>Copy the webhook signing secret and paste it above</li>
            <li>Save settings</li>
        </ol>
        
        <div class="notice notice-info">
            <p><strong>Note:</strong> You need to install the Stripe PHP SDK via Composer for full functionality:</p>
            <code>composer require stripe/stripe-php</code>
        </div>
    </div>
    <?php
}

