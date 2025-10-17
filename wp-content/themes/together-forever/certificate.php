<?php
/**
 * Template Name: Certificate
 *
 * @package Together_Forever
 */

get_header(); ?>

<main>
    <article class="certificate-content">
        <section class="certificate-wrapper" style="max-width: 1400px; padding: 0 20px; margin: 0 auto;">
            
            <!-- Certificate Section -->
            <div class="certificate-section">
                <div class="certificate-container">
                    <div class="certificate-text">
                        <div class="coolhr mb-3"></div>
                        <h1 class="certificate-title">BENEFACTOR CERTIFICATE</h1>

                        <div class="certificate-note">Are you looking for a memorable and unusual gift? Give real kindness!</div>
                        <div class="coolhr mt-3 mb-4"></div>
                        
                        <p>A benefactor certificate is a special gift everyone will appreciate.</p>
                        <p>Such a gift undoubtedly cannot be forgotten and left to gather dust.</p>
                        <p>It will be standing out and please the eyes of beneficiary.</p>

                        <div class="benef-events">
                            <div class="benef-event">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/certificate-icons/document.svg" class="benef-icon benef-icon--document" alt="Choose design">
                                <div class="benef-text">Choose certificate design</div>
                            </div>
                            <div class="benef-event">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/certificate-icons/gift-box.svg" class="benef-icon" alt="Write name">
                                <div class="benef-text">Write in the benefactors name</div>
                            </div>
                            <div class="benef-event">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/certificate-icons/email.svg" class="benef-icon" alt="Email">
                                <div class="benef-text">Include<br>e-mail address</div>
                            </div>
                            <div class="benef-event">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/certificate-icons/valid.svg" class="benef-icon benef-icon--valid" alt="Payment">
                                <div class="benef-text">Enter gift amount and make a payment</div>
                            </div>
                        </div>
                        
                        <p>After payment, Benefactor Certificate will be sent to the stated e-mail.</p>
                        <p>It can be downloaded, printed and gifted.</p>
                        <p>If you would like your gift certificate to be send directly to benefactor, please include their e-mail address when filling out the form.</p>
                        
                        <div class="certificate-slogan">
                            Give kindness instead of souvenirs, make this world a happier place!
                        </div>
                    </div>
                </div>
            </div>

            <!-- Certificate Form Section -->
            <div class="certificate-form-section">
                <div class="certificate-form-container">
                    <form class="certificate-form" id="certificateForm">
                        <div class="form-row">
                            <div class="form-col form-col-left">
                                <div class="form-group">
                                    <label class="form-label" for="beneficiary-name">Beneficiary*</label>
                                    <input type="text" class="form-input" id="beneficiary-name" name="beneficiary_name" required>
                                    <div class="form-error">Recipient must be specified</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="beneficiary-from">From (optional)</label>
                                    <input type="text" class="form-input" id="beneficiary-from" name="beneficiary_from">
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="giver-name">Giver*</label>
                                    <input type="text" class="form-input" id="giver-name" name="giver_name" required>
                                    <div class="form-error">You must specify the donor</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="recipient-email">Recipient's email*</label>
                                    <input type="email" class="form-input" id="recipient-email" name="recipient_email" placeholder="ivanov@mail.com" required>
                                    <div class="form-error">You must enter a valid email</div>
                                </div>
                            </div>

                            <div class="form-col form-col-right">
                                <div class="form-group">
                                    <label class="form-label">Amount in euro</label>
                                    
                                    <div class="radio-custom-group">
                                        <div class="radio-row">
                                            <label class="radio-custom">
                                                <input type="radio" name="amount" value="25" checked>
                                                <span class="radio-label">25 €</span>
                                            </label>
                                            <label class="radio-custom">
                                                <input type="radio" name="amount" value="50">
                                                <span class="radio-label">50 €</span>
                                            </label>
                                        </div>
                                        
                                        <div class="radio-row">
                                            <label class="radio-custom">
                                                <input type="radio" name="amount" value="100">
                                                <span class="radio-label">100 €</span>
                                            </label>
                                            <label class="radio-custom">
                                                <input type="radio" name="amount" value="250">
                                                <span class="radio-label">250 €</span>
                                            </label>
                                        </div>
                                        
                                        <div class="radio-row">
                                            <label class="radio-custom">
                                                <input type="radio" name="amount" value="500">
                                                <span class="radio-label">500 €</span>
                                            </label>
                                            <label class="radio-custom">
                                                <input type="radio" name="amount" value="1000">
                                                <span class="radio-label">1000 €</span>
                                            </label>
                                        </div>
                                        
                                        <div class="radio-row">
                                            <label class="radio-custom">
                                                <input type="radio" name="amount" value="custom">
                                                <span class="radio-label">Other amount</span>
                                            </label>
                                        </div>

                                        <div class="custom-amount-group" id="customAmountGroup" style="display: none;">
                                            <input type="number" class="form-input" id="custom-amount" name="custom_amount" placeholder="Enter amount" min="1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-submit">
                                <span class="btn-text">Proceed to Payment</span>
                                <span class="btn-loader" style="display: none;">
                                    <span class="spinner"></span> Processing...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </section>
    </article>
