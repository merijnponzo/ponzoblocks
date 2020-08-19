<?php

//creates a gallery from post types
function pb_block_categories($categories, $post_type)
{
    $posts = new Timber\PostQuery( array(
        'query' => array(
            'post_type'     => $post_type,
            'tax_query' => $categories,
        ),
    ));
  
    return  $posts;
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