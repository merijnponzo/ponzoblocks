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
*  Add theme icons from Ponzoblocks to theme_icons select dropdown
*  pb_theme_icons > to > theme_icons
*/
function pb_addthemeicons()
{
    if (is_admin()) {
        // add icons to select dropdown
        add_filter('acf/load_field/name=theme_icons' , function ($field) {
            // get theme icons from ponzoblocks textarea
            $theme_icon_text = get_field('pb_theme_icons', 'option');
            $theme_icons =  explode ( '<br />' ,$theme_icon_text );
            $selectvalues = [];
            $selectvalues['none'] = 'none';
            // check for icons with minimal '.svg' length as extension
            foreach ((array) $theme_icons as $icon) {
                if(strlen($icon) > 4){
                    $file = trim(str_replace('.svg','',$icon));
                    $selectvalues[$file] = $file;
                }
            }
            // populate options
            $field['choices'] = $selectvalues;
            // set default value
            $theme_icons_default = get_field('pb_theme_icons_default', 'option');
            $field['default_value'] = $theme_icons_default ;
            return $field;
        });
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
    add_action('acf/init','pb_addthemeicons');
}

