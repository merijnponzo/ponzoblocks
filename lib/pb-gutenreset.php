<?php
//https://joseph-dickson.com/removing-specific-gutenberg-core-blocks-and-options/
//add_theme_support( 'align-wide' );
//disable custom font-sizes
add_theme_support( 'disable-custom-font-sizes' );
// disable manual font size slider and input box
add_theme_support( 'disable-custom-font-sizes' );
// disable custom colors
add_theme_support( 'disable-custom-colors' );
// remove color palette
add_theme_support( 'editor-color-palette' );
// disable title page
function remove_title_tag() {
	remove_post_type_support('page', 'title');
}
// disable gutenberg style in frront
function wps_deregister_styles() {
    wp_dequeue_style( 'wp-block-library' );
}
add_action( 'wp_print_styles', 'wps_deregister_styles', 100 );
// adds a category for the acf modules
function pb_category_ponzobuilder( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'ponzobuilder',
				'title' => __( 'Ponzobuilder', 'ponzobuilder' ),
			),
		)
	);
}
add_filter( 'block_categories', 'pb_category_ponzobuilder', 10, 2);
