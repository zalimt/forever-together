<?php
/**
 * Certificate Payment & Redemption System
 * 
 * Handles Stripe payments, code generation, database storage, and activation
 * 
 * @package Together_Forever
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create custom database table for certificates
 */
function tf_create_certificates_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tf_certificates';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        certificate_code varchar(20) NOT NULL UNIQUE,
        beneficiary_name varchar(255) NOT NULL,
        beneficiary_from varchar(255) DEFAULT NULL,
        giver_name varchar(255) NOT NULL,
        recipient_email varchar(255) NOT NULL,
        amount decimal(10,2) NOT NULL,
        payment_intent_id varchar(255) DEFAULT NULL,
        stripe_session_id varchar(255) DEFAULT NULL,
        status varchar(20) DEFAULT 'active',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        activated_at datetime DEFAULT NULL,
        activated_by_email varchar(255) DEFAULT NULL,
        PRIMARY KEY (id),
        KEY certificate_code (certificate_code),
        KEY status (status)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Create table on theme activation
add_action('after_switch_theme', 'tf_create_certificates_table');

/**
 * Generate unique certificate code
 */
function tf_generate_certificate_code() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tf_certificates';
    
    do {
        // Generate code format: TF-XXXX-XXXX (12 characters)
        $code = 'TF-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4)) . '-' 
                     . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
        
        // Check if code already exists
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE certificate_code = %s",
            $code
        ));
    } while ($exists > 0);
    
    return $code;
}

/**
 * Save certificate to database
 */
function tf_save_certificate($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tf_certificates';
    
    $certificate_code = tf_generate_certificate_code();
    
    $result = $wpdb->insert(
        $table_name,
        array(
            'certificate_code' => $certificate_code,
            'beneficiary_name' => sanitize_text_field($data['beneficiary_name']),
            'beneficiary_from' => sanitize_text_field($data['beneficiary_from']),
            'giver_name' => sanitize_text_field($data['giver_name']),
            'recipient_email' => sanitize_email($data['recipient_email']),
            'amount' => floatval($data['amount']),
            'payment_intent_id' => sanitize_text_field($data['payment_intent_id'] ?? ''),
            'stripe_session_id' => sanitize_text_field($data['stripe_session_id'] ?? ''),
            'is_active' => 1
        ),
        array('%s', '%s', '%s', '%s', '%s', '%f', '%s', '%s', '%d')
    );
    
    if ($result) {
        // Only send email if certificate was successfully created
        $email_sent = tf_send_certificate_email($certificate_code, $recipient_email, $data);
        if ($email_sent) {
            error_log('Certificate created and email sent successfully: ' . $certificate_code);
        } else {
            error_log('Certificate created but email failed: ' . $certificate_code);
        }
        return $certificate_code;
    }
    
    // Log error for debugging
    error_log('Certificate creation failed: ' . $wpdb->last_error);
    return false;
}

/**
 * Send certificate email
 */
