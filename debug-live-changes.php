<?php
/**
 * Debug Live Site Changes
 * 
 * This script helps identify why changes aren't showing on the live site.
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>🔍 Debug Live Site Changes</h1>";

echo "<h2>1. File Timestamps</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>File</th><th>Last Modified</th><th>Size</th><th>Exists</th></tr>";

$files_to_check = array(
    'wp-content/themes/together-forever/functions.php',
    'wp-content/themes/together-forever/certificate.php',
    'wp-content/themes/together-forever/activate-certificate.php',
    'wp-content/themes/together-forever/css/certificate.css',
    'wp-content/themes/together-forever/css/activate-certificate.css',
    'wp-content/themes/together-forever/css/main.css',
    'wp-content/themes/together-forever/inc/certificate-system.php',
    'wp-content/themes/together-forever/inc/stripe-integration.php'
);

foreach ($files_to_check as $file) {
    $full_path = ABSPATH . $file;
    $exists = file_exists($full_path);
    $modified = $exists ? date('Y-m-d H:i:s', filemtime($full_path)) : 'N/A';
    $size = $exists ? filesize($full_path) . ' bytes' : 'N/A';
    
    echo "<tr>";
    echo "<td><code>$file</code></td>";
    echo "<td>$modified</td>";
    echo "<td>$size</td>";
    echo "<td>" . ($exists ? "✅ Yes" : "❌ No") . "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h2>2. WordPress Cache Status</h2>";
echo "<p><strong>Object Cache:</strong> " . (wp_using_ext_object_cache() ? "✅ Enabled" : "❌ Disabled") . "</p>";
echo "<p><strong>Cache Plugins:</strong></p>";

$cache_plugins = array(
    'w3-total-cache/w3-total-cache.php',
    'wp-super-cache/wp-super-cache.php',
    'wp-rocket/wp-rocket.php',
    'litespeed-cache/litespeed-cache.php',
    'wp-fastest-cache/wp-fastest-cache.php'
);

foreach ($cache_plugins as $plugin) {
    $active = is_plugin_active($plugin);
    echo "<p>- <code>$plugin</code>: " . ($active ? "✅ Active" : "❌ Inactive") . "</p>";
}

echo "<h2>3. Theme Status</h2>";
$current_theme = wp_get_theme();
echo "<p><strong>Active Theme:</strong> " . $current_theme->get('Name') . " v" . $current_theme->get('Version') . "</p>";
echo "<p><strong>Theme Directory:</strong> <code>" . get_stylesheet_directory() . "</code></p>";
echo "<p><strong>Theme URL:</strong> <code>" . get_stylesheet_directory_uri() . "</code></p>";

echo "<h2>4. CSS File URLs</h2>";
$css_files = array(
    'certificate.css',
    'activate-certificate.css',
    'main.css'
);

foreach ($css_files as $css_file) {
    $url = get_stylesheet_directory_uri() . '/css/' . $css_file;
    echo "<p><strong>$css_file:</strong> <a href='$url' target='_blank'>$url</a></p>";
    
    // Check if file exists and get its timestamp
    $file_path = get_stylesheet_directory() . '/css/' . $css_file;
    if (file_exists($file_path)) {
        $modified = date('Y-m-d H:i:s', filemtime($file_path));
        echo "<p style='margin-left: 20px; color: #666;'>Last modified: $modified</p>";
    }
}

echo "<h2>5. Database Certificate Table</h2>";
global $wpdb;
$table_name = $wpdb->prefix . 'tf_certificates';

$table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");
echo "<p><strong>Certificate Table:</strong> " . ($table_exists ? "✅ Exists" : "❌ Missing") . "</p>";

if ($table_exists) {
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    echo "<p><strong>Certificate Count:</strong> $count</p>";
}

echo "<h2>6. Stripe Configuration</h2>";
echo "<p><strong>Publishable Key:</strong> " . (get_option('tf_stripe_publishable_key') ? "✅ Set" : "❌ Not Set") . "</p>";
echo "<p><strong>Secret Key:</strong> " . (get_option('tf_stripe_secret_key') ? "✅ Set" : "❌ Not Set") . "</p>";
echo "<p><strong>Webhook Secret:</strong> " . (get_option('tf_stripe_webhook_secret') ? "✅ Set" : "❌ Not Set") . "</p>";

echo "<h2>7. Server Information</h2>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>WordPress Version:</strong> " . get_bloginfo('version') . "</p>";
echo "<p><strong>Site URL:</strong> " . get_site_url() . "</p>";
echo "<p><strong>Home URL:</strong> " . get_home_url() . "</p>";

echo "<h2>8. Quick Fixes</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 20px 0;'>";
echo "<h3>Clear All Caches</h3>";
echo "<button onclick='clearCaches()' style='background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;'>Clear All Caches</button>";
echo "</div>";

echo "<script>";
echo "function clearCaches() {";
echo "  if (confirm('Clear all caches? This will help show your latest changes.')) {";
echo "    fetch('" . admin_url('admin-ajax.php') . "', {";
echo "      method: 'POST',";
echo "      headers: {'Content-Type': 'application/x-www-form-urlencoded'},";
echo "      body: 'action=clear_all_caches'";
echo "    }).then(response => {";
echo "      alert('Caches cleared! Please refresh the page.');";
echo "      location.reload();";
echo "    });";
echo "  }";
echo "}";
echo "</script>";

// Handle cache clearing
if (isset($_POST['action']) && $_POST['action'] === 'clear_all_caches') {
    // Clear WordPress caches
    wp_cache_flush();
    
    // Clear object cache if available
    if (function_exists('wp_cache_delete')) {
        wp_cache_delete('alloptions', 'options');
    }
    
    // Clear any plugin caches
    if (function_exists('w3tc_flush_all')) {
        w3tc_flush_all();
    }
    
    if (function_exists('wp_cache_clear_cache')) {
        wp_cache_clear_cache();
    }
    
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 4px; margin: 20px 0;'>";
    echo "✅ All caches cleared successfully!";
    echo "</div>";
}

echo "<p><strong>Note:</strong> If changes still don't appear, check browser cache (Ctrl+F5 or Cmd+Shift+R)</p>";
?>
