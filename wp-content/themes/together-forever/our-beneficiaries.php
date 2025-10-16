<?php
/**
 * Template Name: Our Beneficiaries
 *
 * @package Together_Forever
 */

get_header(); ?>

<main>
    <article class="our-beneficiaries-content">
        <section class="our-beneficiaries-section" style="max-width: 1400px; padding: 0 20px; margin: 0 auto;">
            <!-- Header Section -->
            <div class="our-beneficiaries-hero">
        <section class="our-beneficiaries-banner">
            <div class="banner-content">
                <h2 class="banner-message">
                    Thanks to you we have raised <span class="banner-highlight">2 092 329 Euro</span> to help <span class="banner-highlight">125 children</span>.
                </h2>
            </div>
        </section>
            </div>

            <!-- Tabs Section -->
            <div class="beneficiaries-tabs">
        <section class="tabs-section">
            <div class="tabs-container">
                <div class="tabs-navigation">
                    <button class="btn btn-primary tab-button active" data-tab="in-need">
                        In Need of Help
                    </button>
                    <button class="btn btn-secondary tab-button" data-tab="awaiting">
                        Awaiting Treatment
                    </button>
                    <button class="btn btn-secondary tab-button" data-tab="helped">
                        We Helped
                    </button>
                </div>
                
                <div class="tabs-content">
                    <!-- In Need of Help Tab -->
                    <div class="tab-panel active" id="in-need">
                        <div class="tab-header">
                            <h2 class="tab-title">Children In Need of Help</h2>
                            <p class="tab-description">These children urgently need your support to receive life-saving treatment.</p>
                        </div>
                        <div class="kids-section">
                            <section>
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
                                echo '<div class="placeholder-message"><p>No children in need of help at this time.</p></div>';
                            }
                            ?>
                                </div>
                            </section>
                        </div>
                    </div>
                    
                    <!-- Awaiting Treatment Tab -->
                    <div class="tab-panel" id="awaiting">
                        <div class="tab-header">
                            <h2 class="tab-title">Children Awaiting Treatment</h2>
                            <p class="tab-description">These children have raised sufficient funds and are currently awaiting or undergoing treatment.</p>
                        </div>
                        <div class="kids-section">
                            <section>
                                <div class="kids-grid">
                            <?php
                            // Query Kids posts with "Awaiting Treatment" category
                            $kids_query = new WP_Query(array(
                                'post_type' => 'kids',
                                'posts_per_page' => -1,
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'kid_category',
                                        'field' => 'name',
                                        'terms' => 'Awaiting Treatment',
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
                                echo '<div class="placeholder-message"><p>No children awaiting treatment at this time.</p></div>';
                            }
                            ?>
                                </div>
                            </section>
                        </div>
                    </div>
                    
                    <!-- We Helped Tab -->
                    <div class="tab-panel" id="helped">
                        <div class="tab-header">
                            <h2 class="tab-title">Children We Helped</h2>
                            <p class="tab-description">These are the children who have successfully completed their treatment thanks to your generous donations.</p>
                        </div>
                        <div class="kids-section">
                            <section>
                                <div class="kids-grid">
                            <?php
                            // Query Kids posts with "We Helped" category
                            $kids_query = new WP_Query(array(
                                'post_type' => 'kids',
                                'posts_per_page' => -1,
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'kid_category',
                                        'field' => 'name',
                                        'terms' => 'We Helped',
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
                                echo '<div class="placeholder-message"><p>No children we helped to display at this time.</p></div>';
                            }
                            ?>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </article>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanels = document.querySelectorAll('.tab-panel');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class and btn-primary from all buttons, add btn-secondary
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'btn-primary');
                btn.classList.add('btn-secondary');
            });
            
            // Remove active class from all panels
            tabPanels.forEach(panel => panel.classList.remove('active'));
            
            // Add active class, btn-primary, and remove btn-secondary from clicked button
            this.classList.add('active', 'btn-primary');
            this.classList.remove('btn-secondary');
            
            // Add active class to corresponding panel
            document.getElementById(targetTab).classList.add('active');
        });
    });
});
</script>

<?php
get_footer();
?>
