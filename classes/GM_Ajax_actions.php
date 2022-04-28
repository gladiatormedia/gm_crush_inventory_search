<?php
    class GM_Ajax_actions{
        
        public function __construct(){
        
        }
        
        public function init(){
            add_action( 'wp_ajax_manufacturer_year_list', array( $this, "gm_ajax_get_manufacturer_year_list" ) );
            add_action( 'wp_ajax_year_model_list', array( $this, "gm_ajax_get_year_model_list" ) );
            add_action( 'wp_ajax_parts_list', array( $this, "gm_ajax_get_parts_list" ) );
        }
        
        public function gm_ajax_get_manufacturer_year_list(){
            
            if( isset( $_POST ) && !empty( $_POST ) && array_key_exists( "manufacturer_code", $_POST ) && !empty( $_POST[ "manufacturer_code" ] ) ):
                
                $manufacturer_code = $_POST[ "manufacturer_code" ];
                //
                $response = file_get_contents(GM_API_URL."list-years?manufacturer_code=".$manufacturer_code );
                if( !empty( $response ) ):
                    $yearsList = json_decode( $response );
                    //
                    if( isset( $yearsList ) && is_array( $yearsList ) && count( $yearsList ) > 0 ):
                        ob_start();
                        include ( GM_PLUGIN_PATH.'/views/years-list.php' );
                        $response = ob_get_clean();
                    else:
                        $response = $this->get_error_message();
                    endif;
                    
                else:
                    $response = $this->get_error_message();
                endif;
                
            else:
                $response = $this->get_error_message();
            endif;
            
            echo $response;
            die();
            
        }
    
        public function gm_ajax_get_year_model_list(){
        
            if( isset( $_POST ) && !empty( $_POST ) && array_key_exists( "manufacturer_code", $_POST ) && !empty( $_POST[ "manufacturer_code" ] ) ):
                
                $manufacturer_code = $_POST[ "manufacturer_code" ];
                $year = ( array_key_exists( "year", $_POST ) ? $_POST[ "year" ] : "" );
                $response = file_get_contents(GM_API_URL."list-models?make=".$manufacturer_code );
                
                if( !empty( $response ) ):
                    $modelList = json_decode( $response );
                    //
                    if( isset( $modelList ) && is_array( $modelList ) && count( $modelList ) > 0 ):
                        ob_start();
                        include ( GM_PLUGIN_PATH.'/views/model-list.php' );
                        $response = ob_get_clean();
                    else:
                        $response = $this->get_error_message();
                    endif;
            
                else:
                    $response = $this->get_error_message();
                endif;
        
            else:
                $response = $this->get_error_message();
            endif;
        
            echo $response;
            die();
        
        }
    
        public function gm_ajax_get_parts_list(){
        
            if( isset( $_POST ) && !empty( $_POST ) && array_key_exists( "manufacturer_code", $_POST ) && !empty( $_POST[ "manufacturer_code" ] ) ):
            
                $manufacturer_code = $_POST[ "manufacturer_code" ];
                $year = ( array_key_exists( "year", $_POST ) ? $_POST[ "year" ] : "" );
                
                $response = file_get_contents(GM_API_URL."list-parts" );
                
                if( !empty( $response ) ):
                    $partList = json_decode( $response );
                    //
                    
                    if( isset( $partList ) && is_array( $partList ) && count( $partList ) > 0 ):
                        ob_start();
                        include ( GM_PLUGIN_PATH.'/views/part-list.php' );
                        $response = ob_get_clean();
                    else:
                        $response = $this->get_error_message();
                    endif;
            
                else:
                    $response = $this->get_error_message();
                endif;
        
            else:
                $response = $this->get_error_message();
            endif;
        
            echo $response;
            die();
        
        }
        
        public function get_error_message( $message = "There was an error. Please try again." ){
            ob_start();
            include GM_PLUGIN_PATH.'/views/error.php';
            return ob_get_clean();
        }
    }