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
    <article class="front-page-content">
        <section class="front-page-section" style="max-width: 1400px; padding: 0 20px; margin: 0 auto;">
            <div class="hero">
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
            <a href="/certificate" class="btn btn-gradient">Gift a Certificate</a>
        </section>
    </article>
    <article class="kids-section">
        <section>
            <h2 class="section-title">Children Who Need Our Help</h2>
            <div class="kids-grid">
                <?php
                // Query Kids posts with "In Need of Help" category
                $kids_query = new WP_Query(array(
                    'post_type' => 'kids',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'kid_category',
                            'field' => 'name',
                            'terms' => 'In Need of Help',
                        ),
                    ),
                    'orderby' => 'date',
                    'order' => 'DESC',
                ));
                
                if ($kids_query->have_posts()) {
                    while ($kids_query->have_posts()) {
                        $kids_query->the_post();
                        
                        // Get post data
                        $kid_bio = get_the_content();
                        
                        // Get ACF fields
                        $kid_name = get_field('kids_card_name');
                        $kid_image = get_field('kid_card_image');
                        // Fallback to featured image if ACF image not set
                        if (!$kid_image && has_post_thumbnail()) {
                            $kid_image = array(
                                'url' => get_the_post_thumbnail_url(get_the_ID(), 'full')
                            );
                        }
                        
                        $collected_amount = floatval(get_field('collected_amount'));
                        $required_amount = floatval(get_field('required_amount'));
                        $kid_age = get_field('kid_age');
                        $kid_diagnosis = get_field('kid_diagnosis');
                        $donate_btn_link = get_field('donate_btn_link');
                        
                        // Always use post permalink for "More About" link
                        $more_about_link = get_permalink();
                        
                        // Calculate progress percentage
                        $progress_percentage = $required_amount > 0 ? ($collected_amount / $required_amount) * 100 : 0;
                        $progress_percentage = min(100, max(0, $progress_percentage)); // Ensure between 0-100
                        
                        // Determine which elephant SVG to use (0-11)
                        $elephant_index = 0;
                        if ($progress_percentage > 0) {
                            $elephant_index = min(11, ceil($progress_percentage / 9.09)); // 100/11 ≈ 9.09
                        }
                        ?>
                        
                        <div class="kid-card">
                            <div class="card-top">
                                <div class="card-header">
                                    <div class="kid-image-container">
                                        <img src="<?php echo $kid_image['url']; ?>" alt="<?php echo $kid_name; ?>" class="kid-image">
                                    </div>
                                    <div class="elephant-progress">
                                        <div class="elephant-container">
                                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/elephant-progress-bar-<?php echo $elephant_index; ?>.svg" alt="Progress" class="elephant-progress-image">
                                            <div class="progress-percentage"><?php echo round($progress_percentage); ?>%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-bottom">
                                <div class="amounts-section">
                                    <div class="amount-row">
                                        <div class="amount-item">
                                            <span class="amount-label">Collected Amount:</span>
                                            <span class="amount-value collected">€<?php echo number_format($collected_amount); ?></span>
                                        </div>
                                        <div class="amount-item">
                                            <span class="amount-label">Required Amount:</span>
                                            <span class="amount-value required">€<?php echo number_format($required_amount); ?></span>
                                        </div>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?php echo $progress_percentage; ?>%"></div>
                                    </div>
                                </div>
                                
                                <div class="kid-details">
                                    <h3 class="kid-name"><?php echo $kid_name; ?></h3>
                                    <p class="kid-age"><?php echo $kid_age; ?></p>
                                    <p class="kid-diagnosis">
                                        <span class="diagnosis-label">Diagnosis:</span>
                                        <span class="diagnosis-value"><?php echo $kid_diagnosis; ?></span>
                                    </p>
                                </div>
                                
                                <div class="card-actions">
                                    <a href="<?php echo $donate_btn_link; ?>" class="donate-btn">Donate</a>
                                    <a href="<?php echo $more_about_link; ?>" class="more-about-link">More About a Child</a>
                                </div>
                            </div>
                        </div>
                        
                        <?php
                    }
                    wp_reset_postdata();
                } else {
                    // Fallback content if no Kids posts
                    echo '<p>No children in need of help at this time.</p>';
                }
                ?>
            </div>
        </section>
    </article>
    <article class="inspiration">
        <section class="inspiration-banner">
            <div class="banner-content">
                <h2 class="banner-message">
                    Every child deserves a chance for treatment, a happy childhood, and a healthy future. Together we can do more!
                </h2>
            </div>
        </section>
    </article>
    <article class="making-difference">
        <section class="stats-section">
            <div class="stats-container">
                <div class="stats-content">
                    <div class="stats-text">
                        <h2 class="stats-title">Making a Difference</h2>
                        <p class="stats-subtitle">For 9 years the Together Forever foundation has funded:</p>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number" data-target="58">0</div>
                                <div class="stat-label">Surgeries</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number" data-target="22">0</div>
                                <div class="stat-label">Medical Examinations</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number" data-target="41">0</div>
                                <div class="stat-label">Rehabilitation Programs</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number" data-target="2">0</div>
                                <div class="stat-label">Online Consultations</div>
                            </div>
                        </div>
                    </div>
                    <div class="stats-icon">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/making-a-difference-icon.svg" alt="Achievement Medal" class="medal-icon">
                    </div>
                </div>
            </div>
        </section>
    </article>
    <article class="our-mission">
        <section class="mission-section">
            <div class="mission-container">
                <div class="mission-content">
                    <div class="mission-image">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/our-mission.png" alt="Children with Together Forever Foundation" class="mission-photo">
                    </div>
                    <div class="mission-text">
                        <h2 class="mission-title">Our Mission</h2>
                        <div class="mission-description">
                            <p>We give children with rare neurological conditions of the brain and spinal cord <strong>a chance at life</strong> by opening the doors to world-class medical care in leading clinics around the world.</p>
                            <p><strong>Every contribution</strong> goes directly toward treatment costs for our beneficiaries.</p>
                            <p>Funds raised through our charity events are <strong>dedicated entirely to helping children</strong> receive the care they need.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </article>
    <article class="partners-slider">
        <section class="partners-section">
            <div class="partners-container">
                <div class="partners-slider-wrapper">
                    <div class="partners-track">
                        <!-- First set of logos -->
                        <div class="partner-logo">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partners-logos/natrue-logo.svg" alt="Natrue" class="logo-img">
                        </div>
                        <div class="partner-logo">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partners-logos/rusradio-logo.png" alt="RusRadio" class="logo-img">
                        </div>
                        <div class="partner-logo">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partners-logos/tria-logo.svg" alt="Tria" class="logo-img">
                        </div>
                        <!-- Second set for 6 logos -->
                        <div class="partner-logo">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partners-logos/natrue-logo.svg" alt="Natrue" class="logo-img">
                        </div>
                        <div class="partner-logo">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partners-logos/rusradio-logo.png" alt="RusRadio" class="logo-img">
                        </div>
                        <div class="partner-logo">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partners-logos/tria-logo.svg" alt="Tria" class="logo-img">
                        </div>
                        <!-- Third set for seamless loop -->
                        <div class="partner-logo">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partners-logos/natrue-logo.svg" alt="Natrue" class="logo-img">
                        </div>
                        <div class="partner-logo">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partners-logos/rusradio-logo.png" alt="RusRadio" class="logo-img">
                        </div>
                        <div class="partner-logo">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partners-logos/tria-logo.svg" alt="Tria" class="logo-img">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </article>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to animate counting
    function animateCounter(element, target, duration = 2000) {
        let start = 0;
        const increment = target / (duration / 16); // 60fps
        
        function updateCounter() {
            start += increment;
            if (start < target) {
                element.textContent = Math.floor(start);
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target;
            }
        }
        
        updateCounter();
    }
    
    // Intersection Observer to trigger animation when section comes into view
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumbers = entry.target.querySelectorAll('.stat-number');
                statNumbers.forEach(statNumber => {
                    const target = parseInt(statNumber.getAttribute('data-target'));
                    animateCounter(statNumber, target);
                });
                // Stop observing after animation starts
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.3 // Trigger when 30% of the section is visible
    });
    
    // Start observing the stats section
    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
        observer.observe(statsSection);
    }
});

