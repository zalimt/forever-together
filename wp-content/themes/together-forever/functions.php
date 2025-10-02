<?php
/**
 * Together Forever Child Theme Functions
 * 
 * This file enqueues the parent theme styles and allows you to add
 * custom functionality to your child theme.
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
    $parent_style = 'twentytwentyfive-style';
    $parent_version = wp_get_theme('twentytwentyfive')->get('Version');
    
    // Enqueue parent theme stylesheet
    wp_enqueue_style(
        $parent_style,
        get_template_directory_uri() . '/style.css',
        array(),
        $parent_version
    );
    
    // Enqueue child theme stylesheet
    wp_enqueue_style(
        'together-forever-style',
        get_stylesheet_directory_uri() . '/style.css',
        array($parent_style),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'together_forever_enqueue_styles');

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
    // Example: add_theme_support('custom-logo');
    // Example: add_theme_support('post-thumbnails');
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
