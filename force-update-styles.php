<?php
/**
 * Force Update Styles - Immediate Effect
 * 
 * This script forces WordPress to use the latest CSS files
 * by updating their timestamps and clearing all caches.
 */

// Load WordPress
require_once('wp-load.php');

// Check if user is admin
if (!current_user_can('administrator')) {
    wp_die('Access denied. Admin only.');
}

echo "<h1>⚡ Force Update Styles - Immediate Effect</h1>";

echo "<h2>1. Updating CSS File Timestamps</h2>";

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
        // Touch the file to update its timestamp
        touch($file_path);
        $new_time = date('Y-m-d H:i:s', filemtime($file_path));
        echo "<p>✅ Updated <code>$css_file</code> - New timestamp: $new_time</p>";
        
        // Also update the .map file if it exists
        $map_file = $file_path . '.map';
        if (file_exists($map_file)) {
            touch($map_file);
            echo "<p>✅ Updated <code>$css_file.map</code></p>";
        }
    } else {
        echo "<p>❌ File not found: <code>$css_file</code></p>";
    }
}

echo "<h2>2. Clearing All Caches</h2>";

// Clear WordPress object cache
wp_cache_flush();
echo "<p>✅ WordPress object cache cleared</p>";

// Clear any plugin caches
if (function_exists('w3tc_flush_all')) {
    w3tc_flush_all();
    echo "<p>✅ W3 Total Cache cleared</p>";
}

if (function_exists('wp_cache_clear_cache')) {
    wp_cache_clear_cache();
    echo "<p>✅ WP Super Cache cleared</p>";
}

if (function_exists('rocket_clean_domain')) {
    rocket_clean_domain();
    echo "<p>✅ WP Rocket cache cleared</p>";
}

// Clear LiteSpeed cache
if (function_exists('litespeed_purge_all')) {
    litespeed_purge_all();
    echo "<p>✅ LiteSpeed cache cleared</p>";
}

echo "<h2>3. Updating Theme Version</h2>";

// Force WordPress to reload theme files by updating version
$current_theme = wp_get_theme();
$current_version = $current_theme->get('Version');
$new_version = $current_version . '.' . time();

// Update theme version in database
update_option('stylesheet_version', $new_version);
echo "<p>✅ Theme version updated to: $new_version</p>";

echo "<h2>4. CSS URLs with Cache Busting</h2>";

echo "<p>Your CSS files are now loaded with fresh timestamps:</p>";
echo "<ul>";

foreach ($css_files as $css_file) {
    $file_path = $theme_css_dir . $css_file;
    if (file_exists($file_path)) {
        $timestamp = filemtime($file_path);
        $url = get_stylesheet_directory_uri() . "/css/$css_file?v=$timestamp";
        echo "<li><a href='$url' target='_blank'>$css_file</a> (timestamp: $timestamp)</li>";
    }
}

echo "</ul>";

echo "<h2>5. Browser Cache Busting</h2>";
echo "<div style='background: #fff3cd; color: #856404; padding: 15px; border: 1px solid #ffeaa7; border-radius: 4px; margin: 20px 0;'>";
echo "<h3>⚠️ Important: Clear Your Browser Cache</h3>";
echo "<p>After running this script, you need to clear your browser cache:</p>";
echo "<ul>";
echo "<li><strong>Chrome/Firefox:</strong> Press Ctrl+Shift+R (Windows) or Cmd+Shift+R (Mac)</li>";
echo "<li><strong>Or:</strong> Open Developer Tools (F12) → Right-click refresh button → Empty Cache and Hard Reload</li>";
echo "</ul>";
echo "</div>";

echo "<h2>6. Test Your Changes</h2>";
echo "<p>Now test your pages:</p>";
echo "<ul>";
echo "<li><a href='" . home_url('/certificate/') . "' target='_blank'>Certificate Page</a></li>";
echo "<li><a href='" . home_url('/activate-certificate/') . "' target='_blank'>Activate Certificate Page</a></li>";
echo "</ul>";

echo "<h2>7. Automatic Cache Busting Setup</h2>";
echo "<p>To prevent this issue in the future, add this to your theme's functions.php:</p>";
echo "<pre style='background: #f8f9fa; padding: 15px; border-radius: 4px; overflow-x: auto;'>";
echo htmlspecialchars('
// Auto cache busting for CSS files
function add_cache_busting_to_styles($src, $handle) {
    if (strpos($src, \'.css\') !== false) {
        $file_path = str_replace(get_stylesheet_directory_uri(), get_stylesheet_directory(), $src);
        if (file_exists($file_path)) {
            $timestamp = filemtime($file_path);
            $src = add_query_arg(\'v\', $timestamp, $src);
        }
    }
    return $src;
}
add_filter(\'style_loader_src\', \'add_cache_busting_to_styles\', 10, 2);
');
echo "</pre>";

echo "<div style='background: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 4px; margin: 20px 0;'>";
echo "✅ <strong>All updates completed!</strong> Your changes should now be visible immediately.";
echo "</div>";

echo "<p><strong>Note:</strong> Run this script whenever you make CSS or PHP changes and they don't appear immediately.</p>";
?>
