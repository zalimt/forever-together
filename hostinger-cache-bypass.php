<?php
/**
 * Hostinger Cache Bypass - Server-Level Cache Solution
 * 
 * Hostinger has aggressive server-level caching that bypasses WordPress.
 * This script creates cache-busting URLs and forces fresh content.
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
    <title>🚀 Hostinger Cache Bypass</title>
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
        .btn-primary {
            background: #007cba;
        }
        .btn-primary:hover {
            background: #005a87;
        }
        ul { line-height: 1.8; }
        code { 
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: "Courier New", monospace;
        }
        .url-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin: 10px 0;
            font-family: monospace;
            word-break: break-all;
        }
        .copy-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
        .copy-btn:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
<div class="container">

<h1>🚀 Hostinger Cache Bypass</h1>

<div class="warning">
    <strong>🎯 HOSTINGER ISSUE IDENTIFIED:</strong><br>
    Hostinger has aggressive server-level caching that bypasses WordPress entirely.
    This is why your changes work on local but not on live.
</div>

<?php
// Generate cache-busting URLs
$timestamp = time();
$random = rand(1000, 9999);
$cache_buster = $timestamp . '_' . $random;

// Your site URLs with cache busting
$base_url = home_url('/');
$cache_bust_urls = [
    'front_page' => $base_url . '?v=' . $cache_buster,
    'front_page_alt' => $base_url . '?nocache=' . $cache_buster,
    'front_page_alt2' => $base_url . '?cb=' . $cache_buster,
    'front_page_alt3' => $base_url . '?t=' . $timestamp,
    'front_page_alt4' => $base_url . '?r=' . $random,
];

echo '<h2>🔗 Cache-Busting URLs</h2>';
echo '<p>Use these URLs to bypass Hostinger\'s server-level cache:</p>';

foreach ($cache_bust_urls as $name => $url) {
    echo '<div class="url-box">';
    echo '<strong>' . ucfirst(str_replace('_', ' ', $name)) . ':</strong><br>';
    echo '<a href="' . $url . '" target="_blank">' . $url . '</a>';
    echo '<button class="copy-btn" onclick="copyToClipboard(\'' . $url . '\')">Copy</button>';
    echo '</div>';
}
?>

<div class="info">
    <h3>💡 How to Use These URLs:</h3>
    <ol>
        <li><strong>Click any of the URLs above</strong> to view your site with cache busting</li>
        <li><strong>Your changes should appear immediately</strong> on these URLs</li>
        <li><strong>Use these URLs for testing</strong> while making changes</li>
        <li><strong>Regular visitors</strong> will see cached version until cache expires</li>
    </ol>
</div>

<h2>🔧 Hostinger-Specific Solutions</h2>

<div class="success">
    <h3>✅ Solution 1: Cache-Busting URLs (Immediate)</h3>
    <p>Use the URLs above to bypass cache immediately. Your changes will be visible on these URLs.</p>
</div>

<div class="info">
    <h3>✅ Solution 2: Hostinger Control Panel</h3>
    <p>Log in to your Hostinger control panel and:</p>
    <ul>
        <li>Go to <strong>Advanced → Cache Manager</strong></li>
        <li>Click <strong>"Clear All Cache"</strong></li>
        <li>Or disable cache temporarily while making changes</li>
    </ul>
</div>

<div class="info">
    <h3>✅ Solution 3: .htaccess Cache Headers</h3>
    <p>Add these rules to your .htaccess file to disable caching:</p>
    <div class="url-box">
# Disable Hostinger Cache
&lt;IfModule mod_headers.c&gt;
    Header set Cache-Control "no-cache, no-store, must-revalidate"
    Header set Pragma "no-cache"
    Header set Expires "0"
&lt;/IfModule&gt;
    </div>
</div>

<div class="warning">
    <h3>⚠️ Solution 4: Contact Hostinger Support</h3>
    <p>If nothing else works, contact Hostinger support and ask them to:</p>
    <ul>
        <li>Clear your site's server-level cache</li>
        <li>Disable aggressive caching for your domain</li>
        <li>Whitelist your IP to bypass cache</li>
    </ul>
</div>

<h2>🎯 For Your Button Issue</h2>

<div class="success">
    <h3>To see "123Gift a Certificate" instead of "GIFT A CERTIFICATE":</h3>
    <ol>
        <li><strong>Upload your updated front-page.php</strong> to the server</li>
        <li><strong>Click any of the cache-busting URLs above</strong></li>
        <li><strong>Your button should show "123Gift a Certificate"</strong> on these URLs</li>
        <li><strong>Regular visitors</strong> will see the old version until Hostinger's cache expires</li>
    </ol>
</div>

<h2>🔍 Why This Happens</h2>

<div class="info">
    <h3>Local vs Live Environment:</h3>
    <ul>
        <li><strong>Local:</strong> No server-level caching, changes appear immediately</li>
        <li><strong>Hostinger Live:</strong> Aggressive server-level caching, bypasses WordPress</li>
        <li><strong>WordPress Cache Plugins:</strong> Only work after server-level cache</li>
        <li><strong>Server Cache:</strong> Serves static files before WordPress even loads</li>
    </ul>
</div>

<div class="warning">
    <h3>Hostinger Cache Layers:</h3>
    <ol>
        <li><strong>Server-Level Cache</strong> (Hostinger) - Most aggressive</li>
        <li><strong>CDN Cache</strong> (if enabled)</li>
        <li><strong>WordPress Cache Plugins</strong> (WP Fastest Cache, etc.)</li>
        <li><strong>Browser Cache</strong></li>
    </ol>
    <p>We need to bypass layer 1 (server-level) to see changes.</p>
</div>

<h2>📞 Next Steps</h2>

<div class="success">
    <h3>Immediate Action:</h3>
    <ol>
        <li><strong>Test the cache-busting URLs above</strong> - your changes should appear</li>
        <li><strong>Use these URLs for development</strong> while making changes</li>
        <li><strong>Contact Hostinger support</strong> to clear server-level cache</li>
        <li><strong>Consider adding .htaccess rules</strong> to disable caching</li>
    </ol>
</div>

<div class="info">
    <h3>Long-term Solution:</h3>
    <ul>
        <li>Ask Hostinger to disable aggressive caching for your domain</li>
        <li>Or switch to a hosting provider with less aggressive caching</li>
        <li>Or use cache-busting URLs for all development work</li>
    </ul>
</div>

</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('URL copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}

// Auto-refresh cache busting URLs every 30 seconds
setInterval(function() {
    location.reload();
}, 30000);
</script>

</body>
</html>

