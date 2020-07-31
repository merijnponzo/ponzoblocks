<?php


function get_pb_blocks(){
    $blocks = array(
        array(
            'name' => 'textvisual',
            'title' => 'Text & Visual',
            'description' => 'Create text & visuals',
            'icon' => 'columns',
            'script' => false,
        ),
        array(
            'name' => 'tiles',
            'title' => 'Tiles',
            'description' => 'Create tiles',
            'icon' => 'columns',
            'script' => false,
        ),
        array(
            'name' => 'contentcolumns',
            'title' => 'Content columns',
            'description' => 'Create contentcolumns',
            'icon' => 'columns',
            'script' => true,
        ),
        array(
            'name' => 'priceblocks',
            'title' => 'Priceblocks',
            'description' => 'Create priceblocks',
            'icon' => 'columns',
            'script' => false,
        ),
        array(
            'name' => 'testimonials',
            'title' => 'Testimonials',
            'description' => 'Create testimonials',
            'icon' => 'columns',
            'script' => true,
        ),
        array(
            'name' => 'quote',
            'title' => 'Quote',
            'description' => 'Create Quote vlock',
            'icon' => 'columns',
            'script' => false,
        ),
        array(
            'name' => 'banner',
            'title' => 'Banner',
            'description' => 'Create a banner',
            'icon' => 'columns',
            'script' => false,
        ),
        array(
            'name' => 'longread',
            'title' => 'Longread',
            'description' => 'Create a longread',
            'icon' => 'columns',
            'script' => false,
        ),
        array(
            'name' => 'shortcode',
            'title' => 'Shortcode',
            'description' => 'Create a shortcode',
            'icon' => 'columns',
            'script' => false,
        ),
        array(
            'name' => 'custom',
            'title' => 'Custom',
            'description' => 'Create a custom part',
            'icon' => 'columns',
            'script' => false,
        ),
        array(
            'name' => 'listitems',
            'title' => 'Listitems',
            'description' => 'Create a listitem',
            'icon' => 'columns',
            'script' => false,
        ),
        array(
            'name' => 'specialfeatures',
            'title' => 'Special features',
            'description' => 'Create specialfeatures',
            'icon' => 'columns',
            'script' => true,
        ),
        array(
            'name' => 'gallery',
            'title' => 'Gallery',
            'description' => 'Create a gallery',
            'icon' => 'columns',
            'script' => false,
        ),
        array(
            'name' => 'visualgallery',
            'title' => 'Visual with Gallery',
            'description' => 'Create a visual + gallery',
            'icon' => 'columns',
            'script' => false,
        ),
    );
    return $blocks;
}
//register the blocks
function pb_register_acf_gutenberg_blocks()
{
    
    $blocks = get_pb_blocks();
    $dir = get_template_directory();
    if (function_exists('acf_register_block')) {
 
        // Register the hero block.
        foreach ($blocks as $block) {
            $blockname = $block['name'];
            $block = array(
                'name' => $blockname,
                'block' => $blockname,
                'title' => __($block['title'], 'pb'),
                'description' => __($block['description'], 'pb'),
                'render_callback' => 'pb_render_block',
                'category' => 'ponzobuilder',
                'icon' => $block['icon'],
                'supports' => array('align' => false),
                'mode' => 'preview',
                'keywords' => array($block),
            );
            // get the registered status from block options
            $registered = get_field('blocks_'.$block['name'],'options');
            if($registered){
                acf_register_block_type($block);
            }
        }
    }
   
}
add_action('acf/init', 'pb_register_acf_gutenberg_blocks');


/**
 * Adds AcfGroup blockThemes to the design dropdown
 *
 * @return string The PHP code
 */
function setBlockThemes()
{

    if (is_user_logged_in()) {
        $blocks = get_pb_blocks();
      
        foreach ((array) $blocks as $block) {
            // get theme select dropdown within ponzoblock
            add_filter('acf/load_field/name=' . $block['name'].'theme' , function ($field) {
                // get original name with replacing theme for ''
                $blocktheme = str_replace('theme','', $field['name']);
                // get the options, from ponzoblocks options repeaters
                $options = get_field($blocktheme, 'option');
                $selectvalues = array();
                foreach ((array) $options as $option) {
                    array_push($selectvalues, $option['name']);
                }
                // populate options
                $field['choices'] = $selectvalues;
                return $field;
            });
        }
        ;
    }
}
add_action('acf/init', 'setblockThemes');


function pb_render_block( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();
    // Acf fields
    $context['block'] = get_fields();
    $context['content'] = $content;
    // Blockmeta
    $context['blockmeta'] = $block;
   // $is_preview
    $context['is_preview'] = $is_preview;
    // Render the block.
    Timber::render( 'textvisual.twig', $context );
}

