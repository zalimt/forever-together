<?php
/**
 * Debug ALL certificates to find missing ones
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Debug ALL Certificates</h1>";

global $wpdb;
$table_name = $wpdb->prefix . 'tf_certificates';

// Check if table exists
$table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;

if (!$table_exists) {
    echo "<p style='color: red;'>❌ Certificate table doesn't exist</p>";
    exit;
}

echo "<p style='color: green;'>✅ Table $table_name exists</p>";

// Get ALL certificates ordered by ID
$all_certificates = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");

echo "<h2>ALL Certificates (Ordered by ID DESC):</h2>";
echo "<p><strong>Total Count:</strong> " . count($all_certificates) . "</p>";

if (empty($all_certificates)) {
    echo "<p>No certificates found.</p>";
} else {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Code</th><th>Amount</th><th>Email</th><th>Created</th><th>Status</th><th>Session ID</th></tr>";
    
    foreach ($all_certificates as $cert) {
        $status = $cert->is_active ? 'Active' : 'Used';
        $status_color = $cert->is_active ? 'green' : 'red';
        
        echo "<tr>";
        echo "<td>{$cert->id}</td>";
        echo "<td><code>{$cert->certificate_code}</code></td>";
        echo "<td>{$cert->amount}€</td>";
        echo "<td>{$cert->recipient_email}</td>";
        echo "<td>{$cert->created_at}</td>";
        echo "<td style='color: $status_color;'>$status</td>";
        echo "<td>" . substr($cert->stripe_session_id, 0, 20) . "...</td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

// Check for specific certificate
echo "<h2>Search for Specific Certificate:</h2>";
$specific_cert = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM $table_name WHERE certificate_code = %s",
    'TF-87A1-6BF0'
));

if ($specific_cert) {
    echo "<p style='color: green;'>✅ Found TF-87A1-6BF0:</p>";
    echo "<p>ID: {$specific_cert->id}</p>";
    echo "<p>Amount: {$specific_cert->amount}€</p>";
    echo "<p>Created: {$specific_cert->created_at}</p>";
    echo "<p>Status: " . ($specific_cert->is_active ? 'Active' : 'Used') . "</p>";
} else {
    echo "<p style='color: red;'>❌ TF-87A1-6BF0 NOT FOUND</p>";
}

// Check highest ID
$max_id = $wpdb->get_var("SELECT MAX(id) FROM $table_name");
echo "<p><strong>Highest ID in database:</strong> $max_id</p>";

// Check for gaps in ID sequence
echo "<h2>ID Sequence Check:</h2>";
$ids = $wpdb->get_col("SELECT id FROM $table_name ORDER BY id");
$expected_ids = range(1, max($ids));
$missing_ids = array_diff($expected_ids, $ids);

if (!empty($missing_ids)) {
    echo "<p style='color: orange;'>⚠️ Missing IDs: " . implode(', ', $missing_ids) . "</p>";
} else {
    echo "<p style='color: green;'>✅ No gaps in ID sequence</p>";
}

?>
