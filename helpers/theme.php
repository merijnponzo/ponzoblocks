<?php
//parse acf subfield percentages to columns
function pb_colclasses($col1_width)
{
    $col1 = "col-6";
    $col2 = "col-6";
    if ($col1_width === "30") {
        $col1 = "col-4";
        $col2 = "col-8";
    } else if ($col1_width === "70") {
        $col1 = "col-8";
        $col2 = "col-4";
    } else if ($col1_width === "40") {
        $col1 = "col-5";
        $col2 = "col-7";
    } else if ($col1_width === "60") {
        $col1 = "col-7";
        $col2 = "col-5";
    }

    return array("col1" => $col1, "col2" => $col2);
}

/* deprecated
//get variants from post thumbnail
function pb_image($id,$featured,$ratio){
//replaces image id with post id's featured id
if($featured){
$id =  get_post_thumbnail_id($id);
}
$meta = wp_get_attachment_metadata($id);

$variants = array('thumbnail','medium','large');
//like acf image -> array(), matches the visual.twig
$images = array(
'ratio'=>$ratio,
'image'=>array(
'sizes'=>array()
)
);
$found = false;
if(is_array($meta)){
if(array_key_exists('sizes',$meta)){
//does this attachments has all the variants?
foreach((array)$variants as $variant){
if(array_key_exists($variant,$meta['sizes'])){
$img = wp_get_attachment_image_src($id,$variant);
if($img){
$images['image']['sizes'][$variant] = $img[0];
$found = true;
}
}
}
}
}

if(!$found){
//get the original
$img = wp_get_attachment_image_src($id,'full');
if(is_array($img)){
$images['image']['sizes']['thumbnail'] = $img[0];
//no image found
}
if(!$img){
$images['image'] = false;
}
}
return $images;
}
 */

//get variants from post thumbnail
function pb_image($id = null, $featured = false, $ratio = 'landscape')
{
    //replaces image id with post id's featured id
    if ($featured) {
        $id = get_post_thumbnail_id($id);
    }
    $meta = wp_get_attachment_metadata($id);
    $full_img = wp_get_attachment_image_src($id, 'full');
    $placeholder = null;
    //creates a placeholder image for all sizes
    if (is_array($full_img)) {
        $placeholder = $full_img[0];
    }
    $variants = array('thumbnail', 'medium', 'large');
    //like acf image -> array(), matches the visual.twig
    $images = array(
        'ratio' => $ratio,
        'meta' => $meta,
        'sizes' => array(
            'thumbnail' => $placeholder,
            'medium' => $placeholder,
            'large' => $placeholder,
        ),

    );
    //gets all available sizes from variants
    if (is_array($meta)) {
        if (array_key_exists('sizes', $meta)) {
            foreach ((array) $variants as $variant) {
                if (array_key_exists($variant, $meta['sizes'])) {
                    $img = wp_get_attachment_image_src($id, $variant);
                    if (is_array($img)) {

                        $images['sizes'][$variant] = $img[0];
                    }
                }
            }
        }
    }
    return $images;
}

function pb_image_acf($ar)
{

    $meta = [];
    $placeholder = '';

    if (is_array($ar)) {
        if (array_key_exists('ID', $ar)) {
            $meta = wp_get_attachment_metadata($ar['ID']);
            $full_img = wp_get_attachment_image_src($ar['ID'], 'full');
            if ($full_img) {
                $placeholder = $full_img[0];
            }
        }
    }
    $variants = array('thumbnail', 'medium', 'large');
    //like acf image -> array(), matches the visual.twig
    $images = array(
        'meta' => $meta,
        'sizes' => array(
            'thumbnail' => $placeholder,
            'medium' => $placeholder,
            'large' => $placeholder,
        ),

    );
    //gets all available sizes from variants
    if (is_array($ar)) {
        if (array_key_exists('sizes', $ar)) {
            foreach ((array) $variants as $variant) {
                if (array_key_exists($variant, $meta['sizes'])) {
                    if (array_key_exists($variant, $ar['sizes'])) {
                        $images['sizes'][$variant] = $ar['sizes'][$variant];
                    }
                }
            }
        }
    }
    return $images;
}

