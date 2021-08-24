<?php

/**
 * Registers the `dt_collection` taxonomy,
 * for use with 'dharma-talk'.
 */
function dt_collection_init() {
	register_taxonomy( 'dt-collection', [ 'dharma-talk', ], [
		'hierarchical'          => false,
		'public'                => true,
		'show_in_nav_menus'     => true,
		'show_ui'               => true,
		'show_admin_column'     => false,
		'query_var'             => true,
		'rewrite'               => true,
		'capabilities'          => [
			'manage_terms' => 'edit_posts',
			'edit_terms'   => 'edit_posts',
			'delete_terms' => 'edit_posts',
			'assign_terms' => 'edit_posts',
		],
		'labels'                => [
			'name'                       => __( 'Collections', 'dt-collection' ),
			'singular_name'              => _x( 'Collection', 'taxonomy general name', 'dt-collection' ),
			'search_items'               => __( 'Search Collections', 'dt-collection' ),
			'popular_items'              => __( 'Popular Collections', 'dt-collection' ),
			'all_items'                  => __( 'All Collections', 'dt-collection' ),
			'parent_item'                => __( 'Parent Collection', 'dt-collection' ),
			'parent_item_colon'          => __( 'Parent Collection:', 'dt-collection' ),
			'edit_item'                  => __( 'Edit Collection', 'dt-collection' ),
			'update_item'                => __( 'Update Collection', 'dt-collection' ),
			'view_item'                  => __( 'View Collection', 'dt-collection' ),
			'add_new_item'               => __( 'Add New Collection', 'dt-collection' ),
			'new_item_name'              => __( 'New Collection', 'dt-collection' ),
			'separate_items_with_commas' => __( 'Separate Collections with commas', 'dt-collection' ),
			'add_or_remove_items'        => __( 'Add or remove Collections', 'dt-collection' ),
			'choose_from_most_used'      => __( 'Choose from the most used Collections', 'dt-collection' ),
			'not_found'                  => __( 'No Collections found.', 'dt-collection' ),
			'no_terms'                   => __( 'No Collections', 'dt-collection' ),
			'menu_name'                  => __( 'Collections', 'dt-collection' ),
			'items_list_navigation'      => __( 'Collections list navigation', 'dt-collection' ),
			'items_list'                 => __( 'Collections list', 'dt-collection' ),
			'most_used'                  => _x( 'Most Used', 'dt-collection', 'dt-collection' ),
			'back_to_items'              => __( '&larr; Back to Collections', 'dt-collection' ),
		],
		'show_in_rest'          => true,
		'rest_base'             => 'dt-collection',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
	] );

}

add_action( 'init', 'dt_collection_init' );

/**
 * Sets the post updated messages for the `dt_collection` taxonomy.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `dt_collection` taxonomy.
 */
function dt_collection_updated_messages( $messages ) {

	$messages['dt-collection'] = [
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Collection added.', 'dt-collection' ),
		2 => __( 'Collection deleted.', 'dt-collection' ),
		3 => __( 'Collection updated.', 'dt-collection' ),
		4 => __( 'Collection not added.', 'dt-collection' ),
		5 => __( 'Collection not updated.', 'dt-collection' ),
		6 => __( 'Collections deleted.', 'dt-collection' ),
	];

	return $messages;
}

add_filter( 'term_updated_messages', 'dt_collection_updated_messages' );
