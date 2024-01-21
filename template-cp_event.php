<?php
/*
 * Template Name: CP Event
 */


get_header();
?>
<div class="admin-event-container" id="primary">
    <?php
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'post_type' => 'event',
        'paged'     => $paged,
    );

    $event_query = new WP_Query($args);

    if ($event_query->have_posts()) :
    ?>

        <div class=" event-main-body">
            <h1>Our Events</h1>
            <ul>
                <?php
                //code which itterate through all event post
                while ($event_query->have_posts()) : $event_query->the_post();
                ?>
                    <li class="event-title-list">
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <h2 class="entry-title event-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>


                            <?php // Code to display all custom field values
                            ?>
                            <div class="event-date-container">
                                <?php
                                $custom_fields = get_post_custom($post->ID);

                                if (!empty($custom_fields['_event_start_date'][0])) :
                                ?>
                                    <div class="event-start-container">
                                        <p><?php echo "Event Starts on " ?>:</p>
                                        <p><?php echo esc_html($custom_fields['_event_start_date'][0]); ?></p>
                                    </div>
                                <?php endif;

                                if (!empty($custom_fields['_event_end_date'][0])) :
                                ?>
                                    <div class="event-end-container">
                                        <p><?php echo "Event Ends on "; ?>:</p>
                                        <p><?php echo esc_html($custom_fields['_event_end_date'][0]); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>



                            <?php //code to display featured image
                            ?>
                            <div class="event-inner-container">

                                <div class="fetured-img-container">
                                    <?php
                                    if (has_post_thumbnail()) :
                                    ?>
                                        <div class="featured-image">
                                            <?php the_post_thumbnail('medium'); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>


                                <?php //code to display Excerpt 
                                ?>
                                <div class="entry-content">
                                    <p><?php the_excerpt(); ?></p>
                                </div>
                            </div>
                        </article>
                    </li>
                <?php
                endwhile;
                wp_reset_postdata(); // Reset the query to the main loop
                ?>
            </ul>


            <?php
            // Pagination links
            echo '<div class="event-pagination">' . paginate_links(array(
                'total'   => $event_query->max_num_pages,
                'current' => max(1, get_query_var('paged')),
            )) . '</div>';
            ?>
        </div>

    <?php
    else :
        echo 'No events found.';
    endif;
    ?>
</div>

<?php include(WP_PLUGIN_DIR . '/cp-events/sidebar_cp_event.php'); ?>
<?php get_footer(); ?>
</body>