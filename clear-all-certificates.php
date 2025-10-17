<?php
/**
 * Clear all certificates from database
 * Only accessible to WordPress administrators
 */

// Load WordPress
require_once('wp-load.php');

// Check if user is admin
if (!current_user_can('administrator')) {
    wp_die('Access denied. Admin only.');
}

echo "<h1>Clear All Certificates</h1>";

global $wpdb;
$table_name = $wpdb->prefix . 'tf_certificates';

// Check if table exists
$table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;

if (!$table_exists) {
    echo "<p style='color: red;'>❌ Certificate table doesn't exist</p>";
    exit;
}

echo "<p style='color: green;'>✅ Table $table_name exists</p>";

// Get current count
$current_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
echo "<p><strong>Current certificates:</strong> $current_count</p>";

// Show current certificates before deletion
echo "<h2>Current Certificates (will be deleted):</h2>";
$certificates = $wpdb->get_results("SELECT id, certificate_code, amount, recipient_email, created_at FROM $table_name ORDER BY id DESC");

if (empty($certificates)) {
    echo "<p>No certificates to delete.</p>";
} else {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Code</th><th>Amount</th><th>Email</th><th>Created</th></tr>";
    
    foreach ($certificates as $cert) {
        echo "<tr>";
        echo "<td>{$cert->id}</td>";
        echo "<td><code>{$cert->certificate_code}</code></td>";
        echo "<td>{$cert->amount}€</td>";
        echo "<td>{$cert->recipient_email}</td>";
        echo "<td>{$cert->created_at}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

// Confirmation
if (!isset($_POST['confirm_delete'])) {
    echo "<h2>⚠️ WARNING ⚠️</h2>";
    echo "<p style='color: red;'><strong>This will permanently delete ALL certificates from the database!</strong></p>";
    echo "<p>This action cannot be undone.</p>";
    
    echo "<form method='post'>";
    echo "<p><input type='checkbox' name='confirm_checkbox' required> I understand this will delete all certificates permanently</p>";
    echo "<p><input type='submit' name='confirm_delete' value='DELETE ALL CERTIFICATES' style='background: red; color: white; padding: 10px 20px; border: none; border-radius: 5px;'></p>";
    echo "</form>";
    
} else {
    // Check if checkbox was checked
    if (!isset($_POST['confirm_checkbox'])) {
        echo "<p style='color: red;'>❌ You must check the confirmation checkbox</p>";
        echo "<p><a href=''>Try again</a></p>";
        exit;
    }
    
    echo "<h2>Deleting All Certificates...</h2>";
    
    // Delete all certificates
    $deleted_count = $wpdb->query("DELETE FROM $table_name");
    
    if ($deleted_count !== false) {
        echo "<p style='color: green;'>✅ Successfully deleted $deleted_count certificates</p>";
        
        // Verify deletion
        $remaining_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        echo "<p><strong>Remaining certificates:</strong> $remaining_count</p>";
        
        if ($remaining_count == 0) {
            echo "<p style='color: green;'>✅ Database cleared successfully!</p>";
        } else {
            echo "<p style='color: red;'>❌ Some certificates may not have been deleted</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Failed to delete certificates</p>";
        echo "<p><strong>Database Error:</strong> " . $wpdb->last_error . "</p>";
    }
    
    echo "<h2>Next Steps:</h2>";
    echo "<p>1. <a href='certificate'>Test certificate purchase</a></p>";
    echo "<p>2. <a href='activate-certificate'>Test certificate activation</a></p>";
    echo "<p>3. <a href='debug-all-certificates.php'>Check certificate status</a></p>";
}

?>
