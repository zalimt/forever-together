<?php
/**
 * Together Forever Child Theme Functions
 * 
 * This file enqueues the parent theme styles and allows you to add
 * custom functionality to your child theme based on Astra.
 * 
 * @package Together_Forever
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue parent theme styles
 * 
 * This function properly loads the parent theme's stylesheet
 * before the child theme's stylesheet.
 */
function together_forever_enqueue_styles() {
    // Get the parent theme's version for cache busting
    $parent_style = 'astra-theme-css';
    $parent_version = wp_get_theme('astra')->get('Version');
    
    // Enqueue parent theme stylesheet
    wp_enqueue_style(
        $parent_style,
        get_template_directory_uri() . '/style.css',
        array(),
        $parent_version
    );
    
    // Enqueue compiled SCSS stylesheets
    $theme_version = wp_get_theme()->get('Version');
    
    // Enqueue root styles (CSS variables and base styles)
    wp_enqueue_style(
        'together-forever-root',
        get_stylesheet_directory_uri() . '/css/root.css',
        array($parent_style),
        $theme_version
    );
    
    // Enqueue main styles (component styles)
    wp_enqueue_style(
        'together-forever-main',
        get_stylesheet_directory_uri() . '/css/main.css',
        array('together-forever-root'),
        $theme_version
    );
    
    // Enqueue about page styles if on about page
    if (is_page_template('about.php')) {
        wp_enqueue_style(
            'together-forever-about',
            get_stylesheet_directory_uri() . '/css/about.css',
            array('together-forever-main'),
            $theme_version
        );
    }
    
    // Enqueue single kids styles if on single kids post
    if (is_singular('kids')) {
        wp_enqueue_style(
            'together-forever-single-kids',
            get_stylesheet_directory_uri() . '/css/single-kids.css',
            array('together-forever-main'),
            $theme_version
        );
    }
    
    // Enqueue certificate page styles if on certificate page
    if (is_page_template('certificate.php') || is_page('certificate')) {
        wp_enqueue_style(
            'together-forever-certificate',
            get_stylesheet_directory_uri() . '/css/certificate.css',
            array('together-forever-main'),
            $theme_version
        );
    }
    
    // Enqueue activate certificate page styles if on activate certificate page
    if (is_page_template('activate-certificate.php') || is_page('activate-certificate')) {
        wp_enqueue_style(
            'together-forever-activate-certificate',
            get_stylesheet_directory_uri() . '/css/activate-certificate.css',
            array('together-forever-main'),
            $theme_version
        );
    }
    
    // Enqueue Font Awesome for social media icons
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        array(),
        '6.5.1'
    );
    
    // Fallback: Enqueue original child theme stylesheet if compiled CSS doesn't exist
    if (!file_exists(get_stylesheet_directory() . '/css/root.css') || !file_exists(get_stylesheet_directory() . '/css/main.css')) {
        wp_enqueue_style(
            'together-forever-fallback',
            get_stylesheet_directory_uri() . '/style.css',
            array($parent_style),
            $theme_version
        );
    }
}
add_action('wp_enqueue_scripts', 'together_forever_enqueue_styles');

/**
 * Register navigation menus
 */
function together_forever_register_menus() {
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'together-forever'),
        'footer_menu_1' => __('Footer Menu 1 - About Us', 'together-forever'),
        'footer_menu_2' => __('Footer Menu 2 - In Need of Help', 'together-forever'),
        'footer_menu_3' => __('Footer Menu 3 - News', 'together-forever'),
        'footer_menu_4' => __('Footer Menu 4 - Make a Donation', 'together-forever'),
    ));
}
add_action('init', 'together_forever_register_menus');

/**
 * Enqueue child theme scripts
 * 
 * Add custom JavaScript files here if needed
 */
function together_forever_enqueue_scripts() {
    // Example: Enqueue a custom JavaScript file
    // wp_enqueue_script(
    //     'together-forever-script',
    //     get_stylesheet_directory_uri() . '/js/custom.js',
    //     array('jquery'),
    //     wp_get_theme()->get('Version'),
    //     true
    // );
}
add_action('wp_enqueue_scripts', 'together_forever_enqueue_scripts');

