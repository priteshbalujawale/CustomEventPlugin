<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        while (have_posts()) :
            the_post();
            echo '<h1>' . get_the_title() . '</h1>';
            $event_start_date = get_post_meta(get_the_ID(), '_event_start_date', true);
            if (!empty($event_start_date)) {
                echo '<p>Event Starts On: ' . esc_html($event_start_date) . '</p>';
            }

            $event_end_date = get_post_meta(get_the_ID(), '_event_end_date', true);
            if (!empty($event_end_date)) {
                echo '<p>Event Ends On: ' . esc_html($event_end_date) . '</p>';
            }

            if (has_post_thumbnail()) {

                the_post_thumbnail('large');
            }

            the_content();

        endwhile;
        ?>
    </main>
</div>
<?php include(WP_PLUGIN_DIR . '/cp-events/sidebar_cp_event.php'); ?>
<?php get_footer(); ?>