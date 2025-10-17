<?php
/**
 * Admin Cache Clear - WordPress Admin Integration
 * 
 * This creates a simple admin page to clear cache
 * Access via: WordPress Admin → Appearance → Clear Cache
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add admin menu for cache clearing
 */
function together_forever_add_cache_admin_menu() {
    add_theme_page(
        'Clear Cache',
        'Clear Cache',
        'manage_options',
        'together-forever-cache',
        'together_forever_cache_admin_page'
    );
    
    // Add submenu under Tools
    add_management_page(
        'Disable Cache Temporarily',
        'Disable Cache',
        'manage_options',
        'together-forever-disable-cache',
        'together_forever_disable_cache_admin_page'
    );
}
add_action('admin_menu', 'together_forever_add_cache_admin_menu');

/**
 * Handle cache clearing action
 */
function together_forever_handle_cache_clear() {
    if (!current_user_can('manage_options')) {
        wp_die('Access denied');
    }
    
    // Handle disable cache action
    if (isset($_POST['disable_cache']) && wp_verify_nonce($_POST['cache_nonce'], 'clear_cache_action')) {
        // Deactivate WP Fastest Cache temporarily
        if (class_exists('WpFastestCache')) {
            deactivate_plugins('wp-fastest-cache/wpFastestCache.php');
        }
        
        // Set constants to disable caching
        if (!defined('DONOTCACHEPAGE')) {
            define('DONOTCACHEPAGE', true);
        }
        if (!defined('DONOTCACHEOBJECT')) {
            define('DONOTCACHEOBJECT', true);
        }
        if (!defined('DONOTCACHEDB')) {
            define('DONOTCACHEDB', true);
        }
        
        // Set transient to disable cache for 1 hour
        set_transient('together_forever_disable_cache', true, 3600);
        
        // Update .htaccess to disable cache
        $htaccess_file = ABSPATH . '.htaccess';
        if (file_exists($htaccess_file)) {
            $htaccess_content = file_get_contents($htaccess_file);
            
            // Add cache disabling rules if not already present
            if (strpos($htaccess_content, 'DISABLE CACHE - Added by cache fix') === false) {
                $cache_disable_rules = "\n# DISABLE CACHE - Added by cache fix\n";
                $cache_disable_rules .= "<IfModule mod_headers.c>\n";
                $cache_disable_rules .= "Header set Cache-Control \"no-cache, no-store, must-revalidate\"\n";
                $cache_disable_rules .= "Header set Pragma \"no-cache\"\n";
                $cache_disable_rules .= "Header set Expires \"0\"\n";
                $cache_disable_rules .= "</IfModule>\n";
                
                file_put_contents($htaccess_file, $htaccess_content . $cache_disable_rules);
            }
        }
        
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p><strong>🚫 Cache DISABLED for 1 hour!</strong> Your site will be slower but changes will appear immediately.</p></div>';
        });
    }
    
    // Handle re-enable cache action
    if (isset($_POST['enable_cache']) && wp_verify_nonce($_POST['cache_nonce'], 'clear_cache_action')) {
        // Remove transient
        delete_transient('together_forever_disable_cache');
        
        // Reactivate WP Fastest Cache
        if (file_exists(WP_PLUGIN_DIR . '/wp-fastest-cache/wpFastestCache.php')) {
            activate_plugin('wp-fastest-cache/wpFastestCache.php');
        }
        
        // Remove cache disabling rules from .htaccess
        $htaccess_file = ABSPATH . '.htaccess';
        if (file_exists($htaccess_file)) {
            $htaccess_content = file_get_contents($htaccess_file);
            $htaccess_content = preg_replace('/\n# DISABLE CACHE - Added by cache fix.*?<\/IfModule>\n/s', "\n", $htaccess_content);
            file_put_contents($htaccess_file, $htaccess_content);
        }
        
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p><strong>✅ Cache RE-ENABLED!</strong> Your site is fast again.</p></div>';
        });
    }
    
    if (isset($_POST['clear_cache']) && wp_verify_nonce($_POST['cache_nonce'], 'clear_cache_action')) {
        // Clear all caches
        wp_cache_flush();
        
        // Clear WP Fastest Cache
        if (class_exists('WpFastestCache')) {
            $wpfc = new WpFastestCache();
            if (method_exists($wpfc, 'deleteCache')) {
                $wpfc->deleteCache(true);
            }
        }
        
        // Clear LiteSpeed Cache
        if (defined('LSCWP_V')) {
            do_action('litespeed_purge_all');
        }
        
        // Clear other caches
        if (function_exists('w3tc_flush_all')) w3tc_flush_all();
        if (function_exists('wp_cache_clear_cache')) wp_cache_clear_cache();
        if (function_exists('rocket_clean_domain')) rocket_clean_domain();
        
        // Update theme version
        update_option('stylesheet_version', time());
        
        // Touch template files
        $theme_dir = get_stylesheet_directory();
        $files_to_touch = [
            $theme_dir . '/front-page.php',
            $theme_dir . '/header.php',
            $theme_dir . '/footer.php',
            $theme_dir . '/functions.php',
        ];
        
        foreach ($files_to_touch as $file) {
            if (file_exists($file)) {
                touch($file);
            }
        }
        
        // Set transient to prevent cache regeneration for 5 minutes
        set_transient('together_forever_cache_cleared', true, 300);
        
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p><strong>✅ Cache cleared successfully!</strong> Cache is disabled for 5 minutes. Your changes should now be visible.</p></div>';
        });
    }
}
add_action('admin_init', 'together_forever_handle_cache_clear');