/**
 * Add custom theme support
 * 
 * Add any custom theme features here
 */
function together_forever_theme_support() {
    // Add custom theme support features here
    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Add support for wide and full width blocks
    add_theme_support('align-wide');
    
    // Add support for editor styles
    add_theme_support('editor-styles');
    
    // Add support for responsive embeds
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'together_forever_theme_support');

/**
 * Customize the excerpt length
 * 
 * Uncomment and modify as needed
 */
// function together_forever_excerpt_length($length) {
//     return 30; // Change this number to your desired excerpt length
// }
// add_filter('excerpt_length', 'together_forever_excerpt_length');

/**
 * Customize the excerpt more text
 * 
 * Uncomment and modify as needed
 */
// function together_forever_excerpt_more($more) {
//     return '...'; // Change this to your desired "read more" text
// }
// add_filter('excerpt_more', 'together_forever_excerpt_more');

/**
 * Add custom body classes
 * 
 * This allows you to add custom CSS classes to the body element
 */
function together_forever_body_classes($classes) {
    // Add custom body classes here
    // Example: $classes[] = 'custom-class';
    
    return $classes;
}
add_filter('body_class', 'together_forever_body_classes');

/**
 * Customize Astra theme options
 * 
 * This function allows you to override Astra theme options
 */
function together_forever_astra_options($defaults) {
    // Override Astra default options here
    // Example: $defaults['blog-layout'] = 'blog-layout-1';
    
    return $defaults;
}
add_filter('astra_theme_defaults', 'together_forever_astra_options');

/**
 * Add custom CSS to Astra's dynamic CSS
 * 
 * This function adds custom CSS to Astra's dynamic CSS output
 */
function together_forever_dynamic_css($dynamic_css) {
    // Add custom CSS here that will be included in Astra's dynamic CSS
    $custom_css = "
        /* Custom Together Forever styles */
        .site-header .main-header-bar {
            /* Add custom header styles here */
        }
        
        .main-navigation ul li a {
            /* Add custom navigation styles here */
        }
    ";
    
    return $dynamic_css . $custom_css;
}
add_filter('astra_dynamic_css', 'together_forever_dynamic_css');

/**
 * Customize the login page
 * 
 * Uncomment and modify as needed
 */
// function together_forever_login_logo() {
//     echo '<style type="text/css">
//         .login h1 a {
//             background-image: url(' . get_stylesheet_directory_uri() . '/images/custom-logo.png) !important;
//             background-size: contain !important;
//             width: 200px !important;
//             height: 100px !important;
//         }
//     </style>';
// }
// add_action('login_head', 'together_forever_login_logo');

/**
 * Add custom admin styles
 * 
 * Uncomment and modify as needed
 */
// function together_forever_admin_styles() {
//     wp_enqueue_style(
//         'together-forever-admin',
//         get_stylesheet_directory_uri() . '/css/admin.css',
//         array(),
//         wp_get_theme()->get('Version')
//     );
// }
// add_action('admin_enqueue_scripts', 'together_forever_admin_styles');

/**
 * Customize the WordPress admin footer
 * 
 * Uncomment and modify as needed
 */
// function together_forever_admin_footer() {
//     echo 'Customized by Together Forever Theme';
// }
// add_filter('admin_footer_text', 'together_forever_admin_footer');

/**
 * Add custom post types support
 * 
 * Uncomment and modify as needed
 */
// function together_forever_add_post_type_support() {
//     // Add support for custom post types
//     add_post_type_support('page', 'excerpt');
// }
// add_action('init', 'together_forever_add_post_type_support');

/**
 * Customize Astra's color palette
 * 
 * This function allows you to customize Astra's color palette
 */
function together_forever_astra_colors($colors) {
    // Add custom colors to Astra's palette
    // Example: $colors['custom-color'] = '#ff0000';
    
    return $colors;
}
add_filter('astra_color_palettes', 'together_forever_astra_colors');

/**
 * Add custom fonts
 * 
 * This function allows you to add custom fonts to your theme
 */
