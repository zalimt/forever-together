<?php
/**
 * Single Kids Post Template
 * 
 * Template for displaying individual Kids profiles
 * 
 * @package Together_Forever
 */

get_header(); ?>

<main id="main" class="site-main single-kids">
    <?php while (have_posts()) : the_post(); 
    
        // Get ACF fields
        $kid_name = get_field('kids_card_name');
        if (empty($kid_name)) {
            $kid_name = get_the_title();
        }
        
        $kid_image = get_field('kid_card_image');
        $kid_age = get_field('kid_age');
        $kid_diagnosis = get_field('kid_diagnosis');
        $collected_amount = floatval(get_field('collected_amount'));
        $required_amount = floatval(get_field('required_amount'));
        $donate_btn_link = get_field('donate_btn_link');
        
        // Get category for status
        $categories = get_the_terms(get_the_ID(), 'kid_category');
        $status = !empty($categories) ? $categories[0]->name : 'In Need of Help';
        
        // Calculate progress percentage
        $progress_percentage = $required_amount > 0 ? ($collected_amount / $required_amount) * 100 : 0;
        $progress_percentage = min(100, max(0, $progress_percentage));
        
        // Determine which elephant SVG to use (0-11)
        $elephant_index = 0;
        if ($progress_percentage > 0) {
            $elephant_index = min(11, ceil($progress_percentage / 9.09));
        }
        
        // Get featured image or ACF image
        $featured_image_url = '';
        if ($kid_image) {
            $featured_image_url = $kid_image['url'];
        } elseif (has_post_thumbnail()) {
            $featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
        }
        ?>
        
        <!-- Hero Section with Kid's Image -->
        <?php if ($featured_image_url) : ?>
            <section class="single-kids-hero" style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('<?php echo esc_url($featured_image_url); ?>');">
                <div class="hero-overlay">
                    <div class="container">
                        <div class="hero-content">
                            <!-- Back Button -->
                            <button onclick="history.back()" class="back-button">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19 12H5M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Back
                            </button>
                            
                            <h1 class="single-kids-title"><?php echo esc_html($kid_name); ?></h1>
                            
                            <?php if ($kid_age) : ?>
                                <p class="single-kids-age"><?php echo esc_html($kid_age); ?></p>
                            <?php endif; ?>
                            
                            <!-- Status Badge -->
                            <div class="status-badge status-<?php echo esc_attr(sanitize_title($status)); ?>">
                                <?php echo esc_html($status); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php else : ?>
            <!-- No Image - Show Header Section -->
            <section class="single-kids-header">
                <div class="container">
                    <div class="header-content">
                        <button onclick="history.back()" class="back-button">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19 12H5M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Back
                        </button>
                        
                        <h1 class="single-kids-title-header"><?php echo esc_html($kid_name); ?></h1>
                        
                        <?php if ($kid_age) : ?>
                            <p class="single-kids-age"><?php echo esc_html($kid_age); ?></p>
                        <?php endif; ?>
                        
                        <div class="status-badge status-<?php echo esc_attr(sanitize_title($status)); ?>">
                            <?php echo esc_html($status); ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        
        <!-- Progress & Info Section -->
        <section class="single-kids-progress-section">
            <div class="container">
                <div class="progress-container">
                    
                    <!-- Progress Card -->
                    <div class="progress-card">
                        <h2 class="progress-title">Fundraising Progress</h2>
                        
                        <!-- Elephant Progress Indicator -->
                        <div class="elephant-progress-large">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/elephant-progress-bar-<?php echo $elephant_index; ?>.svg" alt="Progress" class="elephant-progress-image">
                            <div class="progress-percentage-large"><?php echo round($progress_percentage); ?>%</div>
                        </div>
                        
                        <!-- Amounts -->
                        <div class="amounts-display">
                            <div class="amount-box collected">
                                <span class="amount-label">Collected</span>
                                <span class="amount-value">€<?php echo number_format($collected_amount); ?></span>
                            </div>
                            <div class="amount-box required">
                                <span class="amount-label">Required</span>
                                <span class="amount-value">€<?php echo number_format($required_amount); ?></span>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="progress-bar-large">
                            <div class="progress-fill" style="width: <?php echo $progress_percentage; ?>%"></div>
                        </div>
                        
                        <?php if ($required_amount > $collected_amount) : 
                            $remaining = $required_amount - $collected_amount;
                        ?>
                            <p class="remaining-amount">
                                <strong>€<?php echo number_format($remaining); ?></strong> still needed to reach our goal
                            </p>
                        <?php elseif ($collected_amount >= $required_amount) : ?>
                            <p class="goal-reached">
                                <strong>🎉 Goal Reached!</strong> Thank you for your support!
                            </p>
                        <?php endif; ?>
                        
                        <!-- Donate Button -->
                        <?php if ($donate_btn_link) : ?>
                            <a href="<?php echo esc_url($donate_btn_link); ?>" class="btn btn-primary donate-button-large">
                                Donate Now
                            </a>
                        <?php endif; ?>
                    </div>
                    
                </div>
            </div>
        </section>
        
        <!-- Content Section -->
        <article id="post-<?php the_ID(); ?>" <?php post_class('single-kids-content'); ?>>
            <section class="single-kids-section" style="max-width: 1400px; padding: 0 20px; margin: 0 auto;">
                <div class="single-kids-wrapper">
                    
                    <!-- Main Content -->
                    <div class="single-kids-main">
                        
                        <?php if ($kid_diagnosis) : ?>
                            <div class="diagnosis-section">
                                <h3 class="section-title">Diagnosis</h3>
                                <div class="diagnosis-content">
                                    <?php echo wp_kses_post($kid_diagnosis); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="story-section">
                            <h3 class="section-title"><?php echo esc_html($kid_name); ?>'s Story</h3>
                            <div class="story-content">
                                <?php the_content(); ?>
                            </div>
                        </div>
                        
                        <!-- Call to Action -->
                        <div class="kids-cta-section">
                            <div class="cta-card">
                                <h3>Help <?php echo esc_html($kid_name); ?></h3>
                                <p>Your donation can make a real difference in <?php echo esc_html($kid_name); ?>'s life. Every contribution brings us closer to providing the life-changing treatment needed.</p>
                                <?php if ($donate_btn_link) : ?>
                                    <a href="<?php echo esc_url($donate_btn_link); ?>" class="btn btn-gradient">
                                        Make a Donation
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                    </div>
                    
                    <!-- Sidebar -->
                    <aside class="single-kids-sidebar">
                        <div class="sidebar-content">
                            <h3 class="sidebar-title">Other Children Who Need Help</h3>
                            
                            <?php
                            // Get other kids in need of help
                            $related_kids_query = new WP_Query(array(
                                'post_type' => 'kids',
                                'posts_per_page' => 3,
                                'post__not_in' => array(get_the_ID()),
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'kid_category',
                                        'field' => 'name',
                                        'terms' => 'In Need of Help',
                                    ),
                                ),
                                'orderby' => 'rand'
                            ));
                            
                            if ($related_kids_query->have_posts()) : ?>
                                <div class="related-kids">
                                    <?php while ($related_kids_query->have_posts()) : $related_kids_query->the_post(); 
                                        $related_kid_name = get_field('kids_card_name');
                                        if (empty($related_kid_name)) {
                                            $related_kid_name = get_the_title();
                                        }
                                        $related_kid_image = get_field('kid_card_image');
                                        $related_kid_age = get_field('kid_age');
                                        $related_collected = floatval(get_field('collected_amount'));
                                        $related_required = floatval(get_field('required_amount'));
                                        $related_progress = $related_required > 0 ? ($related_collected / $related_required) * 100 : 0;
                                        $related_progress = min(100, max(0, $related_progress));
                                    ?>
                                        <article class="related-kid-card">
                                            <?php if ($related_kid_image) : ?>
                                                <div class="related-kid-thumbnail">
                                                    <a href="<?php echo get_permalink(); ?>">
                                                        <img src="<?php echo esc_url($related_kid_image['url']); ?>" alt="<?php echo esc_attr($related_kid_name); ?>" class="related-kid-image">
                                                    </a>
                                                </div>
                                            <?php elseif (has_post_thumbnail()) : ?>
                                                <div class="related-kid-thumbnail">
                                                    <a href="<?php echo get_permalink(); ?>">
                                                        <?php the_post_thumbnail('medium', array('class' => 'related-kid-image')); ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="related-kid-content">
                                                <h4 class="related-kid-name">
                                                    <a href="<?php echo get_permalink(); ?>">
                                                        <?php echo esc_html($related_kid_name); ?>
                                                    </a>
                                                </h4>
                                                
                                                <?php if ($related_kid_age) : ?>
                                                    <p class="related-kid-age"><?php echo esc_html($related_kid_age); ?></p>
                                                <?php endif; ?>
                                                
                                                <div class="related-kid-progress">
                                                    <div class="progress-info">
                                                        <span class="progress-label"><?php echo round($related_progress); ?>% funded</span>
                                                        <span class="progress-amount">€<?php echo number_format($related_collected); ?> / €<?php echo number_format($related_required); ?></span>
                                                    </div>
                                                    <div class="progress-bar-small">
                                                        <div class="progress-fill" style="width: <?php echo $related_progress; ?>%"></div>
                                                    </div>
                                                </div>
                                                
                                                <a href="<?php echo get_permalink(); ?>" class="learn-more-btn">Learn More</a>
                                            </div>
                                        </article>
                                    <?php endwhile; ?>
                                    <?php wp_reset_postdata(); ?>
                                </div>
                            <?php else : ?>
                                <p class="no-related-kids">No other children currently need help.</p>
                            <?php endif; ?>
                            
                            <!-- General Donation CTA -->
                            <div class="sidebar-cta">
                                <h4>Can't decide who to help?</h4>
                                <p>Your general donation will be directed to the children who need it most urgently.</p>
                                <a href="/donate" class="btn btn-secondary">Make General Donation</a>
                            </div>
                        </div>
                    </aside>
                    
                </div>
            </section>
        </article>
        
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>