/**
 * Admin page for cache clearing
 */
function together_forever_cache_admin_page() {
    ?>
    <div class="wrap">
        <h1>🚀 Clear Cache</h1>
        
        <div class="card">
            <h2>Clear All Caches</h2>
            <p>Use this when your front-page.php changes don't appear on the live site.</p>
            
            <form method="post" action="">
                <?php wp_nonce_field('clear_cache_action', 'cache_nonce'); ?>
                <p>
                    <input type="submit" name="clear_cache" class="button button-primary button-large" 
                           value="⚡ Clear All Caches" 
                           onclick="return confirm('Are you sure you want to clear all caches?');">
                </p>
            </form>
        </div>
        
        <div class="card">
            <h3>📋 What This Clears:</h3>
            <ul>
                <li>✅ WordPress object cache</li>
                <li>✅ WP Fastest Cache (static HTML files)</li>
                <li>✅ LiteSpeed Cache</li>
                <li>✅ W3 Total Cache</li>
                <li>✅ WP Super Cache</li>
                <li>✅ WP Rocket</li>
                <li>✅ Template file timestamps</li>
                <li>✅ Theme version</li>
            </ul>
        </div>
        
        <div class="card">
            <h3>⚠️ After Clearing Cache:</h3>
            <ol>
                <li><strong>Hard refresh your browser:</strong> <code>Ctrl+Shift+R</code> (Windows) or <code>Cmd+Shift+R</code> (Mac)</li>
                <li><strong>Or test in incognito window</strong> to bypass browser cache</li>
                <li><strong>Check your front page</strong> - changes should now be visible</li>
            </ol>
        </div>
        
        <div class="card">
            <h3>🔍 Current Status:</h3>
            <p><strong>Front Page File:</strong> 
                <?php
                $front_page = get_stylesheet_directory() . '/front-page.php';
                if (file_exists($front_page)) {
                    echo '✅ Exists (Modified: ' . date('Y-m-d H:i:s', filemtime($front_page)) . ')';
                } else {
                    echo '❌ Not found';
                }
                ?>
            </p>
            
            <p><strong>Active Cache Plugins:</strong></p>
            <ul>
                <li>WP Fastest Cache: <?php echo class_exists('WpFastestCache') ? '✅ Active' : '❌ Not active'; ?></li>
                <li>LiteSpeed Cache: <?php echo defined('LSCWP_V') ? '✅ Active' : '❌ Not active'; ?></li>
                <li>W3 Total Cache: <?php echo function_exists('w3tc_flush_all') ? '✅ Active' : '❌ Not active'; ?></li>
                <li>WP Super Cache: <?php echo function_exists('wp_cache_clear_cache') ? '✅ Active' : '❌ Not active'; ?></li>
                <li>WP Rocket: <?php echo function_exists('rocket_clean_domain') ? '✅ Active' : '❌ Not active'; ?></li>
            </ul>
        </div>
        
        <div class="card">
            <h3>🔗 Quick Links:</h3>
            <p>
                <a href="<?php echo home_url('/'); ?>" target="_blank" class="button">View Front Page</a>
                <a href="<?php echo home_url('/?nocache=' . time()); ?>" target="_blank" class="button">Front Page (No Cache)</a>
            </p>
        </div>
    </div>
    
    <style>
    .card {
        background: #fff;
        border: 1px solid #ccd0d4;
        border-radius: 4px;
        padding: 20px;
        margin: 20px 0;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
    }
    .card h2, .card h3 {
        margin-top: 0;
    }
    .button-large {
        font-size: 16px;
        height: auto;
        padding: 8px 16px;
    }
    </style>
    <?php
}

/**
 * Admin page for disabling cache temporarily
 */