function together_forever_custom_fonts() {
    // Add custom font imports here
    // Example: wp_enqueue_style('custom-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
}
add_action('wp_enqueue_scripts', 'together_forever_custom_fonts');

/**
 * Customize Astra's typography
 * 
 * This function allows you to customize Astra's typography settings
 */
function together_forever_astra_typography($typography) {
    // Customize typography settings here
    // Example: $typography['body-font-family'] = 'Inter, sans-serif';
    
    return $typography;
}
add_filter('astra_typography_defaults', 'together_forever_astra_typography');

/**
 * Add custom widget areas
 * 
 * This function allows you to add custom widget areas
 */
function together_forever_widgets_init() {
    // Register custom widget areas here
    // Example:
    // register_sidebar(array(
    //     'name'          => __('Custom Sidebar', 'together-forever'),
    //     'id'            => 'custom-sidebar',
    //     'description'   => __('Add widgets here.', 'together-forever'),
    //     'before_widget' => '<section id="%1$s" class="widget %2$s">',
    //     'after_widget'  => '</section>',
    //     'before_title'  => '<h2 class="widget-title">',
    //     'after_title'   => '</h2>',
    // ));
}
add_action('widgets_init', 'together_forever_widgets_init');

/**
 * ACF JSON Configuration
 * 
 * This function configures ACF Pro to save and load field groups from JSON files
 */
function together_forever_acf_json_save_point($path) {
    // Update path to point to your theme's acf-json folder
    $path = get_stylesheet_directory() . '/acf-json';
    return $path;
}
add_filter('acf/settings/save_json', 'together_forever_acf_json_save_point');

function together_forever_acf_json_load_point($paths) {
    // Remove the original path
    unset($paths[0]);
    // Add the new path
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
}
add_filter('acf/settings/load_json', 'together_forever_acf_json_load_point');

/**
 * Enable SVG Upload Support
 * 
 * This function allows SVG files to be uploaded to WordPress media library
 */
function together_forever_enable_svg_upload($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'together_forever_enable_svg_upload');

/**
 * Fix SVG display in media library
 * 
 * This function ensures SVG files display properly in the WordPress admin
 */
function together_forever_fix_svg_display($response, $attachment, $meta) {
    if ($response['type'] === 'image' && $response['subtype'] === 'svg+xml') {
        $response['image'] = array(
            'src' => $response['url'],
            'width' => 150,
            'height' => 150
        );
        $response['thumb'] = array(
            'src' => $response['url'],
            'width' => 150,
            'height' => 150
        );
    }
    return $response;
}
add_filter('wp_prepare_attachment_for_js', 'together_forever_fix_svg_display', 10, 3);

/**
 * Add SVG support to media library
 * 
 * This function adds proper MIME type support for SVG files
 */
function together_forever_add_svg_support() {
    // Add SVG support to allowed file types
    add_filter('wp_check_filetype_and_ext', 'together_forever_fix_svg_upload', 10, 4);
}
add_action('init', 'together_forever_add_svg_support');

/**
 * Fix SVG file type detection
 * 
 * This function ensures WordPress properly recognizes SVG files
 */
function together_forever_fix_svg_upload($data, $file, $filename, $mimes) {
    $filetype = wp_check_filetype($filename, $mimes);
    return array(
        'ext' => $filetype['ext'],
        'type' => $filetype['type'],
        'proper_filename' => $data['proper_filename']
    );
}

/**
 * Sanitize SVG uploads for security
 * 
 * This function sanitizes SVG content to prevent XSS attacks
 */
function together_forever_sanitize_svg_upload($file) {
    if ($file['type'] === 'image/svg+xml') {
        $svg_content = file_get_contents($file['tmp_name']);
        
        // Remove potentially dangerous elements and attributes
        $dangerous_elements = array('script', 'object', 'embed', 'link', 'foreignobject');
        $dangerous_attributes = array('onload', 'onerror', 'onclick', 'onmouseover', 'href', 'xlink:href');
        
        // Remove dangerous elements
        foreach ($dangerous_elements as $element) {
            $svg_content = preg_replace('/<' . $element . '[^>]*>.*?<\/' . $element . '>/is', '', $svg_content);
            $svg_content = preg_replace('/<' . $element . '[^>]*\/>/is', '', $svg_content);
        }
        
        // Remove dangerous attributes
        foreach ($dangerous_attributes as $attr) {
            $svg_content = preg_replace('/\s*' . $attr . '\s*=\s*["\'][^"\']*["\']/i', '', $svg_content);
        }
        
        // Write sanitized content back to temp file
        file_put_contents($file['tmp_name'], $svg_content);
    }
    
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'together_forever_sanitize_svg_upload');

