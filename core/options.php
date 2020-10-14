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
*  Add taxonomies and categories to the links module
*/
function pb_addfilterlinks()
{
    if (is_admin()) {
        // add icons to select dropdown
        add_filter('acf/load_field/name=filterlink' , function ($field) {
            $terms  = get_terms(['hide_empty' => false, 'orderby' => 'name', 'order' => 'DESC' ]);
            $selectvalues = [];
           
            foreach ((array) $terms as $term) {
                $post_type = get_taxonomy( $term->taxonomy );
                if(is_object($post_type)){                 
                    if(isset($post_type->object_type[0])){
                        $post_type_value =  $post_type->object_type[0];
                        if($post_type_value !== 'nav_menu_item'){
                            if($term->taxonomy !== 'post_tag'){
                                $link = get_term_link($term->term_id);
                                $selectvalues[$link] =  $link;
                            }
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

/*
*
*  Add taxonomies and categories to the links module
*/
function pb_selectedcategories()
{
    if (is_admin()) {
        // add icons to select dropdown
        add_filter('acf/load_field/name=selectedcategories' , function ($field) {
            $terms  = get_terms(['hide_empty' => false, 'orderby' => 'name', 'order' => 'ASC' ]);
            $selectvalues = [];
            foreach ((array) $terms as $term) {
                $post_type = get_taxonomy( $term->taxonomy );
                if(is_object($post_type)){                 
                    if(isset($post_type->object_type[0])){
                        $post_type_value =  $post_type->object_type[0];
                        
                        if($post_type_value !== 'nav_menu_item'){
                            // store post_type and term_id
                            if($term->taxonomy !== 'post_tag'){
                                $value = $post_type_value.'_'.$term->taxonomy.'_'.$term->term_id;
                                $selectvalues[$value] =  $term->name . ' (<strong>'.$term->taxonomy.'</strong> '.$post_type_value .')';
                            }
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
    add_action('acf/init','pb_addfilterlinks');
    add_action('acf/init','pb_selectedcategories');
}

