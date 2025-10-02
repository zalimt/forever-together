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
