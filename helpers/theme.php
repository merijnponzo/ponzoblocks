<?php

//creates a gallery from post types
function pb_block_categories($block)
{
      //default
     $postlimit = 6;
     if(array_key_exists('postlimit', $block)){
        $postlimit = $block['postlimit'];
     }
   
    // categories_posttype
     if(array_key_exists('selectedcategories', $block)){
        $selectedcategories = $block['selectedcategories'];
        $terms = [];
        $taxonomies = [];
        $post_types = [];
       
        
        foreach((array) $selectedcategories as $term){
            // Posttype_Taxonomy_termId
            $values = explode('_',$term);
            if(!empty($values)){
                // create taxonomy array
                if(!array_key_exists($values[1], $taxonomies)){
                    $taxonomies[$values[1]] = array();
                }
                // with terms
                array_push($taxonomies[$values[1]], $values[2]);
                // and posttype
                array_push($post_types, $values[0]);
            }
        }
        // build a query of different taxonomies and terms
        $tax_queries = [];
        // returns only
        $operator = 'IN';
        // returns all
        if(count($post_types) > 1){
            $operator = 'OR';
        }
        foreach((array) $taxonomies as $key => $taxonomy){
            $tax_query =
                [
                    'taxonomy' => $key,
                    'field' => 'term_id',
                    'terms' => $taxonomy,
                    'operator' => $operator
                ];
            array_push( $tax_queries, $tax_query);
        }
        // do query within different post_types with different taxonomies
        $posts = new Timber\PostQuery( array(
            'query' => array(
                'post_type'     => array_unique($post_types),
                'tax_query' =>  $tax_queries,
                'posts_per_page' => $postlimit
            ),
        ));
       
    }else{
        $posts = [];
    }
    return  $posts;
}

//creates a gallery from post types
function pb_block_postsbyterm($categories, $taxonomy, $post_type)
{
    $posts = new Timber\PostQuery( array(
        'query' => array(
            'post_type'     => $post_type,
            'relation' => 'OR',
            'tax_query' => array(
                array(
                'taxonomy' => $taxonomy,
                'field' => 'slug',
                'terms' => $categories
                )
            )
        ),
    ));
    return  $posts;
}

/*
*
*  Get theme for block
*/
function pb_blockthemesingle($blockname, $current_theme){
    //get block options
    $blockthemes = get_field($blockname.'_themes', 'options');
    foreach($blockthemes as $theme){
        if(isset($theme['blocktheme']['name'])){
            if($theme['blocktheme']['name'] == $current_theme){
                return $theme['blocktheme'];
            }
        }
    }
}

// get term by parent
// {% set terms = function('pb_post_term_children', 'assortiment-category', 'leeftijd') %}
function pb_post_term_children($taxonomy_name, $term_parent){
    global $post;
    $term_parent_id = get_term_by('slug', $term_parent, $taxonomy_name);
    $terms = get_the_terms($post->ID, $taxonomy_name);
    $terms_result = [];
    foreach ($terms as $term) {
        if($term->parent === $term_parent_id->term_id) {
            array_push($terms_result,$term->name);
        }
    }
    return $terms_result;
}

// get another block, and render it
function pb_get_otherblock( $post_id ) {
    $reuse_block = get_post( $post_id );
    $reuse_block_content = apply_filters( 'the_content', $reuse_block->post_content);
    return $reuse_block_content;
}
