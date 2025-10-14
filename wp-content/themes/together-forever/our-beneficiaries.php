<?php
/**
 * Template Name: Our Beneficiaries
 *
 * @package Together_Forever
 */

get_header(); ?>

<main>
    <!-- Header Section -->
    <article class="our-beneficiaries-hero">
        <section class="our-beneficiaries-banner">
            <div class="banner-content">
                <h2 class="banner-message">
                    Thanks to you we have raised <span class="banner-highlight">2 092 329 Euro</span> to help <span class="banner-highlight">125 children</span>.
                </h2>
            </div>
        </section>
    </article>

    <!-- Tabs Section -->
    <article class="beneficiaries-tabs">
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
                            // Get the Kids Cards ACF field from the front page
                            $front_page_id = get_option('page_on_front');
                            $kids_cards = get_field('kids_cards', $front_page_id);
                            
                            if ($kids_cards) {
                                $has_cards = false;
                                foreach ($kids_cards as $card) {
                                    $status = $card['status'];
                                    
                                    // Only show cards with "In Need of Help" status in this tab
                                    if ($status !== 'In Need of Help') {
                                        continue;
                                    }
                                    
                                    $has_cards = true;
                                    
                                    $kid_image = $card['kid_card_image'];
                                    $collected_amount = floatval($card['collected_amount']);
                                    $required_amount = floatval($card['required_amount']);
                                    $kid_name = $card['kid_name'];
                                    $kid_age = $card['kid_age'];
                                    $kid_diagnosis = $card['kid_diagnosis'];
                                    $donate_btn_link = $card['donate_btn_link'];
                                    $more_about_link = $card['more_about_a_child_link'];
                                    
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
                                
                                if (!$has_cards) {
                                    echo '<div class="placeholder-message"><p>No children in need of help at this time.</p></div>';
                                }
                            } else {
                                echo '<div class="placeholder-message"><p>No kids cards data available. Please add content through ACF.</p></div>';
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
                            // Get the Kids Cards ACF field from the front page
                            $front_page_id = get_option('page_on_front');
                            $kids_cards = get_field('kids_cards', $front_page_id);
                            
                            if ($kids_cards) {
                                $has_cards = false;
                                foreach ($kids_cards as $card) {
                                    $status = $card['status'];
                                    
                                    // Only show cards with "Awaiting Treatment" status in this tab
                                    if ($status !== 'Awaiting Treatment') {
                                        continue;
                                    }
                                    
                                    $has_cards = true;
                                    
                                    $kid_image = $card['kid_card_image'];
                                    $collected_amount = floatval($card['collected_amount']);
                                    $required_amount = floatval($card['required_amount']);
                                    $kid_name = $card['kid_name'];
                                    $kid_age = $card['kid_age'];
                                    $kid_diagnosis = $card['kid_diagnosis'];
                                    $donate_btn_link = $card['donate_btn_link'];
                                    $more_about_link = $card['more_about_a_child_link'];
                                    
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
                                
                                if (!$has_cards) {
                                    echo '<div class="placeholder-message"><p>No children awaiting treatment at this time.</p></div>';
                                }
                            } else {
                                echo '<div class="placeholder-message"><p>No kids cards data available. Please add content through ACF.</p></div>';
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
                            // Get the Kids Cards ACF field from the front page
                            $front_page_id = get_option('page_on_front');
                            $kids_cards = get_field('kids_cards', $front_page_id);
                            
                            if ($kids_cards) {
                                $has_cards = false;
                                foreach ($kids_cards as $card) {
                                    $status = $card['status'];
                                    
                                    // Only show cards with "We Helped" status in this tab
                                    if ($status !== 'We Helped') {
                                        continue;
                                    }
                                    
                                    $has_cards = true;
                                    
                                    $kid_image = $card['kid_card_image'];
                                    $collected_amount = floatval($card['collected_amount']);
                                    $required_amount = floatval($card['required_amount']);
                                    $kid_name = $card['kid_name'];
                                    $kid_age = $card['kid_age'];
                                    $kid_diagnosis = $card['kid_diagnosis'];
                                    $donate_btn_link = $card['donate_btn_link'];
                                    $more_about_link = $card['more_about_a_child_link'];
                                    
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
                                
                                if (!$has_cards) {
                                    echo '<div class="placeholder-message"><p>No children we helped to display at this time.</p></div>';
                                }
                            } else {
                                echo '<div class="placeholder-message"><p>No kids cards data available. Please add content through ACF.</p></div>';
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
