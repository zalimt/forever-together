<?php
/**
 * Kids Cards Migration Script
 * 
 * This script migrates kids cards from ACF repeater field to Kids custom post type
 * 
 * USAGE:
 * 1. Upload this file to your theme directory
 * 2. Access: yoursite.com/wp-content/themes/together-forever/migrate-kids-cards.php
 * 3. The script will automatically migrate all kids cards
 * 4. Delete this file after migration is complete
 */

// Load WordPress
require_once('../../../wp-load.php');

// Security check - only allow admin users
if (!current_user_can('administrator')) {
    die('Access denied. Admin privileges required.');
}

echo "<h1>Kids Cards Migration Script</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 40px; }
    .success { color: green; }
    .error { color: red; }
    .info { color: blue; }
    .card { border: 1px solid #ccc; padding: 15px; margin: 10px 0; background: #f9f9f9; }
</style>";

// Get the front page ID
$front_page_id = get_option('page_on_front');

if (!$front_page_id) {
    echo "<div class='error'>Error: Front page not set. Please set a front page in WordPress Settings.</div>";
    exit;
}

echo "<div class='info'>Front page ID: " . $front_page_id . "</div>";

// Get the kids cards from ACF repeater field
$kids_cards = get_field('kids_cards', $front_page_id);

if (!$kids_cards || empty($kids_cards)) {
    echo "<div class='error'>No kids cards found in ACF repeater field. Make sure the 'kids_cards' field exists on the front page.</div>";
    exit;
}

echo "<div class='info'>Found " . count($kids_cards) . " kids cards to migrate.</div>";

// Create the three categories if they don't exist
$categories = [
    'In Need of Help',
    'Awaiting Treatment', 
    'We Helped'
];

foreach ($categories as $category_name) {
    $term = get_term_by('name', $category_name, 'kid_category');
    if (!$term) {
        $result = wp_insert_term($category_name, 'kid_category');
        if (is_wp_error($result)) {
            echo "<div class='error'>Error creating category '$category_name': " . $result->get_error_message() . "</div>";
        } else {
            echo "<div class='success'>Created category: $category_name</div>";
        }
    } else {
        echo "<div class='info'>Category '$category_name' already exists</div>";
    }
}

echo "<hr>";

// Migrate each kids card
$migrated_count = 0;
$errors = [];

foreach ($kids_cards as $index => $card) {
    echo "<div class='card'>";
    echo "<h3>Migrating Card #" . ($index + 1) . "</h3>";
    
    // Extract data from the card
    $kid_name = $card['kid_name'] ?? '';
    $kid_bio = $card['kids_bio'] ?? '';
    $kid_image = $card['kid_card_image'] ?? '';
    $collected_amount = $card['collected_amount'] ?? '';
    $required_amount = $card['required_amount'] ?? '';
    $kid_age = $card['kid_age'] ?? '';
    $kid_diagnosis = $card['kid_diagnosis'] ?? '';
    $donate_btn_link = $card['donate_btn_link'] ?? '';
    $more_about_link = $card['more_about_a_child_link'] ?? '';
    $status = $card['status'] ?? 'In Need of Help';
    
    echo "<div class='info'>Name: $kid_name</div>";
    echo "<div class='info'>Status: $status</div>";
    echo "<div class='info'>Age: $kid_age</div>";
    echo "<div class='info'>Diagnosis: $kid_diagnosis</div>";
    echo "<div class='info'>Collected: €$collected_amount / Required: €$required_amount</div>";
    
    // Create the Kids post
    $post_data = [
        'post_title' => $kid_name,
        'post_content' => $kid_bio,
        'post_status' => 'publish',
        'post_type' => 'kids',
        'post_author' => get_current_user_id(),
    ];
    
    $post_id = wp_insert_post($post_data);
    
    if (is_wp_error($post_id)) {
        echo "<div class='error'>Error creating post: " . $post_id->get_error_message() . "</div>";
        $errors[] = "Card #" . ($index + 1) . ": " . $post_id->get_error_message();
        continue;
    }
    
    echo "<div class='success'>Created post with ID: $post_id</div>";
    
    // Set the category
    $category_term = get_term_by('name', $status, 'kid_category');
    if ($category_term) {
        wp_set_post_terms($post_id, [$category_term->term_id], 'kid_category');
        echo "<div class='success'>Set category: $status</div>";
    }
    
    // Set ACF fields
    update_field('kids_card_name', $kid_name, $post_id);
    update_field('kid_card_image', $kid_image, $post_id);
    update_field('collected_amount', $collected_amount, $post_id);
    update_field('required_amount', $required_amount, $post_id);
    update_field('kid_age', $kid_age, $post_id);
    update_field('kid_diagnosis', $kid_diagnosis, $post_id);
    update_field('donate_btn_link', $donate_btn_link, $post_id);
    update_field('more_about_a_child_link', $more_about_link, $post_id);
    
    echo "<div class='success'>Set all ACF fields</div>";
    
    // Handle featured image if kid_image is provided
    if ($kid_image && is_array($kid_image) && isset($kid_image['ID'])) {
        set_post_thumbnail($post_id, $kid_image['ID']);
        echo "<div class='success'>Set featured image</div>";
    }
    
    $migrated_count++;
    echo "</div>";
}

echo "<hr>";
echo "<h2>Migration Summary</h2>";
echo "<div class='success'>Successfully migrated $migrated_count kids cards to Kids posts.</div>";

if (!empty($errors)) {
    echo "<div class='error'>Errors encountered:</div>";
    foreach ($errors as $error) {
        echo "<div class='error'>- $error</div>";
    }
}

echo "<div class='info'><strong>Next Steps:</strong></div>";
echo "<div class='info'>1. Visit your front page to see the migrated kids cards</div>";
echo "<div class='info'>2. Visit WordPress Admin → Kids → All Kids to manage the new posts</div>";
echo "<div class='info'>3. Test the 'More About a Child' links to ensure they work</div>";
echo "<div class='info'>4. Once confirmed working, you can remove the old ACF repeater field</div>";
echo "<div class='info'>5. <strong>DELETE THIS MIGRATION FILE</strong> for security</div>";

echo "<hr>";
echo "<p><a href='" . admin_url('edit.php?post_type=kids') . "'>View All Kids Posts →</a></p>";
echo "<p><a href='" . home_url() . "'>View Front Page →</a></p>";
?>
