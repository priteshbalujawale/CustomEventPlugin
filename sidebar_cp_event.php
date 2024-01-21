<aside id="secondary" class="widget-area">
    <?php dynamic_sidebar('right-sidebar'); ?>

    <?php
    // Display upcomng events in the sidebar
    echo '<div class="widget">';
    echo '<h2>Upcoming Events</h2>';

    $recent_events_args = array(
        'post_type'      => 'event',
        'posts_per_page' => 8,
        'order'          => 'DESC',
        'orderby'        => 'date',
    );

    $recent_events_query = new WP_Query($recent_events_args);

    if ($recent_events_query->have_posts()) :
        echo '<ul>';
        while ($recent_events_query->have_posts()) :
            $recent_events_query->the_post();
            echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
        endwhile;
        echo '</ul>';
        wp_reset_postdata();
    else :
        echo 'No recent events found.';
    endif;

    echo '</div>';
    ?>

</aside>