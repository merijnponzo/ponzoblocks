<?php



add_action( 'init', 'register_testimonials' );

function register_testimonials() {
	
	//Projects
	$labels = array(
		'name'               => _x( 'testimonials', 'post type general name' ),
		'singular_name'      => _x( 'testimonials', 'post type singular name' ),
		'menu_name'          => _x( 'Testimonials', 'admin menu' ),
		'name_admin_bar'     => _x( 'testimonials', 'add new on admin bar' ),
		'add_new'            => _x( 'Nieuwe toevoegen', 'selectedby' ),
		'add_new_item'       => __( 'Nieuwe testimonials toevoegen' ),
		'new_item'           => __( 'Nieuw testimonials' ),
		'edit_item'          => __( 'Bewerk testimonials' ),
		'view_item'          => __( 'Bekijk testimonials' ),
		'all_items'          => __( 'Alle testimonials' ),
		'search_items'       => __( 'Zoek testimonials' ),
		'parent_item_colon'  => __( 'Bovenliggend testimonials:' ),
		'not_found'          => __( 'Geen testimonials gevonden.' ),
		'not_found_in_trash' => __( 'Geen testimonials gevonden.' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_nav_menus'  => true,
		'query_var'          => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'taxonomies' => array(),
		'hierarchical'       => true,
		'menu_position'      => null,
		'show_in_rest'		 => true,
		'rewrite' => array('slug' => 'testimonials'),
		'menu_icon'			 => 'dashicons-list-view',
		'supports'           => array( 'title', 'editor', 'thumbnail','page-attributes','custom-fields','excerpt')
	);
	register_post_type( 'testimonials', $args );
	
	function hide_testimonial_gutenberg( $use_block_editor, $post_type ) {
		if ( 'testimonials' === $post_type ) {
			return false;
		}
	
		return $use_block_editor;
	}
	add_filter( 'use_block_editor_for_post_type', 'hide_testimonial_gutenberg', 10, 2 );

	//TAXONOMIES
	/*
	$labels = array(
		'name' => 'leeftijd',
		'singular_name' => 'leeftijd',
		'search_items' =>  'Zoek leeftijd',
		'popular_items' => 'Veelgebruikte leeftijd',
		'all_items' => 'Alle leeftijd',
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Bewerk leeftijd' ),
		'update_item' => __( 'Update leeftijd' ),
		'add_new_item' => __( 'Nieuwe leeftijd toevoegen' ),
		'new_item_name' => __( 'Nieuwe leeftijd' ),
		'separate_items_with_commas' => __( 'Separate leeftijd with commas' ),
		'add_or_remove_items' => __( 'Toevoegen of verwijderen' ),
		'choose_from_most_used' => __( 'Kies uit meestgebruikte tags' ),
		'menu_name' => __( 'leeftijd' ),
	);

	register_taxonomy('leeftijd','testimonials',array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'show_admin_column' => true
	));
	*/	

}


/*
//QUERY VARS
function custom_query_vars_filter($vars) {
    $vars[] = 'leeftijd';
    $vars[] = 'gewicht';
    $vars[] = 'soort';
    return $vars;
  }
  add_filter( 'query_vars', 'custom_query_vars_filter' );
  
 
  //TAX QUERY VARS
  function rewrite_rules( $rules )
  {	
	$post_type = 'testimonials';
	// all possible combinations
	$createrules = [
		['leeftijd', 'gewicht', 'soort', 'page'],
		['leeftijd', 'gewicht', 'soort'],
		['leeftijd', 'gewicht', 'page'],
		['leeftijd', 'soort', 'page'],
		['gewicht', 'soort', 'page'],
		['leeftijd', 'gewicht'],
		['leeftijd', 'soort'],
		['gewicht', 'soort'],
		['gewicht','page'],
		['soort','page'],
		['leeftijd','page'],
		['gewicht'],
		['soort'],
		['leeftijd'],
		['page']
	];
	$newrules = ponzo_rulesets($post_type, $createrules);

	return $newrules + $rules;
  }
  add_action( 'rewrite_rules_array', 'rewrite_rules', 'top' );
  */

?>