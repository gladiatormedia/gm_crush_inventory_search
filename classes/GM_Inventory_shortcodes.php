<?php
    class GM_Inventory_shortcodes{
        
        public function __construct(){
        
        }
        
        public function init(){
            add_shortcode( 'part_search', array( $this, 'part_search' ) );
        }
        
        public function part_search(){
            ob_start();
            $response = file_get_contents(GM_API_URL."list-makes" );
            if( !empty( $response ) ):
                $manufacturers = json_decode( $response);
                include GM_PLUGIN_PATH.'/views/search-by-fitment.php';
            else:
                $message = "There was an error. Please try again. If problem persist please contact support.";
                include GM_PLUGIN_PATH.'/views/error.php';
            endif;
            return ob_get_clean();
            
        }
    }