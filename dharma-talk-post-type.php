<?php
/*
Plugin Name: Dharma Talk Post Type
Description: Add post types for dharma talks
Author: Dan Harden
*/
// Hook <strong>custom_post_dharma_talk()</strong> to the init action hook
add_action( 'init', 'bzc_custom_post_dharma_talk' );
// The custom function to register a dharma talk post type
function bzc_custom_post_dharma_talk() {
// Set the labels, this variable is used in the $args array
$labels = array(
'name'               => __( 'Dharma Talks' ),
'singular_name'      => __( 'Dharma Talk' ),
'add_new'            => __( 'Add New Dharma Talk' ),
'add_new_item'       => __( 'Add New Dharma Talk' ),
'edit_item'          => __( 'Edit Dharma Talk' ),
'new_item'           => __( 'New Dharma Talk' ),
'all_items'          => __( 'All Dharma Talks' ),
'view_item'          => __( 'View Dharma Talk' ),
'search_items'       => __( 'Search Dharma Talks' ),
'featured_image'     => 'Poster',
'set_featured_image' => 'Add Poster'
);
// The arguments for our post type, to be entered as parameter 2 of register_post_type()
$args = array(
'labels'            => $labels,
'description'       => 'Holds our dharma talk post specific data',
'public'            => true,
'menu_position'     => 5,
'supports'          => array( 'title', 'editor', 'author', 'custom-fields', 'revisions' ),
'has_archive'       => true,
'show_in_admin_bar' => true,
'show_in_nav_menus' => true,
'query_var'         => true,
);
// Call the actual WordPress function
// Parameter 1 is a name for the post type
// Parameter 2 is the $args array
register_post_type( 'dharma-talk', $args);
}

/**
 * Register meta boxes.
 */
function dt_register_meta_boxes() {
    add_meta_box( 'dt-1', __( 'Talk Info', 'dt' ), 'dt_display_callback', 'dharma-talk' );
}
add_action( 'add_meta_boxes', 'dt_register_meta_boxes' );

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function dt_display_callback( $post ) {
    include plugin_dir_path( __FILE__ ) . './form.php';
}

/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function dt_save_meta_box( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
        $post_id = $parent_id;
    }
    $fields = [
        'dt_description',
        'dt_youtube_link',
    ];
    foreach ( $fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
     }
}
add_action( 'save_post_dharma-talk', 'dt_save_meta_box' );

/**
 * Creates default description, if needed
 *
 * @param int $post_id Post ID
 */
function dt_save_description( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
        $post_id = $parent_id;
    }
    if ( array_key_exists( "content", $_POST ) && trim($__POST['content']) !== '' ) {
        return;
    }
    $the_post = get_post($post_id);
    $author_id = $the_post->post_author;
    $the_author = get_the_author_meta( 'display_name', $author_id );
    $pretty_date = esc_html( get_the_date("l, F jS Y", $post_id) );
    $default_description = "<p>A talk given at Berkeley Zen Center on " . $pretty_date . " by " . $the_author . ".</p>";
    $data = array(
        'ID' => $post_id,
        'post_content' => $default_description
        );
    // unhook this function so it doesn't loop infinitely
    remove_action( 'save_post_dharma-talk', 'dt_save_description' );
    wp_update_post( $data );
    add_action( 'save_post_dharma-talk', 'dt_save_description' );
};
add_action( 'save_post_dharma-talk', 'dt_save_description' );

function dt_add_rss($qv) {
    if (isset($qv['feed']) && !isset($qv['post_type'])) {
        $qv['post_type'] = array('post', 'dharma-talk');
    }
    return $qv;
}
add_filter('request', 'dt_add_rss');

function dt_add_to_blog($query) {
 if ( ($query->is_author() || $query->is_home() )&& $query->is_main_query() ) {
  $query->set( 'post_type', array( 'post', 'dharma-talk' ) );
 }
}
add_action( 'pre_get_posts', 'dt_add_to_blog' );