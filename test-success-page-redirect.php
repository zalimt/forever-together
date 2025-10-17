<?php
/**
 * Test success page redirect
 * Access: http://forever-together.local/test-success-page-redirect.php
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>Test Success Page Redirect</h1>";

// Check if the WordPress page exists
$success_page = get_page_by_path('certificate-success');
if ($success_page) {
    echo "<p style='color: green;'>✅ WordPress page 'certificate-success' exists</p>";
    echo "<p>Page ID: {$success_page->ID}</p>";
    echo "<p>Page Status: {$success_page->post_status}</p>";
    echo "<p>Page URL: " . get_permalink($success_page->ID) . "</p>";
} else {
    echo "<p style='color: red;'>❌ WordPress page 'certificate-success' not found</p>";
}

// Test the redirect URL
$test_session_id = 'cs_test_123456789';
$success_url = home_url('/certificate-success') . '?session_id=' . $test_session_id;

echo "<h2>Test Success URL:</h2>";
echo "<p><strong>Success URL:</strong> <a href='$success_url' target='_blank'>$success_url</a></p>";

echo "<h2>Template Files:</h2>";
$template_files = [
    'certificate-success.php',
    'page-certificate-success.php'
];

foreach ($template_files as $file) {
    $file_path = get_stylesheet_directory() . '/' . $file;
    if (file_exists($file_path)) {
        echo "<p style='color: green;'>✅ $file exists</p>";
    } else {
        echo "<p style='color: red;'>❌ $file not found</p>";
    }
}

echo "<h2>Test the Success Page:</h2>";
echo "<p><a href='certificate-success' target='_blank'>Visit Success Page Directly</a></p>";

echo "<h2>Debug Recent Sessions:</h2>";
echo "<p><a href='check-recent-sessions.php'>Check Recent Sessions</a></p>";

?>
