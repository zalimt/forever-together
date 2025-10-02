<?php
/**
 * Template Name: Together Forever Home
 * 
 * Custom page template for the Together Forever home page.
 * This template can be selected in the WordPress admin dashboard.
 *
 * @package Together_Forever
 */

get_header(); ?>

<main id="primary" class="site-main">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <section class="hero-section">
            <h1 class="site-title">Together Forever</h1>
        </section>
    </article>
</main>

<?php
get_sidebar();
get_footer();
