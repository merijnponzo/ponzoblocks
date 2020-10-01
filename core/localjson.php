<?php 

namespace Ponzoblocks\core;

new BlocksLocalJson();
  
class BlocksLocalJson {
    
    private $groups = [];

    public function __construct() {
        foreach((array) Blocks::getBlocks() as $block){
            if(array_key_exists('group', $block)) {
                array_push( $this->groups, $block['group']);
            }
        }
      // add fitler before acf saves a group
      add_action('acf/update_field_group', array($this, 'update_field_group'), 1, 1);
    } // end public function __construct
    
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