// jQuery Partners Slider
$(window).on('load', function () {
    const $slider = $('.partners-track');
    const $items = $slider.children('.partner-logo');

    if (!$items.length) {
        console.warn('No partner logos found');
        return;
    }

    // Clone items to create seamless loop
    const originalItems = $items.toArray();
    $slider.empty();
    
    // Add items twice for seamless loop
    originalItems.forEach(item => $slider.append($(item).clone()));
    originalItems.forEach(item => $slider.append($(item).clone()));

    const itemWidth = 200; // Approximate width + gap
    const totalItems = $slider.children().length;
    
    // Set initial position
    let currentPosition = -(originalItems.length * itemWidth);
    const speed = 1; // Pixels per frame
    
    $slider.css({
        'transition': 'none',
        'transform': `translateX(${currentPosition}px)`
    });

    let isRunning = true;
    let animationFrame;

    function animate() {
        if (!isRunning) return;
        
        currentPosition += speed;
        
        // Reset when we've moved one complete set
        if (currentPosition >= 0) {
            currentPosition = -(originalItems.length * itemWidth);
        }
        
        $slider.css('transform', `translateX(${currentPosition}px)`);
        animationFrame = requestAnimationFrame(animate);
    }

    // Start animation
    animate();

    // Pause on hover
    $slider
        .on('mouseenter', () => {
            isRunning = false;
            if (animationFrame) {
                cancelAnimationFrame(animationFrame);
            }
        })
        .on('mouseleave', () => {
            isRunning = true;
            animate();
        });
});
</script>

<?php
get_footer();
