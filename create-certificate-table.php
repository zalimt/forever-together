<?php
/**
 * Create certificate table manually
 * Access: http://forever-together.local/create-certificate-table.php
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Creating Certificate Table</h1>";

// Include the certificate system functions
require_once(get_stylesheet_directory() . '/inc/certificate-system.php');

// Create the table
tf_create_certificates_table();

echo "<p style='color: green;'>✅ Certificate table creation function called</p>";

// Check if table was created
global $wpdb;
$table_name = $wpdb->prefix . 'tf_certificates';
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
} else {
    echo "<p style='color: red;'>❌ Table creation failed</p>";
}

echo "<p><a href='debug-certificates-simple.php'>Go back to debug page</a></p>";
?>
