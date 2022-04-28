<?php

    class GM_Deactivate
    {
        private string $plugin;
        
        public function __construct( string $file )
        {
            $this->plugin = $file;
        }
        
        public function init(): void
        {
            register_activation_hook( $this->plugin, array( $this, 'deactivate' ) );
        }
        
        public function deactivate(): void
        {
            //remove the tables
            //$this->drop_stores_table();
        }
        
        private function drop_stores_table(): void
        {
            global $wpdb;
            $table_name = GM_PLUGIN_PREFIX . "locations1";
            $table_query = "DROP TABLE IF EXISTS " . $table_name;
            $wpdb->query( $table_query );
        }
        
        
    }