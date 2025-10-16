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
        $status_slug = !empty($categories) ? $categories[0]->slug : 'in-need-of-help';
        
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
        
        <!-- Main Content Section -->
        <section class="single-kids-main-section">
            <div class="main-container">
                
                <!-- Left Column: Child Info -->
                <div class="child-info-column">
                    
                    <!-- Photo and Info Row -->
                    <div class="photo-info-row">
                        <!-- Child's Photo -->
                        <div class="child-photo-card">
                            <?php if ($featured_image_url) : ?>
                                <img src="<?php echo esc_url($featured_image_url); ?>" alt="<?php echo esc_attr($kid_name); ?>" class="child-image">
                            <?php else : ?>
                                <div class="no-photo-placeholder">
                                    <span>No photo available</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Child's Info -->
                        <div class="child-info-card">
                            <!-- Child's Name -->
                            <h1 class="child-name"><?php echo $kid_name; ?></h1>
                            
                            <!-- Diagnosis -->
                            <?php if ($kid_diagnosis) : ?>
                                <div class="child-diagnosis"><?php echo esc_html($kid_diagnosis); ?></div>
                            <?php endif; ?>
                            
                            <!-- Child's Age -->
                            <?php if ($kid_age) : ?>
                                <div class="child-age">Age: <?php echo esc_html($kid_age); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Child's Description -->
                    <?php if (get_the_content()) : ?>
                        <div class="child-description">
                            <?php the_content(); ?>
                        </div>
                    <?php endif; ?>
                    
                </div>
                
                <!-- Right Column: Donation Info & Related Kids -->
                <div class="donation-column">
                    
                    <!-- Donation Progress Card -->
                    <div class="donation-card">
                        
                        <!-- Collected Amount -->
                        <div class="collected-amount">
                            <span class="amount">€<?php echo number_format($collected_amount); ?></span>
                        </div>
                        
                        <!-- Required Amount -->
                        <div class="required-amount">
                            <span class="amount-label">out of €<?php echo number_format($required_amount); ?></span>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?php echo $progress_percentage; ?>%"></div>
                        </div>
                        
                        <!-- Remaining Amount -->
                        <?php if ($required_amount > $collected_amount) : 
                            $remaining = $required_amount - $collected_amount;
                        ?>
                            <div class="remaining-amount">
                                €<?php echo number_format($remaining); ?> still needed
                            </div>
                        <?php elseif ($collected_amount >= $required_amount) : ?>
                            <div class="goal-reached">
                                🎉 Goal Reached!
                            </div>
                        <?php endif; ?>
                        
                        <!-- Donate Button -->
                        <?php if ($donate_btn_link) : ?>
                            <a href="<?php echo esc_url($donate_btn_link); ?>" class="donate-button">
                                Donate Now
                            </a>
                        <?php endif; ?>
                        
                    </div>
                    
                    <!-- Related Kids Section -->
                    <div class="related-kids-section">
                        <h3 class="related-kids-title">More Stories</h3>
                        
                        <?php
                        // Query for related kids (same category, excluding current post)
                        $related_kids = new WP_Query(array(
                            'post_type' => 'kids',
                            'posts_per_page' => 3,
                            'post__not_in' => array(get_the_ID()),
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'kid_category',
                                    'field'    => 'slug',
                                    'terms'    => $status_slug,
                                ),
                            ),
                        ));
                        
                        if ($related_kids->have_posts()) :
                        ?>
                            <div class="related-kids-list">
                                <?php while ($related_kids->have_posts()) : $related_kids->the_post(); 
                                    $related_kid_name = get_field('kids_card_name') ?: get_the_title();
                                    $related_collected = get_field('collected_amount') ?: 0;
                                    $related_required = get_field('required_amount') ?: 100000;
                                    $related_remaining = $related_required - $related_collected;
                                    $related_image = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') : '';
                                    $related_donate_link = get_field('donate_btn_link');
                                    $related_progress = $related_required > 0 ? ($related_collected / $related_required) * 100 : 0;
                                ?>
                                    <div class="related-kid-card">
                                        <?php if ($related_image) : ?>
                                            <img src="<?php echo esc_url($related_image); ?>" alt="<?php echo esc_attr($related_kid_name); ?>" class="related-kid-image">
                                        <?php endif; ?>
                                        <div class="related-kid-info">
                                            <a href="<?php echo get_permalink(); ?>" class="related-kid-name-link">
                                                <h4 class="related-kid-name"><?php echo $related_kid_name; ?></h4>
                                            </a>
                                            <p class="related-kid-description"><?php echo wp_trim_words(get_the_content(), 15, '...'); ?></p>
                                            
                                            <!-- Progress Bar -->
                                            <div class="related-kid-progress">
                                                <div class="related-progress-bar">
                                                    <div class="related-progress-fill" style="width: <?php echo min(100, $related_progress); ?>%"></div>
                                                </div>
                                                <div class="related-progress-text">
                                                    <span class="collected">€<?php echo number_format($related_collected); ?></span>
                                                    <span class="required">/ €<?php echo number_format($related_required); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                            <?php wp_reset_postdata(); ?>
                        <?php endif; ?>
                        
                    </div>
                    
                </div>
                
            </div>
        </section>
        
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>

