<?php
/**
 * Template Name: Activate Certificate
 *
 * @package Together_Forever
 */

get_header(); ?>

<main>
    <article class="activate-certificate-content">
        <section class="activate-certificate-wrapper" style="max-width: 800px; padding: 0 20px; margin: 0 auto;">
            
            <div class="activate-certificate-section">
                <div class="coolhr mb-3"></div>
                <h1 class="activate-title">Activate Your Certificate</h1>
                <div class="coolhr mt-3 mb-4"></div>
                
                <p class="activate-intro">Enter your certificate code below to activate and download your benefactor certificate.</p>
                
                <!-- Activation Form -->
                <div class="activation-form-section">
                    <form id="activationForm" class="activation-form">
                        <div class="form-group">
                            <label class="form-label" for="certificate-code">Certificate Code*</label>
                            <input 
                                type="text" 
                                class="form-input code-input" 
                                id="certificate-code" 
                                name="certificate_code" 
                                placeholder="TF-XXXX-XXXX"
                                required
                            >
                            <p class="form-hint">Format: TF-XXXX-XXXX</p>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="activator-email">Your Email* (optional, for confirmation)</label>
                            <input 
                                type="email" 
                                class="form-input" 
                                id="activator-email" 
                                name="activator_email" 
                                placeholder="your@email.com"
                            >
                        </div>
                        
                        <div id="activation-message" class="activation-message" style="display: none;"></div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-activate">
                                <span class="btn-text">Activate Certificate</span>
                                <span class="btn-loader" style="display: none;">
                                    <span class="spinner"></span> Activating...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Certificate Display (Hidden until activated) -->
                <div id="certificateDisplay" class="certificate-display" style="display: none;">
                    <div class="certificate-success">
                        <div class="success-icon">✓</div>
                        <h2>Certificate Activated Successfully!</h2>
                    </div>
                    
                    <div class="certificate-details-box">
                        <h3>Certificate Details</h3>
                        <div class="detail-row">
                            <span class="detail-label">Code:</span>
                            <span class="detail-value" id="display-code"></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Beneficiary:</span>
                            <span class="detail-value" id="display-beneficiary"></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">From:</span>
                            <span class="detail-value" id="display-from"></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Giver:</span>
                            <span class="detail-value" id="display-giver"></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Amount:</span>
                            <span class="detail-value" id="display-amount"></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Activated:</span>
                            <span class="detail-value" id="display-activated"></span>
                        </div>
                    </div>
                    
                    <div class="certificate-actions">
                        <a href="<?php echo home_url('/certificate'); ?>" class="btn btn-secondary">Purchase Another Certificate</a>
                    </div>
                </div>
                
                <!-- Help Section -->
                <div class="activation-help">
                    <h3>Need Help?</h3>
                    <div class="help-items">
                        <div class="help-item">
                            <strong>Can't find your code?</strong>
                            <p>Check your email inbox and spam folder for the certificate email we sent after your purchase.</p>
                        </div>
                        <div class="help-item">
                            <strong>Code not working?</strong>
                            <p>Make sure you're entering the code exactly as shown, including dashes. Codes are case-sensitive.</p>
                        </div>
                        <div class="help-item">
                            <strong>Already activated?</strong>
                            <p>Each certificate code can only be used once. If you've already activated this code, it cannot be used again.</p>
                        </div>
                        <div class="help-item">
                            <strong>Still having trouble?</strong>
                            <p>Contact us at <a href="mailto:info@togetherfoundation.com">info@togetherfoundation.com</a> with your certificate code.</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </section>
    </article>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
jQuery(document).ready(function($) {
    $('#activationForm').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $btn = $('.btn-activate');
        const $btnText = $('.btn-text');
        const $btnLoader = $('.btn-loader');
        const $message = $('#activation-message');
        
        const code = $('#certificate-code').val().trim().toUpperCase();
        const email = $('#activator-email').val().trim();
        
        // Show loading state
        $btn.prop('disabled', true);
        $btnText.hide();
        $btnLoader.show();
        $message.hide();
        
        // AJAX request
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'activate_certificate',
                code: code,
                email: email,
                nonce: '<?php echo wp_create_nonce('tf_certificate_nonce'); ?>'
            },
            success: function(response) {
                // Reset button
                $btn.prop('disabled', false);
                $btnText.show();
                $btnLoader.hide();
                
                if (response.valid && response.activated) {
                    // Show success message
                    $message
                        .removeClass('error')
                        .addClass('success')
                        .html('<strong>Success!</strong> ' + response.message)
                        .slideDown();
                    
                    // Hide form
                    $form.slideUp();
                    
                    // Display certificate details
                    const cert = response.certificate;
                    $('#display-code').text(cert.certificate_code);
                    $('#display-beneficiary').text(cert.beneficiary_name);
                    $('#display-from').text(cert.beneficiary_from || '—');
                    $('#display-giver').text(cert.giver_name);
                    $('#display-amount').text('€' + parseFloat(cert.amount).toFixed(2));
                    $('#display-activated').text(new Date().toLocaleDateString());
                    
                    $('#certificateDisplay').slideDown();
                    
                    // Scroll to certificate
                    $('html, body').animate({
                        scrollTop: $('#certificateDisplay').offset().top - 100
                    }, 500);
                    
                } else {
                    // Show error message
                    $message
                        .removeClass('success')
                        .addClass('error')
                        .html('<strong>Error:</strong> ' + response.message)
                        .slideDown();
                }
            },
            error: function() {
                // Reset button
                $btn.prop('disabled', false);
                $btnText.show();
                $btnLoader.hide();
                
                // Show error
                $message
                    .removeClass('success')
                    .addClass('error')
                    .html('<strong>Error:</strong> Something went wrong. Please try again.')
                    .slideDown();
            }
        });
    });
    
    // Auto-format certificate code (simplified)
    $('#certificate-code').on('input', function() {
        let value = $(this).val().toUpperCase().replace(/[^A-Z0-9-]/g, '');
        
        // Don't auto-add TF- prefix, let user type the full code
        $(this).val(value);
    });
});
</script>

<?php
get_footer();

