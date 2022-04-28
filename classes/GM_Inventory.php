<?php
    
    include_once GM_PLUGIN_PATH . "/classes/GM_Activate.php";
    include_once GM_PLUGIN_PATH . "/classes/GM_Deactivate.php";
    include_once GM_PLUGIN_PATH . "/classes/GM_enqueue_style_script.php";
    include_once GM_PLUGIN_PATH . "/classes/GM_Inventory_shortcodes.php";
    include_once GM_PLUGIN_PATH . "/classes/GM_Ajax_actions.php";

    class GM_Inventory
    {
        private string $plugin;
        private object $GM_Activate;
        private object $GM_Deactivate;
        private object $GM_style_script;
        private object $GM_part_search;
        private object $GM_Ajax_Actions;
        
        public function __construct( $file )
        {
            //register the plugin path
            $this->plugin = $file;
            //register the activation hooks
            $this->GM_Activate = new GM_Activate( $file );
            $this->GM_Activate->init();
            //register the deactivation hooks
            $this->GM_Deactivate = new GM_Deactivate( $file );
            $this->GM_Deactivate->init();
            //add style and script
            $this->GM_style_script = new GM_enqueue_style_script();
            $this->GM_style_script->init();
            //add shortcodes
            $this->GM_part_search = new GM_Inventory_shortcodes();
            $this->GM_part_search->init();
            //add ajax actions
            $this->GM_Ajax_Actions = new GM_Ajax_actions();
            $this->GM_Ajax_Actions->init();
        }
        
    }