</main>

<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
jQuery(document).ready(function($) {
    // Custom amount toggle
    $('input[name="amount"]').on('change', function() {
        if ($(this).val() === 'custom') {
            $('#customAmountGroup').slideDown();
            $('#custom-amount').focus();
        } else {
            $('#customAmountGroup').slideUp();
        }
    });

    // Form submission with Stripe
    $('#certificateForm').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $btn = $('.btn-submit');
        const $btnText = $('.btn-text');
        const $btnLoader = $('.btn-loader');
        
        // Get form values
        const beneficiaryName = $('#beneficiary-name').val().trim();
        const beneficiaryFrom = $('#beneficiary-from').val().trim();
        const giverName = $('#giver-name').val().trim();
        const recipientEmail = $('#recipient-email').val().trim();
        const amountType = $('input[name="amount"]:checked').val();
        const customAmount = $('#custom-amount').val();
        let amount = amountType === 'custom' ? parseFloat(customAmount) : parseFloat(amountType);
        
        // Basic validation
        let isValid = true;
        let errorMessage = '';
        $('.form-group').removeClass('has-error');
        
        if (!beneficiaryName) {
            $('#beneficiary-name').closest('.form-group').addClass('has-error');
            errorMessage = 'Please enter the beneficiary name.';
            isValid = false;
        }
        
        if (!giverName) {
            $('#giver-name').closest('.form-group').addClass('has-error');
            errorMessage = 'Please enter the giver name.';
            isValid = false;
        }
        
        if (!recipientEmail || !validateEmail(recipientEmail)) {
            $('#recipient-email').closest('.form-group').addClass('has-error');
            errorMessage = 'Please enter a valid email address.';
            isValid = false;
        }
        
        if (amountType === 'custom' && (!customAmount || amount <= 0)) {
            $('#custom-amount').closest('.form-group').addClass('has-error');
            errorMessage = 'Please enter a valid amount.';
            isValid = false;
        }
        
        if (!isValid) {
            alert(errorMessage);
            return;
        }
        
        // Show loading state
        $btn.prop('disabled', true);
        $btnText.hide();
        $btnLoader.show();
        
        // Create Stripe checkout session
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'create_stripe_session',
                beneficiary_name: beneficiaryName,
                beneficiary_from: beneficiaryFrom,
                giver_name: giverName,
                recipient_email: recipientEmail,
                amount: amount,
                nonce: '<?php echo wp_create_nonce('tf_stripe_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    if (response.data.demo_mode) {
                        // Demo mode - show message
                        alert('Demo Mode: ' + response.data.message + '\n\nIn production, this would redirect to Stripe payment page.');
                        
                        // For demo: simulate successful payment
                        if (confirm('Simulate successful payment? (This will create a certificate)')) {
                            simulatePayment(response.data.data);
                        } else {
                            // Reset button
                            $btn.prop('disabled', false);
                            $btnText.show();
                            $btnLoader.hide();
                        }
                    } else {
                        // Production mode - redirect to Stripe
                        const stripe = Stripe(response.data.publishable_key);
                        stripe.redirectToCheckout({
                            sessionId: response.data.session_id
                        }).then(function(result) {
                            if (result.error) {
                                alert(result.error.message);
                                $btn.prop('disabled', false);
                                $btnText.show();
                                $btnLoader.hide();
                            }
                        });
                    }
                } else {
                    alert('Error: ' + response.data.message);
                    $btn.prop('disabled', false);
                    $btnText.show();
                    $btnLoader.hide();
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                $btn.prop('disabled', false);
                $btnText.show();
                $btnLoader.hide();
            }
        });
    });
    
    // Demo function to simulate payment
    function simulatePayment(data) {
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'simulate_certificate_payment',
                beneficiary_name: data.beneficiary_name,
                beneficiary_from: data.beneficiary_from,
                giver_name: data.giver_name,
                recipient_email: data.recipient_email,
                amount: data.amount,
                nonce: '<?php echo wp_create_nonce('tf_stripe_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    alert('Success! Certificate code: ' + response.data.code + '\n\nAn email has been sent to: ' + data.recipient_email);
                    window.location.href = '<?php echo home_url('/activate-certificate'); ?>';
                } else {
                    alert('Error creating certificate: ' + response.data.message);
                }
                
                // Reset button
                $('.btn-submit').prop('disabled', false);
                $('.btn-text').show();
                $('.btn-loader').hide();
            }
        });
    }
    
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});
</script>

<style>
.btn-submit {
    position: relative;
}

.btn-loader {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

<?php
get_footer();