//get variants from post thumbnail
function pb_imagefull($id)
{
    $img = wp_get_attachment_image_src($id, 'full');
    $url = false;
    if (is_array($img)) {
        $url = $img[0];
    }
    return $url;
}

function pb_link($id)
{
    return get_permalink($id);
}
//count rows in repeater
function pb_countrows($rows)
{
    if (!is_array($rows)) {
        $rows = 1;
    } else {
        $rows = count($rows);
    }
    return $rows;
}
//creates a gallery from post types
function pb_gallery($gallery)
{
    //gallery = custom post type
    $the_query = new WP_Query(array(
        'post_type' => $gallery,
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    )
    );

    $posts = [];
    while ($the_query->have_posts()): $the_query->the_post();
        global $post;
        $thumbnail = get_the_post_thumbnail_url($post->ID, 'thumbnail');
        $medium = get_the_post_thumbnail_url($post->ID, 'medium');
        $visual = array('sizes' => array('thumbnail' => $thumbnail, 'medium' => $medium));
        $post_data = array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'excerpt' => $post->post_excerpt,
            'visual' => $visual,
            'permalink' => get_the_permalink(),
        );

        array_push($posts, $post_data);
    endwhile;
    return $posts;
}

function pb_visualoptions($fit = false, $size = false, $ratio = false, $maxheight = false)
{

    $visualoptions = array();
    if ($fit) {
        $visualoptions['fit'] = $fit;
    }
    if ($size) {
        $visualoptions['size'] = $size;
    }
    if ($ratio) {
        $visualoptions['ratio'] = $ratio;
    }
    if ($maxheight) {
        $visualoptions['maxheight'] = $maxheight;
    }
    return $visualoptions;
}
//creates a gallery from post types with customfield showhome => true
function pb_galleryvisual($fields)
{

    $posts = [];

    if (array_key_exists("type", $fields)) {
        $type = $fields["type"];

        //if is taxonomy selection
        if ($type === "selection") {
            /*
            $terms = get_terms( array(
            'taxonomy' => 'categorie',
            'hide_empty' => false,
            ));
             */
            $terms = [];

            if (array_key_exists("selection", $fields)) {
                $terms = $fields["selection"];
            }
            foreach ((array) $terms as $term) {
                if (property_exists($term, 'term_id')) {
                    $post_data = array(
                        'id' => $term->term_id,
                        'title' => $term->name,
                        'excerpt' => '',
                        'visual' => get_field('image', $term),
                        'permalink' => get_term_link($term->term_id),
                    );
                    array_push($posts, $post_data);
                }
            }

            //if is relationships
        } else {

            $posttype = "posts";
            if (array_key_exists("posttype", $fields)) {
                $posttype = $fields["postype"];
            }

            //gallery = custom post type
            $the_query = new WP_Query(array(
                'post_type' => $posttype,
                'posts_per_page' => 4,
                'orderby' => 'menu_order',
                'order' => 'ASC',
                //get cpt's with metakey home
                'meta_query' => array(
                    'meta_key' => 'showhome',
                    'meta_value' => 0,
                ),
            )
            );

            while ($the_query->have_posts()): $the_query->the_post();
                global $post;
                $thumbnail = get_the_post_thumbnail_url($post->ID, 'thumbnail');
                $medium = get_the_post_thumbnail_url($post->ID, 'medium');
                $visual = array('sizes' => array('thumbnail' => $thumbnail, 'medium' => $medium));
                $post_data = array(
                    'id' => $post->ID,
                    'title' => $post->post_title,
                    'excerpt' => $post->post_excerpt,
                    'visual' => $visual,
                    'permalink' => get_the_permalink(),
                );

                array_push($posts, $post_data);
            endwhile;

        }
    }

    return $posts;
}