/**
 * Add SVG preview in media library
 * 
 * This function adds a preview for SVG files in the media library
 */
function together_forever_svg_media_thumbnails($response, $attachment, $meta) {
    if ($response['type'] === 'image' && $response['subtype'] === 'svg+xml') {
        $response['image'] = array(
            'src' => $response['url'],
            'width' => 150,
            'height' => 150
        );
        $response['thumb'] = array(
            'src' => $response['url'],
            'width' => 150,
            'height' => 150
        );
        $response['sizes'] = array(
            'full' => array(
                'url' => $response['url'],
                'width' => 150,
                'height' => 150,
                'orientation' => 'landscape'
            )
        );
    }
    return $response;
}
add_filter('wp_prepare_attachment_for_js', 'together_forever_svg_media_thumbnails', 10, 3);

/**
 * Register Custom Post Type: Kids
 * 
 * This function registers a custom post type for managing kids' profiles
 */
function together_forever_register_kids_post_type() {
    $labels = array(
        'name'                  => _x('Kids', 'Post Type General Name', 'together-forever'),
        'singular_name'         => _x('Kid', 'Post Type Singular Name', 'together-forever'),
        'menu_name'             => __('Kids', 'together-forever'),
        'name_admin_bar'        => __('Kid', 'together-forever'),
        'archives'              => __('Kid Archives', 'together-forever'),
        'attributes'            => __('Kid Attributes', 'together-forever'),
        'parent_item_colon'     => __('Parent Kid:', 'together-forever'),
        'all_items'             => __('All Kids', 'together-forever'),
        'add_new_item'          => __('Add New Kid', 'together-forever'),
        'add_new'               => __('Add New', 'together-forever'),
        'new_item'              => __('New Kid', 'together-forever'),
        'edit_item'             => __('Edit Kid', 'together-forever'),
        'update_item'           => __('Update Kid', 'together-forever'),
        'view_item'             => __('View Kid', 'together-forever'),
        'view_items'            => __('View Kids', 'together-forever'),
        'search_items'          => __('Search Kid', 'together-forever'),
        'not_found'             => __('Not found', 'together-forever'),
        'not_found_in_trash'    => __('Not found in Trash', 'together-forever'),
        'featured_image'        => __('Kid Photo', 'together-forever'),
        'set_featured_image'    => __('Set kid photo', 'together-forever'),
        'remove_featured_image' => __('Remove kid photo', 'together-forever'),
        'use_featured_image'    => __('Use as kid photo', 'together-forever'),
        'insert_into_item'      => __('Insert into kid', 'together-forever'),
        'uploaded_to_this_item' => __('Uploaded to this kid', 'together-forever'),
        'items_list'            => __('Kids list', 'together-forever'),
        'items_list_navigation' => __('Kids list navigation', 'together-forever'),
        'filter_items_list'     => __('Filter kids list', 'together-forever'),
    );
    
    $args = array(
        'label'                 => __('Kids', 'together-forever'),
        'description'           => __('Kids profiles and information', 'together-forever'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'taxonomies'            => array('kid_category', 'kid_tag'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-groups',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rest_base'             => 'kids',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'rewrite'               => array(
            'slug'                  => 'kids',
            'with_front'            => false,
            'pages'                 => true,
            'feeds'                 => true,
        ),
    );
    
    register_post_type('kids', $args);
}
add_action('init', 'together_forever_register_kids_post_type', 0);

/**
 * Register Custom Taxonomy: Kid Categories
 * 
 * This function registers a custom taxonomy for categorizing kids
 * This is separate from regular post categories
 */
function together_forever_register_kid_categories() {
    $labels = array(
        'name'                       => _x('Kid Categories', 'Taxonomy General Name', 'together-forever'),
        'singular_name'              => _x('Kid Category', 'Taxonomy Singular Name', 'together-forever'),
        'menu_name'                  => __('Categories', 'together-forever'),
        'all_items'                  => __('All Categories', 'together-forever'),
        'parent_item'                => __('Parent Category', 'together-forever'),
        'parent_item_colon'          => __('Parent Category:', 'together-forever'),
        'new_item_name'              => __('New Category Name', 'together-forever'),
        'add_new_item'               => __('Add New Category', 'together-forever'),
        'edit_item'                  => __('Edit Category', 'together-forever'),
        'update_item'                => __('Update Category', 'together-forever'),
        'view_item'                  => __('View Category', 'together-forever'),
        'separate_items_with_commas' => __('Separate categories with commas', 'together-forever'),
        'add_or_remove_items'        => __('Add or remove categories', 'together-forever'),
        'choose_from_most_used'      => __('Choose from the most used', 'together-forever'),
        'popular_items'              => __('Popular Categories', 'together-forever'),
        'search_items'               => __('Search Categories', 'together-forever'),
        'not_found'                  => __('Not Found', 'together-forever'),
        'no_terms'                   => __('No categories', 'together-forever'),
        'items_list'                 => __('Categories list', 'together-forever'),
        'items_list_navigation'      => __('Categories list navigation', 'together-forever'),
    );
    
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rest_base'                  => 'kid-categories',
        'rest_controller_class'      => 'WP_REST_Terms_Controller',
        'rewrite'                    => array(
            'slug'                       => 'kids/category',
            'with_front'                 => false,
            'hierarchical'               => true,
        ),
    );
    
    register_taxonomy('kid_category', array('kids'), $args);
}
add_action('init', 'together_forever_register_kid_categories', 0);

/**
 * Register Custom Taxonomy: Kid Tags
 * 
 * This function registers a custom taxonomy for tagging kids
 * This is separate from regular post tags
 */
function together_forever_register_kid_tags() {
    $labels = array(
        'name'                       => _x('Kid Tags', 'Taxonomy General Name', 'together-forever'),
        'singular_name'              => _x('Kid Tag', 'Taxonomy Singular Name', 'together-forever'),
        'menu_name'                  => __('Tags', 'together-forever'),
        'all_items'                  => __('All Tags', 'together-forever'),
        'parent_item'                => __('Parent Tag', 'together-forever'),
        'parent_item_colon'          => __('Parent Tag:', 'together-forever'),
        'new_item_name'              => __('New Tag Name', 'together-forever'),
        'add_new_item'               => __('Add New Tag', 'together-forever'),
        'edit_item'                  => __('Edit Tag', 'together-forever'),
        'update_item'                => __('Update Tag', 'together-forever'),
        'view_item'                  => __('View Tag', 'together-forever'),
        'separate_items_with_commas' => __('Separate tags with commas', 'together-forever'),
        'add_or_remove_items'        => __('Add or remove tags', 'together-forever'),
        'choose_from_most_used'      => __('Choose from the most used', 'together-forever'),
        'popular_items'              => __('Popular Tags', 'together-forever'),
        'search_items'               => __('Search Tags', 'together-forever'),
        'not_found'                  => __('Not Found', 'together-forever'),
        'no_terms'                   => __('No tags', 'together-forever'),
        'items_list'                 => __('Tags list', 'together-forever'),
        'items_list_navigation'      => __('Tags list navigation', 'together-forever'),
    );
    
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rest_base'                  => 'kid-tags',
        'rest_controller_class'      => 'WP_REST_Terms_Controller',
        'rewrite'                    => array(
            'slug'                       => 'kids/tag',
            'with_front'                 => false,
        ),
    );
    
    register_taxonomy('kid_tag', array('kids'), $args);
}
add_action('init', 'together_forever_register_kid_tags', 0);

