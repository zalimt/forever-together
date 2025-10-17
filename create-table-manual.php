<?php
/**
 * Create certificate table manually with SQL
 * Access: http://forever-together.local/create-table-manual.php
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Creating Certificate Table Manually</h1>";

global $wpdb;
$table_name = $wpdb->prefix . 'tf_certificates';

// SQL to create the table
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

echo "<h2>Executing SQL:</h2>";
echo "<pre>" . htmlspecialchars($sql) . "</pre>";

// Execute the SQL
$result = $wpdb->query($sql);

if ($result !== false) {
    echo "<p style='color: green;'>✅ Table creation SQL executed successfully</p>";
    
    // Check if table was created
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");
    
    if ($table_exists) {
        echo "<p style='color: green;'>✅ Table $table_name created successfully!</p>";
        
        // Show table structure
        $columns = $wpdb->get_results("DESCRIBE $table_name");
        echo "<h2>Table Structure:</h2>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>{$column->Field}</td>";
            echo "<td>{$column->Type}</td>";
            echo "<td>{$column->Null}</td>";
            echo "<td>{$column->Key}</td>";
            echo "<td>{$column->Default}</td>";
            echo "<td>{$column->Extra}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<h2>Test Table:</h2>";
        // Insert a test record
        $test_code = 'TEST' . time();
        $insert_result = $wpdb->insert(
            $table_name,
            array(
                'certificate_code' => $test_code,
                'beneficiary_name' => 'Test User',
                'giver_name' => 'Test Giver',
                'recipient_email' => 'test@example.com',
                'amount' => 50.00
            )
        );
        
        if ($insert_result) {
            echo "<p style='color: green;'>✅ Test record inserted successfully</p>";
            
            // Show the test record
            $test_record = $wpdb->get_row("SELECT * FROM $table_name WHERE certificate_code = '$test_code'");
            if ($test_record) {
                echo "<p>Test record: ID {$test_record->id}, Code: {$test_record->certificate_code}</p>";
            }
            
            // Clean up test record
            $wpdb->delete($table_name, array('certificate_code' => $test_code));
            echo "<p>✅ Test record cleaned up</p>";
        } else {
            echo "<p style='color: red;'>❌ Failed to insert test record: " . $wpdb->last_error . "</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Table still doesn't exist after SQL execution</p>";
    }
    
} else {
    echo "<p style='color: red;'>❌ SQL execution failed: " . $wpdb->last_error . "</p>";
}

echo "<p><a href='debug-certificates-simple.php'>Go back to debug page</a></p>";
?>