function together_forever_disable_cache_admin_page() {
    $cache_disabled = get_transient('together_forever_disable_cache');
    ?>
    <div class="wrap">
        <h1>🚫 Disable Cache Temporarily</h1>
        
        <div class="card">
            <h2>Current Status</h2>
            <?php if ($cache_disabled): ?>
                <div style="background: #fff3cd; color: #856404; padding: 15px; border: 1px solid #ffeaa7; border-radius: 4px; margin: 10px 0;">
                    <strong>⚠️ Cache is currently DISABLED</strong><br>
                    Your site will be slower but changes will appear immediately.
                </div>
            <?php else: ?>
                <div style="background: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 4px; margin: 10px 0;">
                    <strong>✅ Cache is currently ENABLED</strong><br>
                    Your site is fast but changes may not appear immediately.
                </div>
            <?php endif; ?>
        </div>
        
        <div class="card">
            <h2>Disable Cache for 1 Hour</h2>
            <p>Use this when cache keeps regenerating and your changes disappear after hard refresh.</p>
            
            <?php if (!$cache_disabled): ?>
                <form method="post" action="">
                    <?php wp_nonce_field('clear_cache_action', 'cache_nonce'); ?>
                    <p>
                        <input type="submit" name="disable_cache" class="button button-primary button-large" 
                               value="🚫 Disable Cache for 1 Hour" 
                               onclick="return confirm('This will disable ALL caching for 1 hour. Your site will be slower but changes will appear immediately. Continue?');">
                    </p>
                </form>
            <?php else: ?>
                <form method="post" action="">
                    <?php wp_nonce_field('clear_cache_action', 'cache_nonce'); ?>
                    <p>
                        <input type="submit" name="enable_cache" class="button button-secondary button-large" 
                               value="✅ Re-enable Cache Now">
                    </p>
                </form>
            <?php endif; ?>
        </div>
        
        <div class="card">
            <h3>📋 What This Does:</h3>
            <ul>
                <li>✅ Deactivates WP Fastest Cache plugin temporarily</li>
                <li>✅ Sets DONOTCACHEPAGE constants to disable all caching</li>
                <li>✅ Updates .htaccess to add no-cache headers</li>
                <li>✅ Disables cache for 1 hour - plenty of time to make changes</li>
                <li>✅ Your site works normally but without caching</li>
            </ul>
        </div>
        
        <div class="card">
            <h3>⚠️ Important Notes:</h3>
            <ul>
                <li><strong>Changes appear immediately</strong> - no cache clearing needed</li>
                <li><strong>Changes stick</strong> - no regeneration possible</li>
                <li><strong>Site will be slower</strong> (no caching for 1 hour)</li>
                <li><strong>Perfect for making changes</strong></li>
                <li><strong>Re-enable when done</strong> for better performance</li>
            </ul>
        </div>
        
        <div class="card">
            <h3>🔍 Current Cache Status:</h3>
            <p><strong>WP Fastest Cache:</strong> 
                <?php echo class_exists('WpFastestCache') ? '✅ Active' : '❌ Not active'; ?>
            </p>
            <p><strong>LiteSpeed Cache:</strong> 
                <?php echo defined('LSCWP_V') ? '✅ Active' : '❌ Not active'; ?>
            </p>
            <p><strong>Cache Disabled Transient:</strong> 
                <?php echo $cache_disabled ? '✅ YES (Cache disabled)' : '❌ NO (Cache enabled)'; ?>
            </p>
        </div>
        
        <div class="card">
            <h3>🔗 Quick Links:</h3>
            <p>
                <a href="<?php echo home_url('/'); ?>" target="_blank" class="button">View Front Page</a>
                <a href="<?php echo home_url('/?nocache=' . time()); ?>" target="_blank" class="button">Front Page (No Cache)</a>
            </p>
            
            <?php
            // Generate cache-busting URLs
            $cache_bust_urls = together_forever_generate_cache_busting_urls();
            if ($cache_bust_urls) {
                echo '<h4>🚀 Cache-Busting URLs (Bypass Hostinger Cache):</h4>';
                echo '<p><strong>Use these URLs to bypass Hostinger\'s aggressive caching:</strong></p>';
                foreach ($cache_bust_urls as $name => $url) {
                    $display_name = ucfirst(str_replace('_', ' ', $name));
                    echo '<p><a href="' . $url . '" target="_blank" class="button" style="margin: 2px;">' . $display_name . '</a></p>';
                }
                echo '<p><em>These URLs add cache-busting parameters to bypass Hostinger\'s server-level cache.</em></p>';
            }
            ?>
        </div>
        
        <div class="card">
            <h3>💡 How to Use:</h3>
            <ol>
                <li><strong>Disable cache</strong> using the button above</li>
                <li><strong>Make your changes</strong> to front-page.php</li>
                <li><strong>Upload to live server</strong></li>
                <li><strong>View changes immediately</strong> - no cache clearing needed!</li>
                <li><strong>Re-enable cache</strong> when done for better performance</li>
            </ol>
        </div>
    </div>
    
    <style>
    .card {
        background: #fff;
        border: 1px solid #ccd0d4;
        border-radius: 4px;
        padding: 20px;
        margin: 20px 0;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
    }
    .card h2, .card h3 {
        margin-top: 0;
    }
    .button-large {
        font-size: 16px;
        height: auto;
        padding: 8px 16px;
    }
    </style>
    <?php
}