function tf_send_certificate_email($certificate_code, $recipient_email, $data) {
    $to = $recipient_email;
    $subject = 'Your Together Forever Benefactor Certificate - ' . $certificate_code;
    
    // Email content
    $message = '
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(90deg, #5C2483 0%, #951B81 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
            .code-box { background: white; border: 3px dashed #5C2483; padding: 20px; margin: 20px 0; text-align: center; border-radius: 8px; }
            .code { font-size: 28px; font-weight: bold; color: #5C2483; letter-spacing: 2px; }
            .button { display: inline-block; background: linear-gradient(90deg, #5C2483 0%, #951B81 100%); color: white; padding: 15px 40px; text-decoration: none; border-radius: 25px; margin: 20px 0; }
            .details { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; }
            .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>🎁 Benefactor Certificate</h1>
                <p>Thank you for your generous donation!</p>
            </div>
            <div class="content">
                <p>Dear ' . esc_html($data['giver_name']) . ',</p>
                
                <p>Thank you for purchasing a Benefactor Certificate for <strong>' . esc_html($data['beneficiary_name']) . '</strong>.</p>
                
                <p>Your generous donation of <strong>€' . number_format($data['amount'], 2) . '</strong> will help children in need receive life-changing treatment.</p>
                
                <div class="code-box">
                    <p style="margin: 0 0 10px 0; font-size: 14px; color: #666;">Your Certificate Code</p>
                    <div class="code">' . esc_html($certificate_code) . '</div>
                </div>
                
                <div class="details">
                    <h3 style="color: #5C2483; margin-top: 0;">Certificate Details:</h3>
                    <p><strong>Beneficiary:</strong> ' . esc_html($data['beneficiary_name']) . '</p>
                    ' . (!empty($data['beneficiary_from']) ? '<p><strong>From:</strong> ' . esc_html($data['beneficiary_from']) . '</p>' : '') . '
                    <p><strong>Giver:</strong> ' . esc_html($data['giver_name']) . '</p>
                    <p><strong>Amount:</strong> €' . number_format($data['amount'], 2) . '</p>
                </div>
                
                <p style="text-align: center;">
                    <a href="' . home_url('/activate-certificate') . '" class="button">Activate Your Certificate</a>
                </p>
                
                <p><strong>How to use your certificate:</strong></p>
                <ol>
                    <li>Click the button above or visit our activation page</li>
                    <li>Enter your certificate code</li>
                    <li>Your certificate will be activated and ready to download</li>
                </ol>
                
                <p style="color: #666; font-size: 14px;"><em>Note: Each certificate code can only be used once. After activation, the code will become inactive.</em></p>
                
                <p>If you have any questions, please don\'t hesitate to contact us.</p>
                
                <p>With gratitude,<br>
                <strong>Together Forever Foundation</strong></p>
            </div>
            <div class="footer">
                <p>Children Charitable Foundation Together Forever</p>
                <p>This email was sent because you purchased a benefactor certificate on our website.</p>
            </div>
        </div>
    </body>
    </html>
    ';
    
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: Together Forever <noreply@' . $_SERVER['HTTP_HOST'] . '>'
    );
    
    return wp_mail($to, $subject, $message, $headers);
}

/**
 * Validate certificate code
 */
function tf_validate_certificate($code) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tf_certificates';
    
    $certificate = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE certificate_code = %s",
        sanitize_text_field($code)
    ));
    
    if (!$certificate) {
        return array('valid' => false, 'message' => 'Certificate code not found.');
    }
    
    if ($certificate->is_active != 1) {
        return array(
            'valid' => false, 
            'message' => 'This certificate has already been activated on ' . 
                        date('F j, Y', strtotime($certificate->activated_at)) . '.'
        );
    }
    
    return array('valid' => true, 'certificate' => $certificate);
}

/**
 * Activate certificate
 */
function tf_activate_certificate($code, $activator_email = '') {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tf_certificates';
    
    $validation = tf_validate_certificate($code);
    
    if (!$validation['valid']) {
        return $validation;
    }
    
    $result = $wpdb->update(
        $table_name,
        array(
            'is_active' => 0,
            'activated_at' => current_time('mysql'),
            'activated_by_email' => sanitize_email($activator_email)
        ),
        array('certificate_code' => $code),
        array('%d', '%s', '%s'),
        array('%s')
    );
    
    if ($result !== false) {
        return array(
            'valid' => true, 
            'activated' => true, 
            'certificate' => $validation['certificate'],
            'message' => 'Certificate activated successfully!'
        );
    }
    
    return array('valid' => false, 'message' => 'Error activating certificate. Please try again.');
}

/**
 * Get certificate by code
 */
function tf_get_certificate($code) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tf_certificates';
    
    return $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE certificate_code = %s",
        sanitize_text_field($code)
    ));
}

/**
 * AJAX handler for certificate activation
 */
function tf_ajax_activate_certificate() {
    check_ajax_referer('tf_certificate_nonce', 'nonce');
    
    $code = sanitize_text_field($_POST['code']);
    $email = sanitize_email($_POST['email']);
    
    $result = tf_activate_certificate($code, $email);
    
    wp_send_json($result);
}
add_action('wp_ajax_activate_certificate', 'tf_ajax_activate_certificate');
add_action('wp_ajax_nopriv_activate_certificate', 'tf_ajax_activate_certificate');

