<?php
/**
 * Temporarily Disable Cache - Emergency Solution
 * 
 * This script temporarily disables WP Fastest Cache completely
 * Use when cache keeps regenerating after clearing
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
    <title>🚫 Disable Cache Temporarily</title>
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            margin: 40px; 
            background: #f5f5f5;
        }
        .container { 
            max-width: 800px;
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
        ul { line-height: 1.8; }
        code { 
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: "Courier New", monospace;
        }
    </style>
</head>
<body>
<div class="container">

<h1>🚫 Temporarily Disable Cache</h1>

<div class="warning">
    <strong>⚠️ WARNING:</strong> This will temporarily disable ALL caching on your site.
    Your site will be slower but you'll see changes immediately.
</div>

<?php
$actions = [];

// Check current cache status
echo '<h2>Current Cache Status</h2>';

if (class_exists('WpFastestCache')) {
    echo '<div class="info">✅ WP Fastest Cache is ACTIVE</div>';
} else {
    echo '<div class="info">❌ WP Fastest Cache not found</div>';
}

if (defined('LSCWP_V')) {
    echo '<div class="info">✅ LiteSpeed Cache is ACTIVE</div>';
} else {
    echo '<div class="info">❌ LiteSpeed Cache not found</div>';
}

// Handle disable action
if (isset($_POST['disable_cache'])) {
    echo '<h2>Disabling Cache...</h2>';
    
    // Method 1: Disable WP Fastest Cache plugin
    if (class_exists('WpFastestCache')) {
        // Deactivate the plugin temporarily
        deactivate_plugins('wp-fastest-cache/wpFastestCache.php');
        $actions[] = 'WP Fastest Cache plugin deactivated';
        echo '<div class="success">✅ WP Fastest Cache plugin deactivated</div>';
    }
    
    // Method 2: Set constants to disable caching
    if (!defined('DONOTCACHEPAGE')) {
        define('DONOTCACHEPAGE', true);
        $actions[] = 'DONOTCACHEPAGE constant set';
        echo '<div class="success">✅ DONOTCACHEPAGE constant set</div>';
    }
    
    if (!defined('DONOTCACHEOBJECT')) {
        define('DONOTCACHEOBJECT', true);
        $actions[] = 'DONOTCACHEOBJECT constant set';
        echo '<div class="success">✅ DONOTCACHEOBJECT constant set</div>';
    }
    
    if (!defined('DONOTCACHEDB')) {
        define('DONOTCACHEDB', true);
        $actions[] = 'DONOTCACHEDB constant set';
        echo '<div class="success">✅ DONOTCACHEDB constant set</div>';
    }
    
    // Method 3: Clear all existing cache
    wp_cache_flush();
    $actions[] = 'WordPress object cache cleared';
    echo '<div class="success">✅ WordPress object cache cleared</div>';
    
    // Method 4: Set transient to disable cache for 1 hour
    set_transient('together_forever_disable_cache', true, 3600);
    $actions[] = 'Cache disabled for 1 hour';
    echo '<div class="success">✅ Cache disabled for 1 hour</div>';
    
    // Method 5: Update .htaccess to disable cache
    $htaccess_file = ABSPATH . '.htaccess';
    if (file_exists($htaccess_file)) {
        $htaccess_content = file_get_contents($htaccess_file);
        
        // Add cache disabling rules
        $cache_disable_rules = "\n# DISABLE CACHE - Added by cache fix\n";
        $cache_disable_rules .= "<IfModule mod_headers.c>\n";
        $cache_disable_rules .= "Header set Cache-Control \"no-cache, no-store, must-revalidate\"\n";
        $cache_disable_rules .= "Header set Pragma \"no-cache\"\n";
        $cache_disable_rules .= "Header set Expires \"0\"\n";
        $cache_disable_rules .= "</IfModule>\n";
        
        // Only add if not already present
        if (strpos($htaccess_content, 'DISABLE CACHE - Added by cache fix') === false) {
            file_put_contents($htaccess_file, $htaccess_content . $cache_disable_rules);
            $actions[] = '.htaccess updated to disable cache';
            echo '<div class="success">✅ .htaccess updated to disable cache</div>';
        } else {
            echo '<div class="info">ℹ️ .htaccess already has cache disabling rules</div>';
        }
    }
    
    echo '<div class="success">';
    echo '<h3>✅ Cache Disabled Successfully!</h3>';
    echo '<p><strong>Actions completed:</strong></p>';
    echo '<ul>';
    foreach ($actions as $action) {
        echo "<li>{$action}</li>";
    }
    echo '</ul>';
    echo '</div>';
    
    echo '<div class="warning">';
    echo '<h3>⚠️ IMPORTANT:</h3>';
    echo '<ol>';
    echo '<li><strong>Cache is now DISABLED for 1 hour</strong></li>';
    echo '<li><strong>Your site will be slower</strong> but changes will appear immediately</li>';
    echo '<li><strong>Make your changes now</strong> while cache is disabled</li>';
    echo '<li><strong>Re-enable cache later</strong> for better performance</li>';
    echo '</ol>';
    echo '</div>';
    
} else {
    // Show disable form
    echo '<h2>Disable Cache</h2>';
    echo '<form method="post">';
    echo '<p>Click the button below to temporarily disable ALL caching on your site.</p>';
    echo '<p><input type="submit" name="disable_cache" value="🚫 Disable Cache for 1 Hour" class="btn" onclick="return confirm(\'This will disable ALL caching for 1 hour. Your site will be slower but changes will appear immediately. Continue?\');"></p>';
    echo '</form>';
}

// Handle re-enable action
if (isset($_POST['enable_cache'])) {
    echo '<h2>Re-enabling Cache...</h2>';
    
    // Remove transient
    delete_transient('together_forever_disable_cache');
    echo '<div class="success">✅ Cache disable transient removed</div>';
    
    // Reactivate WP Fastest Cache
    if (file_exists(WP_PLUGIN_DIR . '/wp-fastest-cache/wpFastestCache.php')) {
        activate_plugin('wp-fastest-cache/wpFastestCache.php');
        echo '<div class="success">✅ WP Fastest Cache plugin reactivated</div>';
    }
    
    // Remove cache disabling rules from .htaccess
    $htaccess_file = ABSPATH . '.htaccess';
    if (file_exists($htaccess_file)) {
        $htaccess_content = file_get_contents($htaccess_file);
        $htaccess_content = preg_replace('/\n# DISABLE CACHE - Added by cache fix.*?<\/IfModule>\n/s', "\n", $htaccess_content);
        file_put_contents($htaccess_file, $htaccess_content);
        echo '<div class="success">✅ .htaccess cache disabling rules removed</div>';
    }
    
    echo '<div class="success">';
    echo '<h3>✅ Cache Re-enabled Successfully!</h3>';
    echo '<p>Your site caching is now active again for better performance.</p>';
    echo '</div>';
}

// Check if cache is currently disabled
$cache_disabled = get_transient('together_forever_disable_cache');
if ($cache_disabled) {
    echo '<div class="warning">';
    echo '<h3>⚠️ Cache is Currently DISABLED</h3>';
    echo '<p>Cache is disabled for 1 hour. Your site will be slower but changes appear immediately.</p>';
    echo '<form method="post" style="display: inline;">';
    echo '<input type="submit" name="enable_cache" value="✅ Re-enable Cache Now" class="btn btn-success">';
    echo '</form>';
    echo '</div>';
}

// Test links
echo '<div class="info">';
echo '<h3>🔍 Test Your Changes Now:</h3>';
echo '<p>';
echo '<a href="' . home_url('/') . '" target="_blank" class="btn">Open Front Page</a>';
echo '<a href="' . home_url('/?nocache=' . time()) . '" target="_blank" class="btn">Front Page (No Cache)</a>';
echo '</p>';
echo '<p><em>Changes should now appear immediately without needing to clear cache!</em></p>';
echo '</div>';

// Instructions
echo '<div class="info">';
echo '<h3>💡 How to Use:</h3>';
echo '<ol>';
echo '<li><strong>Disable cache</strong> using the button above</li>';
echo '<li><strong>Make your changes</strong> to front-page.php</li>';
echo '<li><strong>Upload to live server</strong></li>';
echo '<li><strong>View changes immediately</strong> - no cache clearing needed!</li>';
echo '<li><strong>Re-enable cache</strong> when done for better performance</li>';
echo '</ol>';
echo '</div>';

?>

</div>
</body>
</html>

