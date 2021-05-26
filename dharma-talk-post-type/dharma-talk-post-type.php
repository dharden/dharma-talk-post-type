<?php
/**
 * Plugin Name: Dharma Talk Post Type
 * Description: Add post types for dharma talks
 * Author: Dan Harden
 *
 * @package BZC
 */

// Hook <strong>custom_post_dharma_talk()</strong> to the init action hook.
add_action( 'init', 'bzc_custom_post_dharma_talk' );
/** The custom function to register a dharma talk post type.
 */
function bzc_custom_post_dharma_talk() {
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
		'featured_image'     => 'Featured Image',
		'set_featured_image' => 'Add Featured Image',
	);
	$args   = array(
		'labels'            => $labels,
		'description'       => 'Holds our dharma talk post specific data',
		'public'            => true,
		'menu_position'     => 5,
		'supports'          => array( 'title', 'editor', 'author', 'custom-fields', 'revisions', 'thumbnail' ),
		'has_archive'       => true,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'query_var'         => true,
		'rewrite' => array('slug' => 'dharma-talk','with_front' => false),
	);
	register_post_type( 'dharma-talk', $args );
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
 * @param int $post_id Post ID.
 */
function dt_save_meta_box( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	$parent_id = wp_is_post_revision( $post_id );
	if ( $parent_id ) {
		$post_id = $parent_id;
	}
	$fields = array(
		'dt_youtube_link',
	);
	foreach ( $fields as $field ) {
		if ( array_key_exists( $field, $_POST ) ) {
			update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}
}
add_action( 'save_post_dharma-talk', 'dt_save_meta_box' );

/**
 * Creates default description, if needed
 *
 * @param int $post_id Post ID.
 */
function dt_save_description( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	$parent_id = wp_is_post_revision( $post_id );
	if ( $parent_id ) {
		$post_id = $parent_id;
	}
	if ( array_key_exists( 'content', $_POST ) && trim( wp_unslash( $_POST['content'] ) ) !== '' ) {
		return;
	}
	$the_post            = get_post( $post_id );
	$author_id           = $the_post->post_author;
	$the_author          = get_the_author_meta( 'display_name', $author_id );
	$pretty_date         = esc_html( get_the_date( 'l, F jS Y', $post_id ) );
	$default_description = '<p>A talk given at Berkeley Zen Center on ' . $pretty_date . ' by ' . $the_author . '.</p>';
	$data                = array(
		'ID'           => $post_id,
		'post_content' => $default_description,
	);
	// unhook this function so it doesn't loop infinitely.
	remove_action( 'save_post_dharma-talk', 'dt_save_description' );
	wp_update_post( $data );
	add_action( 'save_post_dharma-talk', 'dt_save_description' );
};
add_action( 'save_post_dharma-talk', 'dt_save_description' );

/**
 * Add dharma-talk to rss
 *
 * @param qv $qv Current query value.
 */
function dt_add_rss( $qv ) {
	if ( isset( $qv['feed'] ) && ! isset( $qv['post_type'] ) ) {
		$qv['post_type'] = array( 'post', 'dharma-talk' );
	}
	return $qv;
}
add_filter( 'request', 'dt_add_rss' );

/**
 * Add dharma-talk to posts
 *
 * @param WP_Query $query Current query object.
 */
function dt_add_to_blog( $query ) {
	if ( ( $query->is_author() || $query->is_home() ) && $query->is_main_query() ) {
		$query->set( 'post_type', array( 'post', 'dharma-talk' ) );
	}
}
add_action( 'pre_get_posts', 'dt_add_to_blog' );

/**
 * Add dharma-talk stylesheet
 */
function dt_enqueue_style() {
	$plugin_url = plugin_dir_url( __FILE__ );
	$plugin_dir = plugin_dir_path( __FILE__ );
	$file_name  = 'dharma-talk.css';
	wp_enqueue_style( 'dharma-talk-plugin-style', $plugin_url . '/' . $file_name, array(), filemtime( $plugin_dir . '/' . $file_name ), false );
}
add_action( 'wp_enqueue_scripts', 'dt_enqueue_style', PHP_INT_MAX );

/**
 * Add default preview image to jetpack
 *
 * @param Media $media the media attached to a post.
 * @param int   $post_id Post ID.
 * @param args  $args additional arguments.
 */
function dt_jetpack_custom_image( $media, $post_id, $args ) {
	if ( $media ) {
		return $media;
	} else {
		$permalink  = get_permalink( $post_id );
		$the_post   = get_post( $post_id );
		$author_id  = $the_post->post_author;
		$thumbnail_url = dt_get_youtube_thumbnail ( $post_id );
		if (function_exists('get_wpupa_url')) $avatar_url = get_wpupa_url($author_id, ['size' => 128]);
		$site_logo = wp_get_attachment_url( get_theme_mod( 'custom_logo' ) );
		$custom_image_url = $thumbnail_url ? $thumbnail_url : $site_logo;
		$url        = apply_filters( 'jetpack_photon_url', $custom_image_url );
		return array(
			array(
				'type' => 'image',
				'from' => 'custom_fallback',
				'src'  => esc_url( $url ),
				'href' => $permalink,
			),
		);
	}
}
add_filter( 'jetpack_images_get_images', 'dt_jetpack_custom_image', 10, 3 );

function dt_get_youtube_link ( $post_id ) {
	if ( !$post_id ) return;
	return get_post_meta( $post_id, 'dt_youtube_link', true );
}

function dt_get_youtube_thumbnail ( $post_id ) {
	$youtube_link = dt_get_youtube_link( $post_id );
	if ( !$youtube_link ) return;
	parse_str( parse_url( $youtube_link, PHP_URL_QUERY ), $querystring );
	return 'https://img.youtube.com/vi/' . $querystring['v'] . '/maxresdefault.jpg';
}

/**
 * Add og tags
 */
function dt_add_meta_tags() {
	$post_id      = get_queried_object_id();
	$youtube_link = dt_get_youtube_link( $post_id );
	if ( $youtube_link ) {
		parse_str( parse_url( $youtube_link, PHP_URL_QUERY ), $querystring );
		echo '<meta name="twitter:card" content="summary">';
		echo '<meta name="twitter:player" content="' . $youtube_link . '">';
		echo '<meta name="og:video" content="' . $youtube_link . '">';
	}
}
add_action( 'wp_head', 'dt_add_meta_tags' );