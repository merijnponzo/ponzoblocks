<?php

//creates a gallery from post types
function pb_block_categories($block)
{
    // button group posttype
    $posttype = $block['posttype'];
    // categories_posttype
    if(array_key_exists('categories_'.$posttype, $block)){
        $taxonomies = $block['categories_'.$posttype];
        $terms = [];
        foreach((array) $taxonomies as $taxonomy){
            // should match the term_object within acf taxonomy
            if(is_object($taxonomy)){
                array_push($terms,  $taxonomy->term_id);
                // get the taxonomy name
                $taxonomy = $taxonomy->taxonomy;
            }else{
                print_r('create a term_object within acf taxonomy instead of term_id');
            }
        }
        $taxquery = [
            [
                'taxonomy' => $taxonomy,
                'field' => 'term_id',
                'terms' => $terms,
                'operator' => 'IN'
            ]
        ];
       
        $posts = new Timber\PostQuery( array(
            'query' => array(
                'post_type'     => $posttype,
                'tax_query' =>  $taxquery,
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
