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
