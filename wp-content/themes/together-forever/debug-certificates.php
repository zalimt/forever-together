<?php
/**
 * Template Name: Debug Certificates
 * 
 * WordPress-based debug page for certificates
 */

get_header();

// Check if user is admin
if (!current_user_can('administrator')) {
    echo '<div class="container"><h1>Access Denied</h1><p>Admin access required.</p></div>';
    get_footer();
    exit;
}

global $wpdb;
$table_name = $wpdb->prefix . 'tf_certificates';

?>
<div class="container" style="max-width: 1200px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <h1>🔍 Certificate System Debug</h1>
    
    <h2>1. Database Connection Test</h2>
    <p><strong>Host:</strong> <?php echo DB_HOST; ?></p>
    <p><strong>Database:</strong> <?php echo DB_NAME; ?></p>
    <p><strong>Table:</strong> <?php echo $table_name; ?></p>
    
    <h2>2. Table Existence Check</h2>
    <?php
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
    echo "<p>Table exists: " . ($table_exists ? "✅ YES" : "❌ NO") . "</p>";
    
    if ($table_exists) {
        echo "<h2>3. Table Structure</h2>";
        $columns = $wpdb->get_results("DESCRIBE $table_name");
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        foreach ($columns as $col) {
            echo "<tr><td>{$col->Field}</td><td>{$col->Type}</td><td>{$col->Null}</td><td>{$col->Key}</td><td>{$col->Default}</td></tr>";
        }
        echo "</table>";
        
        echo "<h2>4. Current Certificate Count</h2>";
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        echo "<p><strong>Total certificates:</strong> $count</p>";
        
        if ($count > 0) {
            echo "<h2>5. All Certificates</h2>";
            $certificates = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Code</th><th>Amount</th><th>Created</th><th>Active</th><th>Session ID</th></tr>";
            
            foreach ($certificates as $cert) {
                echo "<tr>";
                echo "<td>{$cert->id}</td>";
                echo "<td><code>{$cert->certificate_code}</code></td>";
                echo "<td>€{$cert->amount}</td>";
                echo "<td>{$cert->created_at}</td>";
                echo "<td>" . ($cert->is_active ? "✅" : "❌") . "</td>";
                echo "<td><small>" . substr($cert->stripe_session_id, 0, 20) . "...</small></td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        echo "<h2>6. Test Database Insert</h2>";
        $test_data = array(
            'certificate_code' => 'TEST-' . time(),
            'beneficiary_name' => 'Test Beneficiary',
            'beneficiary_from' => 'Test From',
            'giver_name' => 'Test Giver',
            'recipient_email' => 'test@example.com',
            'amount' => 50.00,
            'payment_intent_id' => 'test_payment',
            'stripe_session_id' => 'test_session',
            'is_active' => 1
        );
        
        $result = $wpdb->insert($table_name, $test_data);
        
        if ($result) {
            echo "<p style='color: green;'>✅ <strong>Database insert works!</strong> Test certificate created.</p>";
            
            // Clean up test certificate
            $wpdb->delete($table_name, array('certificate_code' => $test_data['certificate_code']));
            echo "<p>Test certificate cleaned up.</p>";
        } else {
            echo "<p style='color: red;'>❌ <strong>Database insert FAILED:</strong> " . $wpdb->last_error . "</p>";
        }
        
    } else {
        echo "<h2>3. Creating Table</h2>";
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            certificate_code varchar(16) NOT NULL,
            beneficiary_name varchar(255) NOT NULL,
            beneficiary_from varchar(255) DEFAULT '',
            giver_name varchar(255) NOT NULL,
            recipient_email varchar(255) NOT NULL,
            amount decimal(10,2) NOT NULL,
            payment_intent_id varchar(255) DEFAULT '',
            stripe_session_id varchar(255) DEFAULT '',
            is_active tinyint(1) DEFAULT 1,
            activated_at datetime DEFAULT NULL,
            activated_by_email varchar(255) DEFAULT '',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY certificate_code (certificate_code)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        
        $result = $wpdb->query($sql);
        
        if ($result !== false) {
            echo "<p style='color: green;'>✅ <strong>Table created successfully!</strong></p>";
        } else {
            echo "<p style='color: red;'>❌ <strong>Table creation FAILED:</strong> " . $wpdb->last_error . "</p>";
        }
    }
    
    echo "<h2>7. WordPress Database Status</h2>";
    // Check database connection without accessing undefined property
    $db_connected = true;
    if (isset($wpdb->db_connect_error) && $wpdb->db_connect_error) {
        $db_connected = false;
    }
    echo "<p><strong>Database connection:</strong> " . ($db_connected ? "✅ OK" : "❌ FAILED") . "</p>";
    echo "<p><strong>Last query:</strong> " . $wpdb->last_query . "</p>";
    echo "<p><strong>Last error:</strong> " . ($wpdb->last_error ?: "None") . "</p>";
    
    echo "<h2>8. Stripe Configuration</h2>";
    $stripe_keys = [
        'tf_stripe_secret_key' => get_option('tf_stripe_secret_key'),
        'tf_stripe_publishable_key' => get_option('tf_stripe_publishable_key'),
        'tf_stripe_webhook_secret' => get_option('tf_stripe_webhook_secret')
    ];
    
    foreach ($stripe_keys as $key => $value) {
        $status = !empty($value) ? "✅ Set" : "❌ Missing";
        echo "<p><strong>$key:</strong> $status</p>";
    }
    
    echo "<h2>9. Processing Status</h2>";
    $success_page_content = file_get_contents(get_stylesheet_directory() . '/certificate-success.php');
    if (strpos($success_page_content, 'if (false && isset($_GET[\'session_id\']))') !== false) {
        echo "<p>❌ <strong>Automatic processing is DISABLED</strong></p>";
    } else {
        echo "<p>✅ <strong>Automatic processing is ENABLED</strong></p>";
    }
    
    ?>
</div>

<?php get_footer(); ?>