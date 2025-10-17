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

// Clear all caches
wp_cache_flush();

// Clear any plugin caches
if (function_exists('w3tc_flush_all')) w3tc_flush_all();
if (function_exists('wp_cache_clear_cache')) wp_cache_clear_cache();
if (function_exists('rocket_clean_domain')) rocket_clean_domain();
if (function_exists('litespeed_purge_all')) litespeed_purge_all();

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

echo "<h1>⚡ Cache Cleared Successfully!</h1>";
echo "<p>All caches have been cleared and CSS files updated.</p>";
echo "<p><strong>Now clear your browser cache:</strong></p>";
echo "<ul>";
echo "<li>Press <strong>Ctrl+Shift+R</strong> (Windows) or <strong>Cmd+Shift+R</strong> (Mac)</li>";
echo "<li>Or open Developer Tools (F12) → Right-click refresh button → Empty Cache and Hard Reload</li>";
echo "</ul>";

echo "<p><a href='" . home_url('/certificate/') . "' target='_blank'>Test Certificate Page</a> | ";
echo "<a href='" . home_url('/activate-certificate/') . "' target='_blank'>Test Activation Page</a></p>";

echo "<p><small>Bookmark this page: <code>" . home_url('/clear-cache-now.php') . "</code></small></p>";
?>
