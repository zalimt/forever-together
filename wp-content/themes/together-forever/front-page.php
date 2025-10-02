<?php
/**
 * The front page template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package Together_Forever
 */

get_header(); ?>

<main>
    <article>
        <section class="hero-section">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">Children Charitable Foundation Together Forever</h1>
                    <p class="hero-description">Was founded in 2016 to help children with rare neurological diseases of the brain and spinal cord.</p>
                    <p class="hero-stats">With your support we helped <span class="highlight">123 Children!</span></p>
                    <div class="hero-buttons">
                        <a href="#help" class="btn btn-primary">Help Children</a>
                        <a href="#contact" class="btn btn-secondary">Contact Us</a>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="image-frame">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/hero-image.png" alt="Happy Child" class="child-image">
                    </div>
                </div>
            </div>
        </section>

        <!-- Video Section -->
        <section class="video-section">
            <div class="video-container">
                <iframe 
                    width="800" 
                    height="400" 
                    src="https://www.youtube.com/embed/Kp4LNNJ1UL0" 
                    title="Together Forever Video" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                    allowfullscreen>
                </iframe>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <a href="#certificate" class="btn btn-gradient">Gift a Certificate</a>
        </section>
    </article>
</main>

<?php
get_footer();
