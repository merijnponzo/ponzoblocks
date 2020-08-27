<?php  
/*
*
*  Reset Gutenberg options
*  https://joseph-dickson.com/removing-specific-gutenberg-core-blocks-and-options/
*/
add_theme_support( 'align-wide' );
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

// adds a category for the acf modules
function PonzoblocksCategory( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'ponzoblocks',
				'title' => __( 'Ponzoblocks', 'ponzoblocks' ),
			),
		)
	);
}
add_filter( 'block_categories', 'PonzoblocksCategory', 10, 2);
