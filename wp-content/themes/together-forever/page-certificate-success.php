<?php
/**
 * Page Template for certificate-success slug
 * This page is shown after successful payment
 */

get_header(); 

// Process certificate if webhook failed
if (isset($_GET['session_id'])) {
    $session_id = sanitize_text_field($_GET['session_id']);
    
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
            
            // Check if payment was successful
            if ($session->payment_status === 'paid' && $session->metadata) {
                
                // Check if certificate already exists
                global $wpdb;
                $table_name = $wpdb->prefix . 'tf_certificates';
                $existing_cert = $wpdb->get_row($wpdb->prepare(
                    "SELECT * FROM $table_name WHERE stripe_session_id = %s",
                    $session_id
                ));
                
                // Only create if it doesn't exist
                if (!$existing_cert) {
                    $data = array(
                        'beneficiary_name' => $session->metadata->beneficiary_name ?? 'Certificate Recipient',
                        'beneficiary_from' => $session->metadata->beneficiary_from ?? '',
                        'giver_name' => $session->metadata->giver_name ?? 'Anonymous Donor',
                        'recipient_email' => $session->metadata->recipient_email ?? $session->customer_details->email,
                        'amount' => $session->amount_total / 100,
                        'payment_intent_id' => $session->payment_intent,
                        'stripe_session_id' => $session_id,
                    );
                    
                    // Debug: Log the data
                    error_log('Success page processing certificate with data: ' . print_r($data, true));
                    
                    $certificate_code = tf_save_certificate($data);
                    
                    if ($certificate_code) {
                        error_log('Certificate created and email sent on success page: ' . $certificate_code);
                    } else {
                        error_log('Certificate creation failed on success page');
                    }
                } else {
                    error_log('Certificate already exists for session: ' . $session_id);
                }
            }
        } catch (Exception $e) {
            // Log error but don't show to user
            error_log('Certificate processing error: ' . $e->getMessage());
        }
    }
}
?>

<main>
    <article class="certificate-success-content">
        <section class="certificate-success-wrapper" style="max-width: 800px; padding: 40px 20px; margin: 0 auto;">
            
            <div class="certificate-success-section">
                <div class="success-container">
                    <div class="success-icon">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" fill="#4CAF50"/>
                            <path d="M9 12l2 2 4-4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    
                    <h1 class="success-title">Payment Successful!</h1>
                    <div class="coolhr mb-3"></div>
                    
                    <div class="success-message">
                        <p>Thank you for your generous donation!</p>
                        <p>Your benefactor certificate has been processed and will be sent to your email shortly.</p>
                    </div>
                    
                    <div class="success-details">
                        <h3>What happens next?</h3>
                        <ul>
                            <li>✅ Payment processed successfully</li>
                            <li>📧 Certificate code will be sent to your email</li>
                            <li>🎁 Certificate can be printed or shared digitally</li>
                            <li>💝 Your kindness will help children in need</li>
                        </ul>
                    </div>
                    
                    <div class="success-actions">
                        <a href="<?php echo home_url('/activate-certificate'); ?>" class="btn btn-primary">
                            Activate Certificate
                        </a>
                        <a href="<?php echo home_url('/certificate'); ?>" class="btn btn-secondary">
                            Purchase Another Certificate
                        </a>
                    </div>
                    
                    <div class="success-note">
                        <p><strong>Note:</strong> If you don't receive the email within 5 minutes, please check your spam folder or contact us.</p>
                    </div>
                </div>
            </div>

        </section>
    </article>
</main>

<style>
.certificate-success-content {
    min-height: 60vh;
    display: flex;
    align-items: center;
}

.success-container {
    text-align: center;
    background: white;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.success-icon {
    margin-bottom: 20px;
}

.success-title {
    font-size: 2.5rem;
    font-weight: bold;
    color: var(--tf-purple);
    margin-bottom: 20px;
}

.success-message {
    font-size: 1.2rem;
    color: #666;
    margin-bottom: 30px;
    line-height: 1.6;
}

.success-details {
    text-align: left;
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
}

.success-details h3 {
    color: var(--tf-purple);
    margin-bottom: 15px;
}

.success-details ul {
    list-style: none;
    padding: 0;
}

.success-details li {
    padding: 8px 0;
    color: #555;
}

.success-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 24px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-block;
}

.btn-primary {
    background: var(--tf-purple);
    color: white;
}

.btn-primary:hover {
    background: var(--tf-purple-gradient);
    transform: translateY(-2px);
}

.btn-secondary {
    background: transparent;
    color: var(--tf-purple);
    border: 2px solid var(--tf-purple);
}

.btn-secondary:hover {
    background: var(--tf-purple);
    color: white;
}

.success-note {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 6px;
    padding: 15px;
    color: #856404;
    font-size: 0.9rem;
}

.coolhr {
    height: 3px;
    background: var(--tf-purple-gradient);
    border: none;
    margin: 20px 0;
}
</style>

<?php get_footer(); ?>