/**
 * Flush rewrite rules on theme activation
 * 
 * This ensures that the custom post type permalinks work correctly
 */
function together_forever_flush_rewrite_rules() {
    together_forever_register_kids_post_type();
    together_forever_register_kid_categories();
    together_forever_register_kid_tags();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'together_forever_flush_rewrite_rules');

/**
 * Flush rewrite rules when needed
 * 
 * This function can be called to refresh permalinks
 */
function together_forever_force_flush_rewrite_rules() {
    // Check if we need to flush rewrite rules
    $version = get_option('together_forever_rewrite_rules_version', '1.0');
    $current_version = '1.1'; // Increment this when you need to flush rules
    
    if (version_compare($version, $current_version, '<')) {
        flush_rewrite_rules();
        update_option('together_forever_rewrite_rules_version', $current_version);
    }
}
add_action('init', 'together_forever_force_flush_rewrite_rules', 99);

/**
 * Include Certificate System
 * 
 * This includes the certificate payment and redemption system
 */
require_once get_stylesheet_directory() . '/inc/certificate-system.php';

/**
 * Include Stripe Integration
 * 
 * This includes Stripe payment integration for certificates
 */
require_once get_stylesheet_directory() . '/inc/stripe-integration.php';

/**
 * Include Admin Cache Clear
 * 
 * This includes the admin cache clearing functionality
 */
