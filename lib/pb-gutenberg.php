<?php

//register the modules
function pb_register_acf_gutenberg_blocks()
{
    $modules = array(
        array(
            'module' => 'textvisual',
            'title' => 'Text & Visual',
            'description' => 'Create text & visuals',
            'icon' => 'columns',
            'script' => false,
        ),
        array(
            'module' => 'tiles',
            'title' => 'Tiles',
            'description' => 'Create tiles',
            'icon' => 'columns',
            'script' => false,
        ),
        array(
            'module' => 'contentcolumns',
            'title' => 'Content columns',
            'description' => 'Create contentcolumns',
            'icon' => 'columns',
            'script' => true,
        ),
        array(
            'module' => 'priceblocks',
            'title' => 'Priceblocks',
            'description' => 'Create priceblocks',
            'icon' => 'columns',
            'script' => false,
        ),
        array(
            'module' => 'testimonials',
            'title' => 'Testimonials',
            'description' => 'Create testimonials',
            'icon' => 'columns',
            'script' => true,
        ),
        array(
            'module' => 'quote',
            'title' => 'Quote',
            'description' => 'Create Quote vlock',
            'icon' => 'columns',
            'script' => false,
        ),
        array(
            'module' => 'banner',
            'title' => 'Banner',
            'description' => 'Create a banner',
            'icon' => 'columns',
            'script' => false,
        ),
        array(
            'module' => 'longread',
            'title' => 'Longread',
            'description' => 'Create a longread',
            'icon' => 'columns',
            'script' => false,
        ),
        array(
            'module' => 'shortcode',
            'title' => 'Shortcode',
            'description' => 'Create a shortcode',
            'icon' => 'columns',
            'script' => false,
        ),
        array(
            'module' => 'custom',
            'title' => 'Custom',
            'description' => 'Create a custom part',
            'icon' => 'columns',
            'script' => false,
        ),
        array(
            'module' => 'listitems',
            'title' => 'Listitems',
            'description' => 'Create a listitem',
            'icon' => 'columns',
            'script' => false,
        ),
        array(
            'module' => 'specialfeatures',
            'title' => 'Special features',
            'description' => 'Create specialfeatures',
            'icon' => 'columns',
            'script' => true,
        ),
        array(
            'module' => 'gallery',
            'title' => 'Gallery',
            'description' => 'Create a gallery',
            'icon' => 'columns',
            'script' => false,
        ),
        array(
            'module' => 'visualgallery',
            'title' => 'Visual with Gallery',
            'description' => 'Create a visual + gallery',
            'icon' => 'columns',
            'script' => false,
        ),
    );

    $dir = get_template_directory();

    if (function_exists('acf_register_block')) {
        // Register the hero block.
        foreach ($modules as $module) {
            $blockname = $module['module'];
            $block = array(
                'name' => $blockname,
                'module' => $blockname,
                'title' => __($module['title'], 'pb'),
                'description' => __($module['description'], 'pb'),
                'render_callback' => 'pb_twigblocks',
                'category' => 'ponzobuilder',
                'icon' => $module['icon'],
                'supports' => array('align' => false),
                'mode' => 'preview',
                'keywords' => array($module),
            );
            /* no block scripts needed (yet)
            if ($module['script']) {
            if (is_admin()) {
            $block['enqueue_script'] = plugins_url() . '/ponzobuilder/assets/blocks/' . $blockname . '.js';
            }
            }
             */
            acf_register_block_type($block);
        }
    }
}
add_action('acf/init', 'pb_register_acf_gutenberg_blocks');

function pb_twigblocks($block)
{
    //ges the module name
    $module = $block['module'];
    //get builder options
    $options = get_field($module, 'options');
    $option = array();
    $current_theme = get_field($module . 'theme');
    $ref = rand(99999, 9999);
    if (is_array($options) && !is_array($current_theme)) {
        if (array_key_exists($current_theme, $options)) {
            $option = $options[$current_theme];
        }
    }
    $vars['ref'] = $ref;
    $vars['fields'] = get_fields();
    $vars['module'] = $module;
    $vars['option'] = $option;
    Timber::render('/templates/ponzobuilder/' . $module . '.php', $vars);
}
