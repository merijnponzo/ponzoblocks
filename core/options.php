<?php
use Ponzoblocks\core\Blocks as Blocks;
/*
*
*  Get themes for current block
*  by populating theme block options, (from options->ponzoblocks admin)
*  into dropdown select within block (acf field 'textvisualtheme')
*/
function pb_addblockthemes()
{
    if (is_admin()) {
        foreach ((array) Blocks::getBlocks() as $block) {
            // get theme select dropdown within ponzoblock
            add_filter('acf/load_field/name=' . $block['name'].'theme' , function ($field) {
                // get original name with replacing theme for ''
                $blocktheme = str_replace('theme','', $field['name']);
                // get the options, from ponzoblocks options repeaters
                $options = get_field($blocktheme, 'option');
                $selectvalues = array();
                foreach ((array) $options as $option) {
                    if(!empty($option)){
                        array_push($selectvalues, $option['name']);
                    }
                }
                // populate options
                $field['choices'] = $selectvalues;
                return $field;
            });
        }
    }
}
/*
*
*  Adds the ACF ponzoblocks options to the admin
*/
// add ponzoblocks setup to wp-admin
acf_add_options_page(array(
    'page_title' => 'Ponzoblocks',
    'menu_title' => 'Ponzoblocks',
    'menu_slug' => 'theme-builder-settings',
    'icon_url' => 'dashicons-editor-table',
    'capability' => 'edit_posts',
));

if(is_admin()){
    add_action('acf/init','pb_addblockthemes');
}