require_once get_stylesheet_directory() . '/admin-cache-clear.php';

/**
 * Auto Cache Busting for CSS Files
 * 
 * This automatically adds timestamps to CSS files to prevent caching issues
 */
function together_forever_add_cache_busting_to_styles($src, $handle) {
    // Only apply to our theme's CSS files
    if (strpos($src, get_stylesheet_directory_uri()) !== false && strpos($src, '.css') !== false) {
        $file_path = str_replace(get_stylesheet_directory_uri(), get_stylesheet_directory(), $src);
        if (file_exists($file_path)) {
            $timestamp = filemtime($file_path);
            $src = add_query_arg('v', $timestamp, $src);
        }
    }
    return $src;
}
add_filter('style_loader_src', 'together_forever_add_cache_busting_to_styles', 10, 2);

/**
 * Disable Page Caching for Admins
 * 
 * This ensures admins always see fresh content without cache
 */
function together_forever_disable_cache_for_admin() {
    if (is_user_logged_in() && current_user_can('administrator')) {
        // Set headers to prevent caching
        header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Define constant to disable cache plugins
        if (!defined('DONOTCACHEPAGE')) {
            define('DONOTCACHEPAGE', true);
        }
        if (!defined('DONOTCACHEOBJECT')) {
            define('DONOTCACHEOBJECT', true);
        }
        if (!defined('DONOTCACHEDB')) {
            define('DONOTCACHEDB', true);
        }
        
        // Disable WP Fastest Cache for admins
        if (class_exists('WpFastestCache')) {
            add_filter('wpfc_is_cacheable', '__return_false');
        }
        
        // Disable LiteSpeed Cache for admins
        if (defined('LSCWP_V')) {
            add_filter('litespeed_control_set_nocache', '__return_true');
        }
    }
}
add_action('init', 'together_forever_disable_cache_for_admin', 1);

/**
 * Force disable cache for all users temporarily
 * 
 * This prevents cache regeneration after clearing
 */
function together_forever_force_disable_cache() {
    // Check if we're in cache clearing mode
    $cache_cleared = get_transient('together_forever_cache_cleared');
    
    if ($cache_cleared) {
        // Disable ALL caching for 5 minutes after cache clear
        if (!defined('DONOTCACHEPAGE')) {
            define('DONOTCACHEPAGE', true);
        }
        if (!defined('DONOTCACHEOBJECT')) {
            define('DONOTCACHEOBJECT', true);
        }
        if (!defined('DONOTCACHEDB')) {
            define('DONOTCACHEDB', true);
        }
        
        // Disable WP Fastest Cache
        if (class_exists('WpFastestCache')) {
            add_filter('wpfc_is_cacheable', '__return_false');
        }
        
        // Disable LiteSpeed Cache
        if (defined('LSCWP_V')) {
            add_filter('litespeed_control_set_nocache', '__return_true');
        }
        
        // Set no-cache headers
        header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
    }
}
add_action('init', 'together_forever_force_disable_cache', 1);

