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
                // get the rows, from ponzoblocks repeaters
                $rows = get_field($blocktheme.'_themes', 'option');
                $selectvalues = array();
                foreach ((array) $rows as $row) {
                    if(!empty($row)){
                        // get the cloned blocktheme group within this repeater row
                        if(array_key_exists('blocktheme',$row)){
                            if(isset($row['blocktheme']['name'])){
                                array_push($selectvalues, $row['blocktheme']['name']);
                            }
                        }
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

