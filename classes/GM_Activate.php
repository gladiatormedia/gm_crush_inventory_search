<?php
    
    class GM_Activate
    {
        private string $plugin;
        private string $collation;
        
        public function __construct( string $file ){
            $this->plugin = $file;
            $this->get_charset_collation();
        }
        
        public function init(): void
        {
            register_activation_hook( $this->plugin, array( $this, 'activate' ) );
        }
        
        public function activate(): void
        {
            //build the tables
            //$this->add_stores_table();
            $this->add_list_table();
//            $this->add_manufacturer_table();
//            $this->add_years_table();
//            $this->add_models_table();
        }
        
        private function get_charset_collation(): void
        {
            global $wpdb;
            $this->collation = $wpdb->get_charset_collate();
        }
        
        private function add_stores_table(): void
        {
            try
            {
                global $wpdb;
                $table_name = GM_PLUGIN_PREFIX . "locations1";

                $table_query = "CREATE TABLE $table_name (
                  id int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  number int(6) NOT NULL,
                  name varchar(10) NOT NULL,
                  state varchar(2) NOT NULL);";

                require_once( ABSPATH . 'wp-admin/includes/upgrade.php');

                if( function_exists( "dbDelta" ) ):
                    dbDelta( $table_query );
                else:
                    throw new Exception("dbDelta does NOT exist");
                endif;
            
            } catch( Exception $e ){
                echo "<pre>";
                var_dump( $e->getMessage() );
                echo "</pre>";
                exit();
            }
        }
        
        private function add_list_table(): void
        {
            try
            {
                global $wpdb;
                $table_name = GM_PLUGIN_PREFIX . "IndexListApp";

//                $table_query = "CREATE TABLE $table_name (
//                  id int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
//                  number int(6) NOT NULL,
//                  name varchar(10) NOT NULL,
//                  state varchar(2) NOT NULL);";
    
                $table_query = "CREATE TABLE $table_name (
                IndexListId int(11) NOT NULL,
                SeqNbr smallint(6) NOT NULL,
                TreeLevel smallint(6) NOT NULL,
                Application varchar(255) DEFAULT NULL,
                PartType char(3) DEFAULT NULL,
                IntchNbr char(5) DEFAULT NULL,
                AlphaExtension char(1) DEFAULT NULL,
                LRFlag char(1) DEFAULT NULL,
                InterchangeNumber varchar(12) DEFAULT NULL
                )";

//                INSERT INTO `IndexListApp` (`IndexListId`, `SeqNbr`, `TreeLevel`, `Application`, `PartType`, `IntchNbr`, `AlphaExtension`, `LRFlag`, `InterchangeNumber`) VALUES

                require_once( ABSPATH . 'wp-admin/includes/upgrade.php');

                if( function_exists( "dbDelta" ) ):
                    dbDelta( $table_query );
                else:
                    throw new Exception("dbDelta does NOT exist");
                endif;

            } catch( Exception $e ){
                echo "<pre>";
                var_dump( $e->getMessage() );
                echo "</pre>";
                exit();
            }
            
        }
    
        private function add_manufacturer_table(): void
        {
            try
            {
                global $wpdb;
                $table_name = GM_PLUGIN_PREFIX . "manufacturer";
                
            
                $table_query = "CREATE TABLE $table_name (
                id int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                manufacturer_code varchar(12) NOT NULL,
                manufacturer_name varchar(255) NOT NULL
                )";
                
            
                require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
            
                if( function_exists( "dbDelta" ) ):
                    dbDelta( $table_query );
                else:
                    throw new Exception("dbDelta does NOT exist");
                endif;
            
            } catch( Exception $e ){
                echo "<pre>";
                var_dump( $e->getMessage() );
                echo "</pre>";
                exit();
            }
        
        }
    
        private function add_years_table(): void
        {
            try
            {
                global $wpdb;
                $table_name = GM_PLUGIN_PREFIX . "years";
            
            
                $table_query = "CREATE TABLE $table_name (
                id int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                manufacturer_id INT(12) NOT NULL,
                year varchar(10) NOT NULL
                )";
            
            
                require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
            
                if( function_exists( "dbDelta" ) ):
                    dbDelta( $table_query );
                else:
                    throw new Exception("dbDelta does NOT exist");
                endif;
            
            } catch( Exception $e ){
                echo "<pre>";
                var_dump( $e->getMessage() );
                echo "</pre>";
                exit();
            }
        
        }
    
        private function add_models_table(): void
        {
            try
            {
                global $wpdb;
                $table_name = GM_PLUGIN_PREFIX . "models";
            
            
                $table_query = "CREATE TABLE $table_name (
                id int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                manufacturer_id INT(12) NOT NULL,
                model varchar(255) NOT NULL
                )";
            
            
                require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
            
                if( function_exists( "dbDelta" ) ):
                    dbDelta( $table_query );
                else:
                    throw new Exception("dbDelta does NOT exist");
                endif;
            
            } catch( Exception $e ){
                echo "<pre>";
                var_dump( $e->getMessage() );
                echo "</pre>";
                exit();
            }
        
        }
        
        private function get_memory_size(): int
        {
            $memory_size_string = ini_get( 'memory_limit');
    
            //check if there is a letter in the string
            if( is_numeric( $memory_size_string ) ):
                $memory_size = $memory_size_string;
            else:
                //get the last letter
                $memory_size_letter = strtolower( substr( $memory_size_string, -1 ) );
                $memory_size_int = substr( $memory_size_string, 0, -1 );
                switch(  $memory_size_letter ):
                case 'm':
                    $memory_size = $memory_size_int * 1048576;
                    break;
                case 'k':
                    $memory_size = $memory_size_int * 1024;
                    break;
                case 'g':
                    $memory_size = $memory_size_int * 1073741824;
                    break;
                endswitch;
            endif;
            
            return (int) $memory_size;
        }
        
        private function seed_list_table_data()
        {
            $memory_size = $this->get_memory_size() / 2;
            echo "<pre>";
            var_dump( $memory_size );
            echo "</pre>";
            $file_handle = fopen( GM_PLUGIN_INDEXLISTAPPSQL, 'r' );
            if( $file_handle ):
                $total_lines = 10;
                $i = 0;
                while( !feof( $file_handle ) ):
                    $buffer = fgets( $file_handle, $memory_size );
                    echo "<pre>";
                    var_dump( $buffer );
                    echo "</pre>";
//                    $i++;
//                    if( $i == $total_lines ):
//                        break;
//                    endif;
                endwhile;
                fclose( $file_handle );
            endif;
    
    
            exit();
        }
    }