/**
 * Hostinger Cache Bypass - Add cache-busting to all URLs
 * 
 * This adds cache-busting parameters to bypass Hostinger's aggressive caching
 */
function together_forever_add_cache_busting_to_urls() {
    // Only add cache busting if we're not in admin and cache is disabled
    if (!is_admin() && (get_transient('together_forever_cache_cleared') || get_transient('together_forever_disable_cache'))) {
        // Add cache-busting parameter to all internal links
        add_filter('the_content', 'together_forever_add_cache_busting_to_content');
        add_filter('wp_get_attachment_url', 'together_forever_add_cache_busting_to_media');
    }
}
add_action('init', 'together_forever_add_cache_busting_to_urls');

/**
 * Add cache-busting parameter to content links
 */
function together_forever_add_cache_busting_to_content($content) {
    $cache_buster = '?v=' . time();
    $home_url = home_url('/');
    
    // Add cache busting to internal links
    $content = preg_replace(
        '/(<a[^>]+href=["\'])(' . preg_quote($home_url, '/') . ')([^"\']*)(["\'][^>]*>)/i',
        '$1$2$3' . $cache_buster . '$4',
        $content
    );
    
    return $content;
}

/**
 * Add cache-busting parameter to media URLs
 */
function together_forever_add_cache_busting_to_media($url) {
    if (get_transient('together_forever_cache_cleared') || get_transient('together_forever_disable_cache')) {
        $cache_buster = '?v=' . time();
        $url = add_query_arg('v', time(), $url);
    }
    return $url;
}

/**
 * Generate cache-busting URLs for admin
 */
function together_forever_generate_cache_busting_urls() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $timestamp = time();
    $base_url = home_url('/');
    
    $cache_bust_urls = [
        'front_page' => $base_url . '?v=' . $timestamp,
        'front_page_alt1' => $base_url . '?nocache=' . $timestamp,
        'front_page_alt2' => $base_url . '?cb=' . $timestamp,
        'front_page_alt3' => $base_url . '?t=' . $timestamp,
        'front_page_alt4' => $base_url . '?r=' . rand(1000, 9999),
    ];
    
    return $cache_bust_urls;
}

/**
 * Exclude Admin from WP Fastest Cache
 */
function together_forever_wpfc_exclude_admin($is_cacheable) {
    if (is_user_logged_in() && current_user_can('administrator')) {
        return false;
    }
    return $is_cacheable;
}
add_filter('wpfc_is_cacheable', 'together_forever_wpfc_exclude_admin');

/**
 * Force Version on Front Page Template
 * 
 * Adds a version parameter to force refresh
 */
function together_forever_force_front_page_refresh() {
    if (is_front_page()) {
        $front_page_file = get_stylesheet_directory() . '/front-page.php';
        if (file_exists($front_page_file)) {
            $version = filemtime($front_page_file);
            // Output HTML comment with version for debugging
            echo "<!-- Front Page Version: {$version} -->\n";
        }
    }
}
add_action('wp_head', 'together_forever_force_front_page_refresh', 999);

/**
 * Add cache clear button to admin bar
 */
function together_forever_add_cache_clear_to_admin_bar($wp_admin_bar) {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $wp_admin_bar->add_node(array(
        'id'    => 'clear-cache',
        'title' => '⚡ Clear Cache',
        'href'  => admin_url('themes.php?page=together-forever-cache'),
        'meta'  => array(
            'title' => 'Clear all caches (WP Fastest Cache, LiteSpeed, etc.)'
        )
    ));
}
add_action('admin_bar_menu', 'together_forever_add_cache_clear_to_admin_bar', 100);

/**
 * Quick cache clear via AJAX
 */
function together_forever_quick_cache_clear() {
    if (!current_user_can('manage_options') || !wp_verify_nonce($_POST['nonce'], 'quick_cache_clear')) {
        wp_die('Access denied');
    }
    
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
    
    wp_send_json_success('Cache cleared successfully! Cache disabled for 5 minutes.');
}
add_action('wp_ajax_quick_cache_clear', 'together_forever_quick_cache_clear');

/**
 * Add quick cache clear script to admin
 */
