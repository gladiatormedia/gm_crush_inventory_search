<?php
    class GM_Menu_page{
        
        public function __construct(){
        
        }
        
        public function init(){
            add_action( 'admin_menu', array( $this, 'gm_inventory_search_page' ) );
        }
        
        public function gm_inventory_search_page(){
            add_menu_page( 'Gladiator Media Inventory Search', 'Inventory Search', 'manage_options', 'gm-inventory-search-settings.php', array( $this, 'gm_inventory_search_admin_page' ), 'dashicons-search', 6  );
        }
        
        public function gm_inventory_search_admin_page(){
            ob_start();
            include GM_PLUGIN_PATH.'/admin/views/settings.php';
            $result = ob_get_clean();
            echo $result;
        }
    }