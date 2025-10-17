<?php
/**
 * Test Cache Fix - Quick Diagnostic
 */

require_once('wp-load.php');

if (!current_user_can('administrator')) {
    wp_die('Access denied');
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cache Fix Test</title>
    <style>
        body { font-family: -apple-system, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        h1 { color: #2271b1; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 4px; margin: 10px 0; }
        code { background: #f8f9fa; padding: 2px 6px; border-radius: 3px; }
        ul { line-height: 1.8; }
        .timestamp { font-size: 20px; font-weight: bold; color: #2271b1; }
    </style>
</head>
<body>
<div class="container">

<h1>🔍 Cache Fix Diagnostic</h1>

<h2>1. Admin Cache Bypass</h2>
<?php if (is_user_logged_in() && current_user_can('administrator')): ?>
    <div class="success">
        ✅ You are logged in as admin<br>
        ✅ Cache should be DISABLED for you<br>
        ✅ You should see all changes immediately
    </div>
<?php else: ?>
    <div class="error">
        ❌ Not logged in as admin<br>
        Please log in to WordPress admin first
    </div>
<?php endif; ?>

<h2>2. Current Timestamp</h2>
<div class="info">
    <p class="timestamp"><?php echo date('Y-m-d H:i:s'); ?></p>
    <p>This timestamp changes every time you refresh. If it doesn't change, you have browser cache issues.</p>
</div>

<h2>3. Front Page File Status</h2>
<?php
$front_page = get_stylesheet_directory() . '/front-page.php';
if (file_exists($front_page)):
    $mtime = filemtime($front_page);
?>
    <div class="success">
        ✅ front-page.php exists<br>
        📅 Last modified: <strong><?php echo date('Y-m-d H:i:s', $mtime); ?></strong><br>
        🔢 Timestamp: <code><?php echo $mtime; ?></code>
    </div>
<?php else: ?>
    <div class="error">❌ front-page.php not found!</div>
<?php endif; ?>

<h2>4. Cache Constants</h2>
<div class="info">
    <ul>
        <li>DONOTCACHEPAGE: <?php echo defined('DONOTCACHEPAGE') && DONOTCACHEPAGE ? '✅ TRUE' : '❌ FALSE'; ?></li>
        <li>DONOTCACHEOBJECT: <?php echo defined('DONOTCACHEOBJECT') && DONOTCACHEOBJECT ? '✅ TRUE' : '❌ FALSE'; ?></li>
        <li>DONOTCACHEDB: <?php echo defined('DONOTCACHEDB') && DONOTCACHEDB ? '✅ TRUE' : '❌ FALSE'; ?></li>
    </ul>
    <?php if (is_user_logged_in() && current_user_can('administrator')): ?>
        <p><strong>These should all be TRUE for admins (you).</strong></p>
    <?php endif; ?>
</div>

<h2>5. Active Cache Plugins</h2>
<div class="info">
    <ul>
        <li>WP Fastest Cache: <?php echo class_exists('WpFastestCache') ? '✅ ACTIVE' : '❌ Not active'; ?></li>
        <li>LiteSpeed Cache: <?php echo defined('LSCWP_V') ? '✅ ACTIVE' : '❌ Not active'; ?></li>
        <li>W3 Total Cache: <?php echo function_exists('w3tc_flush_all') ? '✅ ACTIVE' : '❌ Not active'; ?></li>
        <li>WP Super Cache: <?php echo function_exists('wp_cache_clear_cache') ? '✅ ACTIVE' : '❌ Not active'; ?></li>
        <li>WP Rocket: <?php echo function_exists('rocket_clean_domain') ? '✅ ACTIVE' : '❌ Not active'; ?></li>
    </ul>
</div>

<h2>6. Cache Directories</h2>
<?php
$cache_dirs = [
    WP_CONTENT_DIR . '/cache/all/',
    WP_CONTENT_DIR . '/cache/wpfc-minified/',
    WP_CONTENT_DIR . '/litespeed/cache/',
];

foreach ($cache_dirs as $dir):
    if (is_dir($dir)):
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        $count = iterator_count($files);
?>
        <div class="<?php echo $count > 0 ? 'info' : 'success'; ?>">
            📁 <?php echo str_replace(WP_CONTENT_DIR, '', $dir); ?><br>
            Files: <strong><?php echo $count; ?></strong>
            <?php if ($count > 0): ?>
                <br><em>(These files may cause caching issues)</em>
            <?php endif; ?>
        </div>
<?php
    endif;
endforeach;
?>

<h2>7. What To Do</h2>

<?php if (is_user_logged_in() && current_user_can('administrator')): ?>
    <div class="success">
        <h3>✅ Good News!</h3>
        <p>As an admin, cache is DISABLED for you. You should see changes immediately!</p>
        <p><strong>What to do:</strong></p>
        <ol>
            <li>Make your changes to <code>front-page.php</code></li>
            <li><strong>Hard refresh browser:</strong> <code>Ctrl+Shift+R</code> (Win) or <code>Cmd+Shift+R</code> (Mac)</li>
            <li>If still not working, run: <a href="<?php echo home_url('/nuclear-cache-clear.php'); ?>" target="_blank">Nuclear Cache Clear</a></li>
        </ol>
    </div>
<?php else: ?>
    <div class="error">
        <p>You need to log in to WordPress admin first!</p>
        <p><a href="<?php echo admin_url(); ?>">Go to WordPress Admin</a></p>
    </div>
<?php endif; ?>

<h2>8. Quick Actions</h2>
<p>
    <a href="<?php echo home_url('/'); ?>" target="_blank" style="display:inline-block; background:#2271b1; color:white; padding:10px 20px; text-decoration:none; border-radius:4px; margin:5px;">View Front Page</a>
    <a href="<?php echo home_url('/nuclear-cache-clear.php'); ?>" target="_blank" style="display:inline-block; background:#dc3545; color:white; padding:10px 20px; text-decoration:none; border-radius:4px; margin:5px;">Nuclear Clear Cache</a>
    <a href="<?php echo home_url('/clear-cache-now.php'); ?>" target="_blank" style="display:inline-block; background:#28a745; color:white; padding:10px 20px; text-decoration:none; border-radius:4px; margin:5px;">Quick Clear Cache</a>
</p>

<div class="info">
    <h3>💡 Pro Tip</h3>
    <p>Always test in <strong>INCOGNITO/PRIVATE</strong> window to completely bypass browser cache!</p>
    <p><strong>Or use this direct link:</strong><br>
    <a href="<?php echo home_url('/?nocache=' . time()); ?>" target="_blank"><?php echo home_url('/?nocache=' . time()); ?></a></p>
</div>

</div>
</body>
</html>


