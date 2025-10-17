<?php
/**
 * Clear Front Page Cache - Emergency Cache Buster
 * 
 * Run this script on your LIVE server when front-page.php changes don't appear
 * URL: yourdomain.com/clear-front-page-cache.php
 * 
 * This script specifically targets all caching layers that might prevent
 * template changes from appearing on your live site.
 */

// Load WordPress
require_once('wp-load.php');

// Security: Only allow admins
if (!current_user_can('administrator')) {
    wp_die('⛔ Access denied. Admin only.');
}

// Start output
echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Front Page Cache Clearer</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2271b1; border-bottom: 3px solid #2271b1; padding-bottom: 10px; }
        h2 { color: #135e96; margin-top: 30px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 4px; margin: 10px 0; }
        .warning { background: #fff3cd; color: #856404; padding: 15px; border: 1px solid #ffeaa7; border-radius: 4px; margin: 10px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 4px; margin: 10px 0; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px; border: 1px solid #bee5eb; border-radius: 4px; margin: 10px 0; }
        ul { line-height: 1.8; }
        code { background: #f8f9fa; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
        .btn { display: inline-block; background: #2271b1; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; margin: 10px 5px; }
        .btn:hover { background: #135e96; }
        .step { margin: 20px 0; padding: 15px; border-left: 4px solid #2271b1; background: #f8f9fa; }
    </style>
</head>
<body>
<div class="container">';

echo '<h1>🧹 Front Page Cache Clearer</h1>';
echo '<p><strong>Purpose:</strong> This script clears ALL caching layers to ensure your front-page.php changes appear on the live site.</p>';

$actions_taken = array();
$errors = array();

// Step 1: Clear WordPress Object Cache
echo '<h2>Step 1: WordPress Cache</h2>';
try {
    wp_cache_flush();
    $actions_taken[] = '✅ WordPress object cache flushed';
    echo '<div class="success">WordPress object cache cleared successfully!</div>';
} catch (Exception $e) {
    $errors[] = '❌ WordPress cache: ' . $e->getMessage();
    echo '<div class="error">Error: ' . $e->getMessage() . '</div>';
}

// Step 2: Clear WP Fastest Cache (THE MAIN CULPRIT)
echo '<h2>Step 2: WP Fastest Cache</h2>';
if (class_exists('WpFastestCache')) {
    try {
        // Method 1: Use the class directly
        $wpfc = new WpFastestCache();
        if (method_exists($wpfc, 'deleteCache')) {
            $wpfc->deleteCache(true);
            $actions_taken[] = '✅ WP Fastest Cache: All cached pages deleted';
            echo '<div class="success"><strong>✅ WP Fastest Cache cleared!</strong> (This is the most important one for template changes)</div>';
        }
        
        // Method 2: Delete cache files directly
        $cache_path = WP_CONTENT_DIR . '/cache/all/';
        if (is_dir($cache_path)) {
            $deleted_files = together_forever_delete_directory_contents($cache_path);
            $actions_taken[] = "✅ Deleted {$deleted_files} cached HTML files";
            echo "<div class='success'>Deleted {$deleted_files} cached HTML files from {$cache_path}</div>";
        }
    } catch (Exception $e) {
        $errors[] = '❌ WP Fastest Cache: ' . $e->getMessage();
        echo '<div class="error">Error: ' . $e->getMessage() . '</div>';
    }
} else {
    echo '<div class="info">WP Fastest Cache plugin not active or not found.</div>';
}

// Step 3: Clear LiteSpeed Cache
echo '<h2>Step 3: LiteSpeed Cache</h2>';
if (defined('LSCWP_V')) {
    try {
        do_action('litespeed_purge_all');
        $actions_taken[] = '✅ LiteSpeed Cache purged';
        echo '<div class="success">LiteSpeed cache cleared!</div>';
    } catch (Exception $e) {
        $errors[] = '❌ LiteSpeed: ' . $e->getMessage();
        echo '<div class="error">Error: ' . $e->getMessage() . '</div>';
    }
} else {
    echo '<div class="info">LiteSpeed Cache not active.</div>';
}

// Step 4: Clear other cache plugins
echo '<h2>Step 4: Other Cache Plugins</h2>';

// W3 Total Cache
if (function_exists('w3tc_flush_all')) {
    try {
        w3tc_flush_all();
        $actions_taken[] = '✅ W3 Total Cache cleared';
        echo '<div class="success">W3 Total Cache cleared!</div>';
    } catch (Exception $e) {
        $errors[] = '❌ W3TC: ' . $e->getMessage();
    }
}

// WP Super Cache
if (function_exists('wp_cache_clear_cache')) {
    try {
        wp_cache_clear_cache();
        $actions_taken[] = '✅ WP Super Cache cleared';
        echo '<div class="success">WP Super Cache cleared!</div>';
    } catch (Exception $e) {
        $errors[] = '❌ WP Super Cache: ' . $e->getMessage();
    }
}

// WP Rocket
if (function_exists('rocket_clean_domain')) {
    try {
        rocket_clean_domain();
        $actions_taken[] = '✅ WP Rocket cache cleared';
        echo '<div class="success">WP Rocket cache cleared!</div>';
    } catch (Exception $e) {
        $errors[] = '❌ WP Rocket: ' . $e->getMessage();
    }
}

// Step 5: Update theme version for cache busting
echo '<h2>Step 5: Theme Version Update</h2>';
try {
    $current_version = wp_get_theme()->get('Version');
    $new_version = $current_version . '.' . time();
    update_option('stylesheet_version', $new_version);
    $actions_taken[] = "✅ Theme version updated to {$new_version}";
    echo "<div class='success'>Theme version updated to: <code>{$new_version}</code></div>";
} catch (Exception $e) {
    $errors[] = '❌ Theme version: ' . $e->getMessage();
    echo '<div class="error">Error: ' . $e->getMessage() . '</div>';
}

// Step 6: Touch front-page.php to update timestamp
echo '<h2>Step 6: Template File Timestamp</h2>';
try {
    $front_page_file = get_stylesheet_directory() . '/front-page.php';
    if (file_exists($front_page_file)) {
        touch($front_page_file);
        $timestamp = date('Y-m-d H:i:s', filemtime($front_page_file));
        $actions_taken[] = "✅ front-page.php timestamp updated: {$timestamp}";
        echo "<div class='success'>front-page.php timestamp updated: <code>{$timestamp}</code></div>";
    } else {
        $errors[] = '❌ front-page.php not found';
        echo '<div class="error">front-page.php file not found!</div>';
    }
} catch (Exception $e) {
    $errors[] = '❌ File timestamp: ' . $e->getMessage();
    echo '<div class="error">Error: ' . $e->getMessage() . '</div>';
}

// Summary
echo '<h2>📊 Summary</h2>';

if (!empty($actions_taken)) {
    echo '<div class="success">';
    echo '<h3>✅ Actions Completed:</h3>';
    echo '<ul>';
    foreach ($actions_taken as $action) {
        echo "<li>{$action}</li>";
    }
    echo '</ul>';
    echo '</div>';
}

if (!empty($errors)) {
    echo '<div class="error">';
    echo '<h3>❌ Errors:</h3>';
    echo '<ul>';
    foreach ($errors as $error) {
        echo "<li>{$error}</li>";
    }
    echo '</ul>';
    echo '</div>';
}

// Next steps
echo '<div class="warning">';
echo '<h3>⚠️ IMPORTANT: Next Steps</h3>';
echo '<ol>';
echo '<li><strong>Clear your browser cache:</strong> Press <code>Ctrl+Shift+R</code> (Windows) or <code>Cmd+Shift+R</code> (Mac)</li>';
echo '<li><strong>Or:</strong> Open Developer Tools (F12) → Right-click refresh button → "Empty Cache and Hard Reload"</li>';
echo '<li><strong>Check in incognito/private window</strong> to ensure caches are truly cleared</li>';
echo '</ol>';
echo '</div>';

// Test links
echo '<div class="step">';
echo '<h3>🔍 Test Your Front Page Now:</h3>';
echo '<p><a href="' . home_url('/') . '" target="_blank" class="btn">Open Front Page in New Tab</a></p>';
echo '<p><em>Open in a new incognito window to bypass browser cache completely.</em></p>';
echo '</div>';

// Instructions for future
echo '<div class="info">';
echo '<h3>💡 For Future Template Changes:</h3>';
echo '<p>Bookmark this page: <code>' . home_url('/clear-front-page-cache.php') . '</code></p>';
echo '<p>Run this script every time you update front-page.php on the live server and changes don\'t appear.</p>';
echo '</div>';

// Automatic cache clearing info
echo '<div class="info">';
echo '<h3>🤖 Automatic Cache Clearing</h3>';
echo '<p>Your theme now includes automatic cache clearing! When you upload an updated front-page.php to the live server:</p>';
echo '<ul>';
echo '<li>The cache will automatically clear within 60 seconds of the file being modified</li>';
echo '<li>You just need to wait a minute and refresh</li>';
echo '<li>Or run this script manually for instant results</li>';
echo '</ul>';
echo '</div>';

echo '</div></body></html>';

// Helper function to delete directory contents
function together_forever_delete_directory_contents($dir) {
    $count = 0;
    if (!is_dir($dir)) {
        return $count;
    }
    
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    
    foreach ($files as $fileinfo) {
        try {
            if ($fileinfo->isDir()) {
                rmdir($fileinfo->getRealPath());
            } else {
                unlink($fileinfo->getRealPath());
                $count++;
            }
        } catch (Exception $e) {
            // Continue even if some files can't be deleted
        }
    }
    
    return $count;
}

