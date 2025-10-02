<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="masthead" class="site-header">
    <div class="header-container">
        <!-- Logo Section -->
        <div class="site-logo">
            <a href="/">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/TF-logo.svg" alt="Together Forever Logo" class="logo-image">
            </a>
        </div>

        <!-- Navigation Section -->
        <nav class="main-navigation">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'menu_class'     => 'nav-menu',
                'container'      => false,
                'fallback_cb'    => false,
            ));
            ?>
            
            <!-- Contact Button -->
            <a href="#contact" class="contact-button">Contact Us</a>
        </nav>
    </div>
</header>

<div id="page" class="site">
