<?php
/**
 * Plugin Name: GM Inventory Search
 * Plugin URI: http://gladiator-media.com
 * Description: Inventory Search Using the Crush API
 * Version: 1.1
 * Author: Gladiator Media LLC
 * Author URI: http://gladiator-media.com
 * License: GPL2
 * Requires PHP: 7.4
 */

define( "GM_PLUGIN_PATH", dirname(__FILE__ ) );

require_once GM_PLUGIN_PATH . "/common/constants.php";
require_once GM_PLUGIN_PATH . "/classes/GM_Inventory.php";
require_once GM_PLUGIN_PATH . "/admin/classes/GM_Menu_page.php";

//cue the star of the show
$GM_Inventory = new GM_Inventory( __FILE__ );

//make admin menu entry
$GM_menu_page = new GM_Menu_page();
$GM_menu_page->init();

//for the settings link on plugin page
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'gm_inventory_add_plugin_page_settings_link');

function gm_inventory_add_plugin_page_settings_link( $links ) {
    $links[] = '<a href="' .
        admin_url( 'admin.php?page=gm-inventory-search-settings.php' ) .
        '">' . __('Settings') . '</a>';
    return $links;
}