function together_forever_admin_cache_script() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Add quick clear button to admin bar
        if ($('#wp-admin-bar-clear-cache').length) {
            $('#wp-admin-bar-clear-cache').append('<span class="quick-clear" style="margin-left: 10px; color: #ff6b6b; cursor: pointer;" title="Quick Clear">⚡</span>');
            
            $('.quick-clear').click(function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (confirm('Clear all caches now?')) {
                    $(this).text('⏳');
                    
                    $.post(ajaxurl, {
                        action: 'quick_cache_clear',
                        nonce: '<?php echo wp_create_nonce('quick_cache_clear'); ?>'
                    }, function(response) {
                        if (response.success) {
                            alert('✅ Cache cleared! Now hard refresh your browser (Ctrl+Shift+R)');
                            $('.quick-clear').text('✅');
                            setTimeout(function() {
                                $('.quick-clear').text('⚡');
                            }, 3000);
                        } else {
                            alert('❌ Error clearing cache');
                            $('.quick-clear').text('⚡');
                        }
                    });
                }
            });
        }
    });
    </script>
    <?php
}
add_action('admin_footer', 'together_forever_admin_cache_script');
add_action('wp_footer', 'together_forever_admin_cache_script');

/**
 * Force Theme Refresh
 * 
 * This function can be called to force WordPress to reload all theme files
 */
function together_forever_force_theme_refresh() {
    // Clear all caches
    wp_cache_flush();
    
    // Update theme version to force reload
    $current_version = wp_get_theme()->get('Version');
    update_option('stylesheet_version', $current_version . '.' . time());
    
    // Clear any plugin caches
    if (function_exists('w3tc_flush_all')) w3tc_flush_all();
    if (function_exists('wp_cache_clear_cache')) wp_cache_clear_cache();
    if (function_exists('rocket_clean_domain')) rocket_clean_domain();
    if (function_exists('litespeed_purge_all')) litespeed_purge_all();
    
    // Clear WP Fastest Cache - this is crucial for template changes
    if (function_exists('wpfc_clear_all_cache')) {
        wpfc_clear_all_cache(true);
    }
}

/**
 * Clear Front Page Cache Automatically
 * 
 * Automatically clears the cache when the front page or related templates are saved
 */
function together_forever_clear_front_page_cache() {
    // Clear all caches to ensure front page updates
    wp_cache_flush();
    
    // Clear WP Fastest Cache specifically for homepage
    if (function_exists('wpfc_clear_all_cache')) {
        wpfc_clear_all_cache(true);
    }
    
    // Clear specific page caches
    if (class_exists('WpFastestCache')) {
        $wpfc = new WpFastestCache();
        if (method_exists($wpfc, 'deleteCache')) {
            $wpfc->deleteCache(true); // Delete all cache
        }
    }
    
    // Clear LiteSpeed cache for homepage
    if (defined('LSCWP_V')) {
        do_action('litespeed_purge_all');
    }
    
    // Set transient to prevent cache regeneration for 5 minutes
    set_transient('together_forever_cache_cleared', true, 300);
}

/**
 * Auto-clear cache on theme/template changes
 * 
 * This ensures that when you modify template files, the cache is automatically cleared
 */
function together_forever_auto_clear_cache_on_save() {
    // Only run in admin or when accessing the site after a file change
    if (!is_admin()) {
        // Check if template file was recently modified (within last 60 seconds)
        $front_page_template = get_stylesheet_directory() . '/front-page.php';
        $functions_file = get_stylesheet_directory() . '/functions.php';
        
        if (file_exists($front_page_template)) {
            $last_modified = filemtime($front_page_template);
            $time_since_modified = time() - $last_modified;
            
            // If file was modified in last 60 seconds, clear cache
            if ($time_since_modified < 60) {
                $cache_cleared_flag = get_transient('together_forever_cache_cleared_' . $last_modified);
                
                if (!$cache_cleared_flag) {
                    together_forever_clear_front_page_cache();
                    // Set flag to prevent clearing multiple times
                    set_transient('together_forever_cache_cleared_' . $last_modified, true, 300);
                }
            }
        }
    }
}
add_action('init', 'together_forever_auto_clear_cache_on_save', 1);
