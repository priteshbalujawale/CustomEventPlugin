<?php

/**
 * Custom Post Events.
 *
 *
 */
/*
*Plugin Name: CP Events
*Description: Add custom events to your site
*Version:1.0
*Author: Pritesh Jawale
*/

if (!defined('ABSPATH')) {
    header("Location:/");
    die;
}

//after plugin activation give access to admin user
function my_plugin_activation()
{
    $capabilities = [
        'read' => true,
        'edit_posts' => true,
        'upload_file' => true
    ];
    add_role('admin_users', 'Admin User', $capabilities);
}
function my_plugin_deactivation()
{
    remove_role('admin_users');
}
//register the plugin
register_activation_hook(__FILE__, 'my_plugin_activation');
register_deactivation_hook(__FILE__, 'my_plugin_deactivation');

// add custom post type
function cw_post_type_event()
{
    $supports = array(

        'title',

        'editor',

        'author',

        'thumbnail',

        'excerpt',

        'revisions',

        'post-formats',

        'trackbacks',

        'revisions'

    );

    $labels = array(

        'name' => esc_html('Events', 'plural'),

        'singular_name' => esc_html('Event', 'singular'),

        'menu_name' => esc_html('Events', 'admin menu'),

        'name_admin_bar' => esc_html('Events', 'admin bar'),

        'add_new' => esc_html('Add New Event', 'add new'),

        'add_new_item' => esc_html('Add New Event'),

        'new_item' => esc_html('New Event'),

        'edit_item' => esc_html('Edit Event'),

        'view_item' => esc_html('View Event'),

        'all_items' => esc_html('All Events'),

        'search_items' => esc_html('Search Events'),

        'not_found' => esc_html('No Event found.'),

    );

    $args = array(

        'supports' => $supports,

        'labels' => $labels,

        'public' => true,

        'query_var' => true,

        'rewrite' => array('slug' => 'event'),

        'has_archive' => true,

        'hierarchical' => false,

        'menu_icon' => 'dashicons-calendar',

        'show_ui' => true,

        'show_in_menu' => true,

        'show_in_nav_menus' => true,

        'show_in_admin_bar' => true,

        'can_export' => true,

        'capability_type' => array('event', 'events'),

        'map_meta_cap' => true,

        'query_var' => true,


    );

    register_post_type('event', $args);
}
add_action('init', 'cw_post_type_event');


// give the permission to edit the post only to admin user and administrator
add_action('admin_init', 'cw_post_type_event_caps');
function cw_post_type_event_caps()
{
    $role = array('admin_users', 'administrator');
    foreach ($role as $the_role) {
        $role = get_role($the_role);
        $role->add_cap('read');
        $role->add_cap('read_events');
        $role->add_cap('read_private_events');
        $role->add_cap('edit_events');
        $role->add_cap('edit_other_events');
        $role->add_cap('edit_published_events');
        $role->add_cap('publish_events');
        $role->add_cap('delete_others_events');
        $role->add_cap('delete_private_events');
        $role->add_cap('delete_published_events');
    }
}



// add custom fields to custom post type event
function cw_post_cf()
{
    add_meta_box(
        "cf_event_start_meta",
        "Event Start Date",
        "display_cp_event_start",
        "event",
        "side",
        "low"
    );
    add_meta_box(
        "cf_event_end_meta",
        "Event End Date",
        "display_cp_event_end",
        "event",
        "side",
        "low"
    );
}
add_action("add_meta_boxes", "cw_post_cf");

// save custom field
function save_cw_post_cf($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST["_event_start_date"])) {
        update_post_meta($post_id, "_event_start_date", sanitize_text_field($_POST["_event_start_date"]));
    }
    if (isset($_POST["_event_end_date"])) {
        update_post_meta($post_id, "_event_end_date", sanitize_text_field($_POST["_event_end_date"]));
    }
}
add_action('save_post', 'save_cw_post_cf');


// display custom field to post editor
function display_cp_event_start()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $fieldData = isset($custom["_event_start_date"][0]) ? $custom["_event_start_date"][0] : '';
    echo '<input type="date" name="_event_start_date" placeholder="Event Start On" value="' . esc_attr($fieldData) . '">';
}
function display_cp_event_end()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $fieldData = isset($custom["_event_end_date"][0]) ? $custom["_event_end_date"][0] : '';
    echo '<input type="date" name="_event_end_date" placeholder="Event End On" value="' . esc_attr($fieldData) . '">';
}


// Add custom columns to the event post type
function add_custom_columns($columns)
{
    $columns['_event_start_date'] = 'Event Start On';
    $columns['_event_end_date'] = 'Event End On';
    return $columns;
}
add_filter('manage_event_posts_columns', 'add_custom_columns');

function display_custom_columns($column, $post_id)
{
    switch ($column) {
        case '_event_start_date':
            echo get_post_meta($post_id, '_event_start_date', true);
            break;
        case '_event_end_date':
            echo get_post_meta($post_id, '_event_end_date', true);
            break;
    }
}
add_action('manage_event_posts_custom_column', 'display_custom_columns', 10, 2);


// add the external files

// // add the custom template for all events 
function assign_custom_template()
{
    $theme_directory = get_template_directory();
    $template_file_path = $theme_directory . '/template-cp_event.php';
    // if the template already exist then it overide that template and if not then insert new tempalte
    if (file_exists($template_file_path)) {
        $template_content = file_get_contents(__DIR__ . '/template-cp_event.php');
        file_put_contents($template_file_path, $template_content);
    } else {
        $template_content = file_get_contents(__DIR__ . '/template-cp_event.php');
        file_put_contents($template_file_path, $template_content);
    }
}
register_activation_hook(__FILE__, 'assign_custom_template');

// add the custom template for single event
function assign_single_custom_template()
{
    $theme_directory = get_template_directory();
    $template_file_path = $theme_directory . '/single-cp_event.php';
    if (file_exists($template_file_path)) {
        $template_content = file_get_contents(__DIR__ . '/single-cp_event.php');
        file_put_contents($template_file_path, $template_content);
    } else {
        $template_content = file_get_contents(__DIR__ . '/single-cp_event.php');
        file_put_contents($template_file_path, $template_content);
    }
}
// register_activation_hook(__FILE__, 'assign_single_custom_template');

// add custom css 
function plugin_enqueue_scripts()
{
    wp_enqueue_style('custom-style', plugins_url('css-cp_event.css', __FILE__), array(), '1.0');
}
add_action('wp_enqueue_scripts', 'plugin_enqueue_scripts');
