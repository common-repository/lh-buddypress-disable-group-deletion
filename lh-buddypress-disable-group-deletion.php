<?php
/**
 * Plugin Name: LH Buddypress Disable Group Deletion and settings
 * Plugin URI: https://lhero.org/portfolio/lh-buddypress-disable-group-deletion/
 * Description: Disable the BuddyPress group deletion and settings by group admins and only allow site admins to delete a group
 * Version: 1.01
 * Text Domain: lh_bdgd
 * Domain Path: /languages
 * Author: Peter Shaw
 * Author URI: https://shawfactor.com
*/


if (!class_exists('LH_Buddypress_disable_group_deletion_plugin')) {
    

class LH_Buddypress_disable_group_deletion_plugin {
    
      private static $instance;
    
    
public function disable_group_delete_by_non_site_admin(){
    
    if ( ! bp_is_group() || is_super_admin() ) {
        return;
    }
 
    $parent = groups_get_current_group()->slug . '_manage';
    bp_core_remove_subnav_item( $parent, 'delete-group', 'groups' );
    bp_core_remove_subnav_item( $parent, 'group-settings', 'groups' );
    
 
 
    // BuddyPress seems to have a bug, the same screen function is used for all the sub nav in group manage
    // so above code removes the callback, let us reattach it
    // if we don't , the admin redirect will not work.
    if ( function_exists( 'groups_screen_group_admin' ) ) {
        add_action( 'bp_screens', 'groups_screen_group_admin', 2 );
    }
    
    
}

public function plugin_init(){


   // Hooks

add_action( 'groups_setup_nav', array($this, 'disable_group_delete_by_non_site_admin') ); 
    
}
    
    /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
    public static function get_instance(){
        if (null === self::$instance) {
            self::$instance = new self();
        }
 
        return self::$instance;
    }




	/**
	* Constructor
	*/
	public function __construct() {
	    
//run whatever on bp include
add_action( 'bp_include', array($this,'plugin_init'));





}

}

$lh_buddypress_disable_group_deletion_instance = LH_Buddypress_disable_group_deletion_plugin::get_instance();

}

?>