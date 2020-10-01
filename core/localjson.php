<?php 

namespace Ponzoblocks\core;

new BlocksLocalJson();

// https://support.advancedcustomfields.com/forums/topic/is-it-possible-to-have-local-json-files-in-both-theme-and-plugin-folders/

class BlocksLocalJson {
    
    private $groups = [
        // blocktheme layout
        'group_5f2a6bea9d498',
        // pb heading
        'group_5c4863169cc9b',
        // pb link
        'group_5c3dd9fe0dc42',
        // pb theme options
        'group_5c51b38ae0193',
        // ponzoblocks
        'group_5c48d31ccd99c',
        // pb visualoptions
        'group_5c6bdcecdb96f',
        // pb visual
        'group_5c41f6fb4becf'

    ];

    public function __construct() {
        foreach((array) Blocks::getBlocks() as $block){
            if(array_key_exists('group', $block)) {
                array_push( $this->groups, $block['group']);
            }
        }
        // add filter before acf saves a group
        add_action('acf/update_field_group', array($this, 'update_field_group'), 1, 1);
        // Load - includes the /acf-json folder in this plugin to the places to look for ACF Local JSON files
        add_filter('acf/settings/load_json', function($paths) {
            $paths[] = dirname(plugin_dir_path(__FILE__)) . '/acf-json';
            return $paths;
        });
    }

    public function update_field_group($group) {
      // called when ACF save the field group to the DB
      if (in_array($group['key'], $this->groups)) {
        // if it is one of my groups then add a filter on the save location
        // high priority to make sure it is not overrridded, I hope
        add_filter('acf/settings/save_json',  array($this, 'override_location'), 9999);
      }
      return $group;
    } // end public function update_field_group
    
    public function override_location($path) {
      // remove this filter so it will not effect other goups
      remove_filter('acf/settings/save_json',  array($this, 'override_location'), 9999);
      // override save path
      $path = dirname(plugin_dir_path(__FILE__)).'/acf-json';
      return $path;
    } // end public function override_json_location
    
    
}
