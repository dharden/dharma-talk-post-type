<?php

// Add site logo
add_theme_support( 'custom-logo', array(
    'height'               => 300,
    'width'                => 573,
    'flex-height'          => false,
    'flex-width'           => true,
) );

add_action( 'wp_enqueue_scripts', 'twentyfourteen_parent_theme_enqueue_styles' );

/**
 * Enqueue scripts and styles.
 */
function twentyfourteen_parent_theme_enqueue_styles() {
	wp_enqueue_style( 'twentyfourteen-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'shizuka-style',
		get_stylesheet_directory_uri() . '/style.css',
		[ 'twentyfourteen-style' ]
	);
}

// CVPlugin - Fix bug of Collapsible List
add_action( 'wp_footer', 'my_custom_js' );
function my_custom_js() {
?>
<script>
	jQuery( document ).ready(function($) {
		// Remove Boostrap attributes
		$('.pt-cv-view .panel-title').removeAttr('data-toggle');
		$('.panel-collapse').removeClass('collapse');
		$('.panel-collapse').hide();

		// Do toggle manually
		$('.pt-cv-view .panel-title').on('click', function(e){
			e.preventDefault();
			
			var $icon = $(this).next('span').children('i');
			var $collapse = $(this).parent().next('.panel-collapse');
			// Hide showing item
			if($collapse.is(':visible')) {				
				$collapse.fadeOut(100);
				// Show Plus icon
				$icon.removeClass('glyphicon-minus').addClass('glyphicon-plus');
			} else {			
				// Hide other items
				$(this).closest('.pt-cv-collapsible').find('.panel-collapse').fadeOut(100);
				// Toggle display this item			
				$collapse.animate({height: "toggle"}, 300);
				// Show Minus icon
				$icon.removeClass('glyphicon-plus').addClass('glyphicon-minus');
			}
		});
	});
</script>			
<?php
}
include get_stylesheet_directory() . '/taxonomies/dt-collection.php';
