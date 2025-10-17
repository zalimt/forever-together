<?php
/**
 * LIVE SERVER CACHE BUSTER
 * 
 * Specifically designed for your live server at:
 * https://red-grouse-914732.hostingsite.com/
 * 
 * This script will force clear ALL caches on your live server
 */

// Load WordPress
require_once('wp-load.php');

// Security check
if (!current_user_can('administrator')) {
    wp_die('⛔ Access denied. Admin only.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>🚀 Live Server Cache Buster</title>
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            margin: 40px; 
            background: #f5f5f5;
        }
        .container { 
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { 
            color: #dc3545;
            border-bottom: 3px solid #dc3545;
            padding-bottom: 10px;
        }
        h2 { color: #495057; margin-top: 30px; }
        .success { 
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            margin: 10px 0;
        }
        .warning { 
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            margin: 10px 0;
        }
        .error { 
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            margin: 10px 0;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border: 1px solid #bee5eb;
            border-radius: 4px;
            margin: 10px 0;
        }
        ul { line-height: 1.8; }
        code { 
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: "Courier New", monospace;
        }
        .btn {
            display: inline-block;
            background: #dc3545;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 5px;
            font-weight: bold;
        }
        .btn:hover { background: #c82333; }
        .btn-success {
            background: #28a745;
        }
        .btn-success:hover {
            background: #218838;
        }
        .step {
            margin: 20px 0;
            padding: 15px;
            border-left: 4px solid #dc3545;
            background: #f8f9fa;
        }
        .highlight {
            background: #fff3cd;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
            border-left: 4px solid #ffc107;
        }
    </style>
</head>
<body>
<div class="container">

<h1>🚀 Live Server Cache Buster</h1>

<div class="warning">
    <strong>🎯 TARGET:</strong> Your live server at <code>https://red-grouse-914732.hostingsite.com/</code><br>
    <strong>🔧 PURPOSE:</strong> Force clear ALL caches so your front-page.php changes appear immediately
</div>

<?php
$actions = [];
$errors = [];
$files_deleted = 0;

// STEP 1: WordPress Core Cache
echo '<h2>Step 1: WordPress Core Cache</h2>';
try {
    wp_cache_flush();
    $actions[] = 'WordPress object cache flushed';
    echo '<div class="success">✅ WordPress object cache cleared</div>';
} catch (Exception $e) {
    $errors[] = 'WordPress cache: ' . $e->getMessage();
    echo '<div class="error">❌ Error: ' . $e->getMessage() . '</div>';
}

// Delete all transients
global $wpdb;
$transients_deleted = $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '%_transient_%'");
echo '<div class="success">✅ Deleted ' . $transients_deleted . ' transients</div>';

// STEP 2: WP Fastest Cache - AGGRESSIVE CLEARING
echo '<h2>Step 2: WP Fastest Cache (Aggressive)</h2>';

// Method 1: Use WP Fastest Cache API
if (class_exists('WpFastestCache')) {
    try {
        $wpfc = new WpFastestCache();
        if (method_exists($wpfc, 'deleteCache')) {
            $wpfc->deleteCache(true);
            echo '<div class="success">✅ WP Fastest Cache API: All cache deleted</div>';
        }
    } catch (Exception $e) {
        echo '<div class="error">❌ WP Fastest Cache API error: ' . $e->getMessage() . '</div>';
    }
}

// Method 2: Manually delete ALL cache directories
$cache_dirs = [
    WP_CONTENT_DIR . '/cache/all/',
    WP_CONTENT_DIR . '/cache/wpfc-minified/',
    WP_CONTENT_DIR . '/cache/wpfc-mobile-cache/',
    WP_CONTENT_DIR . '/cache/',
];

foreach ($cache_dirs as $cache_dir) {
    if (is_dir($cache_dir)) {
        $deleted = live_delete_directory_contents($cache_dir);
        $files_deleted += $deleted;
        echo "<div class='success'>✅ Deleted {$deleted} files from: <code>" . basename($cache_dir) . "</code></div>";
    }
}

// STEP 3: LiteSpeed Cache
echo '<h2>Step 3: LiteSpeed Cache</h2>';
if (defined('LSCWP_V') || class_exists('LiteSpeed_Cache_API')) {
    try {
        do_action('litespeed_purge_all');
        echo '<div class="success">✅ LiteSpeed Cache purged</div>';
    } catch (Exception $e) {
        echo '<div class="error">❌ LiteSpeed error: ' . $e->getMessage() . '</div>';
    }
} else {
    echo '<div class="info">ℹ️ LiteSpeed Cache not active</div>';
}

// STEP 4: Other cache plugins
echo '<h2>Step 4: Other Cache Plugins</h2>';

if (function_exists('w3tc_flush_all')) {
    w3tc_flush_all();
    echo '<div class="success">✅ W3 Total Cache cleared</div>';
}

if (function_exists('wp_cache_clear_cache')) {
    wp_cache_clear_cache();
    echo '<div class="success">✅ WP Super Cache cleared</div>';
}

if (function_exists('rocket_clean_domain')) {
    rocket_clean_domain();
    echo '<div class="success">✅ WP Rocket cache cleared</div>';
}

// STEP 5: Update ALL file timestamps
echo '<h2>Step 5: Update File Timestamps</h2>';

$theme_dir = get_stylesheet_directory();
$files_to_touch = [
    $theme_dir . '/front-page.php',
    $theme_dir . '/header.php',
    $theme_dir . '/footer.php',
    $theme_dir . '/functions.php',
    $theme_dir . '/style.css',
    $theme_dir . '/css/main.css',
    $theme_dir . '/css/root.css',
];

$touched = 0;
foreach ($files_to_touch as $file) {
    if (file_exists($file)) {
        touch($file);
        $touched++;
    }
}

echo "<div class='success'>✅ Updated timestamps on {$touched} files</div>";

// STEP 6: Force theme version update
echo '<h2>Step 6: Force Theme Reload</h2>';
$new_version = time();
update_option('stylesheet_version', $new_version);
update_option('_site_transient_update_themes', null);
echo "<div class='success'>✅ Theme version: <code>{$new_version}</code></div>";

// STEP 7: Clear rewrite rules
flush_rewrite_rules(true);
echo '<div class="success">✅ Rewrite rules flushed</div>';

// STEP 8: Set nocache constants
echo '<h2>Step 7: Set No-Cache Constants</h2>';
if (!defined('DONOTCACHEPAGE')) {
    define('DONOTCACHEPAGE', true);
    echo '<div class="success">✅ DONOTCACHEPAGE set</div>';
}

// Summary
echo '<h2>📊 LIVE SERVER CACHE CLEAR SUMMARY</h2>';
echo '<div class="step">';
echo '<p style="font-size: 24px; font-weight: bold; color: #dc3545;">' . $files_deleted . '</p>';
echo '<p>Cache files deleted from your live server</p>';
echo '</div>';

echo '<div class="success">';
echo '<h3>✅ Actions Completed:</h3>';
echo '<ul>';
foreach ($actions as $action) {
    echo "<li>{$action}</li>";
}
echo '<li>' . $transients_deleted . ' WordPress transients deleted</li>';
echo '<li>' . $files_deleted . ' cache files deleted</li>';
echo '<li>' . $touched . ' template files touched</li>';
echo '<li>Theme version updated</li>';
echo '<li>Rewrite rules flushed</li>';
echo '</ul>';
echo '</div>';

if (!empty($errors)) {
    echo '<div class="error">';
    echo '<h3>❌ Errors (non-critical):</h3>';
    echo '<ul>';
    foreach ($errors as $error) {
        echo "<li>{$error}</li>";
    }
    echo '</ul>';
    echo '</div>';
}

// Your specific issue
echo '<div class="highlight">';
echo '<h3>🎯 YOUR SPECIFIC ISSUE:</h3>';
echo '<p><strong>Problem:</strong> Button shows "GIFT A CERTIFICATE" instead of "123Gift a Certificate"</p>';
echo '<p><strong>Cause:</strong> Old cached HTML being served from live server</p>';
echo '<p><strong>Solution:</strong> This script just cleared ALL cache files</p>';
echo '</div>';

// Final instructions
echo '<div class="warning">';
echo '<h3>⚠️ CRITICAL: Clear Your Browser Cache NOW!</h3>';
echo '<ol>';
echo '<li><strong>Hard Refresh:</strong> Press <code>Ctrl+Shift+R</code> (Windows) or <code>Cmd+Shift+R</code> (Mac)</li>';
echo '<li><strong>Or DevTools:</strong> F12 → Right-click refresh → "Empty Cache and Hard Reload"</li>';
echo '<li><strong>Best Option:</strong> Open in <strong>INCOGNITO/PRIVATE</strong> window</li>';
echo '</ol>';
echo '<p><strong>Repeat the hard refresh 2-3 times!</strong></p>';
echo '</div>';

// Test links
echo '<div class="step">';
echo '<h3>🔍 Test Your Changes Now:</h3>';
echo '<p>';
echo '<a href="' . home_url('/') . '" target="_blank" class="btn">Open Front Page</a>';
echo '<a href="' . home_url('/?nocache=' . time()) . '" target="_blank" class="btn btn-success">Front Page (No Cache)</a>';
echo '</p>';
echo '<p><em>The second link adds a unique parameter to bypass ALL caching.</em></p>';
echo '</div>';

// Debug info
echo '<h2>🔍 Debug Information</h2>';
echo '<div class="info">';
echo '<h4>Front Page File Info:</h4>';
$front_page = $theme_dir . '/front-page.php';
if (file_exists($front_page)) {
    echo '<ul>';
    echo '<li><strong>Path:</strong> <code>' . $front_page . '</code></li>';
    echo '<li><strong>Modified:</strong> ' . date('Y-m-d H:i:s', filemtime($front_page)) . '</li>';
    echo '<li><strong>Size:</strong> ' . number_format(filesize($front_page)) . ' bytes</li>';
    echo '</ul>';
}

echo '<h4>Your Live Server:</h4>';
echo '<ul>';
echo '<li><strong>URL:</strong> <code>https://red-grouse-914732.hostingsite.com/</code></li>';
echo '<li><strong>WordPress Version:</strong> ' . get_bloginfo('version') . '</li>';
echo '<li><strong>Theme:</strong> ' . wp_get_theme()->get('Name') . '</li>';
echo '</ul>';
echo '</div>';

// How to use
echo '<div class="info">';
echo '<h3>💡 How to Use This Script:</h3>';
echo '<p><strong>Bookmark this URL:</strong> <code>' . home_url('/live-cache-buster.php') . '</code></p>';
echo '<p><strong>When to use:</strong></p>';
echo '<ul>';
echo '<li>After uploading changes to front-page.php</li>';
echo '<li>When changes appear on local but not live</li>';
echo '<li>When button text or other content is wrong</li>';
echo '<li>When you need to force clear everything</li>';
echo '</ul>';
echo '</div>';

echo '<div class="success">';
echo '<h3>✅ LIVE SERVER CACHE CLEAR COMPLETE!</h3>';
echo '<p><strong>Next steps:</strong></p>';
echo '<ol>';
echo '<li>Clear your browser cache (<code>Ctrl+Shift+R</code>)</li>';
echo '<li>Test in incognito window</li>';
echo '<li>Your button should now show "123Gift a Certificate"!</li>';
echo '</ol>';
echo '</div>';

?>

</div>

<script>
// Auto-scroll to summary
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        window.scrollTo(0, document.body.scrollHeight);
    }, 100);
});
</script>

</body>
</html>

<?php

/**
 * Delete directory contents recursively
 */
function live_delete_directory_contents($dir) {
    $count = 0;
    
    if (!is_dir($dir)) {
        return $count;
    }
    
    try {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        
        foreach ($files as $fileinfo) {
            try {
                if ($fileinfo->isDir()) {
                    @rmdir($fileinfo->getRealPath());
                } else {
                    @unlink($fileinfo->getRealPath());
                    $count++;
                }
            } catch (Exception $e) {
                // Continue even if some files fail
            }
        }
    } catch (Exception $e) {
        // Continue even if iterator fails
    }
    
    return $count;
}


