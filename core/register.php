<?php
use Ponzoblocks\core\Blocks as Blocks;
/*
*
*  Get themes for current block
*  by populating theme block options, (from options->ponzoblocks admin)
*  into dropdown select within block (acf field 'textvisualtheme')
*/
function pb_blocktheme($blockname, $formatname = false){
    // format name if the blockname has pb-
    if($formatname){
        $blockname = str_replace('pb-', '', $blockname);
    }
    
    //get block options
    $options = get_field($blockname.'_themes', 'options');
    $option = array();
    $current_theme = get_field($blockname . 'theme');
    
    if(is_array($options)){
        if(isset($options[$current_theme]['blocktheme'])){
            $option = $options[$current_theme]['blocktheme'];
        }
        // add other options as well (except the blocktheme clone)
        if(isset($options[$current_theme])){
            foreach((array) $options[$current_theme] as $p => $other_option){
                if($p !== 'blocktheme'){
                    $option[$p] = $other_option;
                }
            }
        }
    }
    return $option;
}

/*
*
* Allow these blocks + ponzoblocks
*/
function pb_allowedblocks(){
    // allowed core blocks
    $allowed_blocks = array( 
        'core/block',
        'core/template',
        /*
        'core/columns',
        'core/image',
        'core/text'
        */
    );
    //add them to allowed blocks
    foreach((array) Blocks::getBlocks() as $block){
        //with acf prefix
        array_push( $allowed_blocks, 'acf/'.$block['slug']);
    }
    return $allowed_blocks;
}
/*
*
* Register the blocks as ponzoblocks
*/
function pb_registerblocks()
{
    if (function_exists('acf_register_block')) {
        // Register the hero block.
        foreach (Blocks::getBlocks() as $block) {
            $blockname = $block['name'];
            $block = array(
                'name' => $blockname,
                'block' => $blockname,
                'slug' => $blockname,
                'title' => __($block['title'], 'pb'),
                'description' => __($block['description'], 'pb'),
                'render_callback' => 'pb_blockrender',
                'category' => 'ponzoblocks',
                'icon' => $block['icon'],
                'keywords' => array($block),
                /*
                'supports' => array(
                    'jsx' => true,
                    'mode' => false,
                ),
                */
            );
            // get the registered status from block options
            $registered = get_field('blocks_'.$block['name'],'options');
            if($registered){
                acf_register_block_type($block);
            }
        }
    }
}


/*
*
* Render with timber
*/
function pb_blockrender( $block) {
   
    $context = Timber::context();
    $blockname = $block['slug'];
    $blocktheme = pb_blocktheme($blockname, false);
    // Add blockname
    $context['blockname'] = $blockname;
    // Acf fields
    $context['block'] = get_fields();
    // Add options
    $context['blocktheme'] = $blocktheme;
    // Set default template from plugin folder
    $twigtemplate = '@ponzoblocks/'.$blockname.'.twig';
    if(isset($blocktheme['customtemplate'])) {
        if(strlen($blocktheme['customtemplate']) > 2){
            // within views/blocks folder in wordpress theme
            $twigtemplate = $blocktheme['customtemplate'];
        }
    }
    Timber::render($twigtemplate, $context );
}
// allowed blocktypes
add_filter('allowed_block_types', 'pb_allowedblocks');
add_action('acf/init','pb_registerblocks');