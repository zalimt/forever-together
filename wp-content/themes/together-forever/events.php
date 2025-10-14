<?php
/**
 * Template Name: Events
 * 
 * A custom page template for the Events page
 */

get_header(); ?>

<main id="main" class="site-main events-page">
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('events-page-content'); ?>>
            
            <!-- Events Header Section -->
            <section class="events-header-section">
                <div class="container">
                    <?php 
                    // Get ACF fields for events header
                    $events_heading = get_field('events_heading');
                    $events_subheading = get_field('events_subheading');
                    ?>
                    
                    <?php if ($events_heading) : ?>
                        <h1 class="events-heading"><?php echo wp_kses_post(nl2br($events_heading)); ?></h1>
                    <?php else : ?>
                        <h1 class="events-heading">Upcoming <strong>Events</strong></h1>
                    <?php endif; ?>
                    
                    <?php if ($events_subheading) : ?>
                        <p class="events-subheading"><?php echo wp_kses_post(nl2br($events_subheading)); ?></p>
                    <?php else : ?>
                        <p class="events-subheading">Join us at our upcoming events and charity activities. Together we can make a difference in the lives of children who need our support.</p>
                    <?php endif; ?>
                    
                    <!-- Search Bar -->
                    <div class="events-search">
                        <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                            <div class="search-input-wrapper">
                                <input type="search" class="search-field" placeholder="Search Events" value="<?php echo get_search_query(); ?>" name="s" />
                                <input type="hidden" name="category_name" value="events" />
                                <button type="submit" class="search-submit">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Events Posts Section -->
            <section class="events-posts-section">
                <div class="container">
                    <?php
                    // Query events posts from the "events" category
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    $events_posts = new WP_Query(array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'posts_per_page' => 9,
                        'paged' => $paged,
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'category_name' => 'events'
                    ));
                    
                    if ($events_posts->have_posts()) : ?>
                        <div class="events-posts-grid">
                            <?php while ($events_posts->have_posts()) : $events_posts->the_post(); ?>
                                <article class="events-post-card">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="post-thumbnail">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('medium_large', array('class' => 'post-featured-image')); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="post-content">
                                        <div class="post-categories">
                                            <?php
                                            $categories = get_the_category();
                                            if (!empty($categories)) {
                                                foreach (array_slice($categories, 0, 3) as $category) {
                                                    echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-tag category-' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</a>';
                                                }
                                            }
                                            ?>
                                        </div>
                                        
                                        <h2 class="post-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        
                                        <div class="post-excerpt">
                                            <?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?>
                                        </div>
                                        
                                        <div class="post-meta">
                                            <time datetime="<?php echo get_the_date('c'); ?>" class="post-date">
                                                <?php echo get_the_date(); ?>
                                            </time>
                                        </div>
                                        
                                        <div class="read-more-wrapper">
                                            <a href="<?php the_permalink(); ?>" class="read-more-btn">Read Article</a>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>

                        <!-- Pagination -->
                        <?php if ($events_posts->max_num_pages > 1) : ?>
                            <div class="events-pagination">
                                <?php
                                echo paginate_links(array(
                                    'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                                    'total' => $events_posts->max_num_pages,
                                    'current' => max(1, $paged),
                                    'format' => '?paged=%#%',
                                    'show_all' => false,
                                    'type' => 'plain',
                                    'end_size' => 2,
                                    'mid_size' => 1,
                                    'prev_text' => '← Previous',
                                    'next_text' => 'Next →',
                                    'add_args' => false,
                                    'add_fragment' => '',
                                ));
                                ?>
                            </div>
                        <?php endif; ?>

                    <?php else : ?>
                        <div class="no-posts-message">
                            <h3>No events found</h3>
                            <p>It looks like there are no events scheduled yet. Check back soon for new events!</p>
                        </div>
                    <?php endif; ?>
                    
                    <?php wp_reset_postdata(); ?>
                </div>
            </section>
            
        </article>
        
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>