/**
 * DEMO: Simulate certificate payment (for testing without Stripe)
 */
function tf_simulate_certificate_payment() {
    check_ajax_referer('tf_stripe_nonce', 'nonce');
    
    $data = array(
        'beneficiary_name' => sanitize_text_field($_POST['beneficiary_name']),
        'beneficiary_from' => sanitize_text_field($_POST['beneficiary_from']),
        'giver_name' => sanitize_text_field($_POST['giver_name']),
        'recipient_email' => sanitize_email($_POST['recipient_email']),
        'amount' => floatval($_POST['amount']),
        'payment_intent_id' => 'demo_' . time(),
        'stripe_session_id' => 'demo_session_' . time(),
    );
    
    $certificate_code = tf_save_certificate($data);
    
    if ($certificate_code) {
        // Send email
        tf_send_certificate_email($certificate_code, $data['recipient_email'], $data);
        
        wp_send_json_success(array(
            'code' => $certificate_code,
            'message' => 'Certificate created successfully!'
        ));
    } else {
        wp_send_json_error(array('message' => 'Error creating certificate.'));
    }
}
add_action('wp_ajax_simulate_certificate_payment', 'tf_simulate_certificate_payment');
add_action('wp_ajax_nopriv_simulate_certificate_payment', 'tf_simulate_certificate_payment');

/**
 * Admin page to view all certificates
 */
function tf_certificates_admin_menu() {
    add_menu_page(
        'Certificates',
        'Certificates',
        'manage_options',
        'tf-certificates',
        'tf_certificates_admin_page',
        'dashicons-awards',
        30
    );
}
add_action('admin_menu', 'tf_certificates_admin_menu');

/**
 * Admin page content
 */
function tf_certificates_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tf_certificates';
    
    // Handle status filter
    $status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
    
    $where = '';
    if ($status_filter === 'active') {
        $where = "WHERE status = 'active'";
    } elseif ($status_filter === 'inactive') {
        $where = "WHERE status = 'inactive'";
    }
    
    $certificates = $wpdb->get_results("SELECT * FROM $table_name $where ORDER BY created_at DESC");
    
    ?>
    <div class="wrap">
        <h1>Certificate Management</h1>
        
        <div class="tablenav top">
            <div class="alignleft actions">
                <select name="status" onchange="window.location.href='?page=tf-certificates&status=' + this.value">
                    <option value="all" <?php selected($status_filter, 'all'); ?>>All Statuses</option>
                    <option value="active" <?php selected($status_filter, 'active'); ?>>Active</option>
                    <option value="inactive" <?php selected($status_filter, 'inactive'); ?>>Inactive</option>
                </select>
            </div>
        </div>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Beneficiary</th>
                    <th>Giver</th>
                    <th>Amount</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Activated</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($certificates)): ?>
                    <tr>
                        <td colspan="8">No certificates found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($certificates as $cert): ?>
                        <tr>
                            <td><strong><?php echo esc_html($cert->certificate_code); ?></strong></td>
                            <td><?php echo esc_html($cert->beneficiary_name); ?></td>
                            <td><?php echo esc_html($cert->giver_name); ?></td>
                            <td>€<?php echo number_format($cert->amount, 2); ?></td>
                            <td><?php echo esc_html($cert->recipient_email); ?></td>
                            <td>
                                <span class="<?php echo $cert->status === 'active' ? 'status-active' : 'status-inactive'; ?>">
                                    <?php echo ucfirst($cert->status); ?>
                                </span>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($cert->created_at)); ?></td>
                            <td>
                                <?php 
                                if ($cert->activated_at) {
                                    echo date('M j, Y', strtotime($cert->activated_at));
                                } else {
                                    echo '—';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <style>
            .status-active { 
                background: #46b450; 
                color: white; 
                padding: 4px 10px; 
                border-radius: 3px; 
                font-size: 12px;
            }
            .status-inactive { 
                background: #dc3232; 
                color: white; 
                padding: 4px 10px; 
                border-radius: 3px; 
                font-size: 12px;
            }
        </style>
    </div>
    <?php
}

