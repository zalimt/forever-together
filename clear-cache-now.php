<?php
/**
 * One-Click Cache Clear
 * 
 * Run this script whenever you make changes and they don't appear immediately.
 * Bookmark this URL for instant cache clearing.
 */

// Load WordPress
require_once('wp-load.php');

// Check if user is admin
if (!current_user_can('administrator')) {
    wp_die('Access denied. Admin only.');
}

echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cache Cleared</title>
    <style>
        body { font-family: -apple-system, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        h1 { color: #2271b1; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin: 10px 0; }
        code { background: #f8f9fa; padding: 2px 6px; border-radius: 3px; }
        ul { line-height: 1.8; }
    </style>
</head>
<body>
<div class="container">';

// Clear all caches
wp_cache_flush();

// Clear WP Fastest Cache (CRITICAL FOR TEMPLATE CHANGES)
if (class_exists('WpFastestCache')) {
    $wpfc = new WpFastestCache();
    if (method_exists($wpfc, 'deleteCache')) {
        $wpfc->deleteCache(true);
    }
    // Also delete cache files directly
    $cache_path = WP_CONTENT_DIR . '/cache/all/';
    if (is_dir($cache_path)) {
        array_map('unlink', glob("$cache_path/*"));
    }
}

// Clear any plugin caches
if (function_exists('w3tc_flush_all')) w3tc_flush_all();
if (function_exists('wp_cache_clear_cache')) wp_cache_clear_cache();
if (function_exists('rocket_clean_domain')) rocket_clean_domain();
if (function_exists('litespeed_purge_all')) litespeed_purge_all();
if (defined('LSCWP_V')) do_action('litespeed_purge_all');

// Update theme version to force reload
$current_version = wp_get_theme()->get('Version');
update_option('stylesheet_version', $current_version . '.' . time());

// Touch CSS files to update timestamps
$css_files = array(
    'certificate.css',
    'activate-certificate.css', 
    'main.css',
    'root.css'
);

$theme_css_dir = get_stylesheet_directory() . '/css/';
foreach ($css_files as $css_file) {
    $file_path = $theme_css_dir . $css_file;
    if (file_exists($file_path)) {
        touch($file_path);
    }
}

// Touch template files
$template_files = array('front-page.php', 'header.php', 'footer.php');
foreach ($template_files as $template_file) {
    $file_path = get_stylesheet_directory() . '/' . $template_file;
    if (file_exists($file_path)) {
        touch($file_path);
    }
}

echo "<h1>⚡ Cache Cleared Successfully!</h1>";
echo "<div class='success'>";
echo "<p><strong>✅ All caches cleared:</strong></p>";
echo "<ul>";
echo "<li>WordPress object cache</li>";
echo "<li>WP Fastest Cache (static HTML files)</li>";
echo "<li>LiteSpeed Cache</li>";
echo "<li>CSS file timestamps updated</li>";
echo "<li>Template file timestamps updated</li>";
echo "<li>Theme version updated</li>";
echo "</ul>";
echo "</div>";

echo "<p><strong>Now clear your browser cache:</strong></p>";
echo "<ul>";
echo "<li>Press <strong>Ctrl+Shift+R</strong> (Windows) or <strong>Cmd+Shift+R</strong> (Mac)</li>";
echo "<li>Or open Developer Tools (F12) → Right-click refresh button → Empty Cache and Hard Reload</li>";
echo "</ul>";

echo "<p><strong>Test your pages:</strong></p>";
echo "<p><a href='" . home_url('/') . "' target='_blank'>Front Page</a> | ";
echo "<a href='" . home_url('/certificate/') . "' target='_blank'>Certificate Page</a> | ";
echo "<a href='" . home_url('/activate-certificate/') . "' target='_blank'>Activation Page</a></p>";

echo "<p><small>Bookmark this page: <code>" . home_url('/clear-cache-now.php') . "</code></small></p>";
echo "</div></body></html>";
?>
