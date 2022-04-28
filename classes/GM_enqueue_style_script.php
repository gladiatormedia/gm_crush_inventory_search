<?php
    class GM_enqueue_style_script{
    
        public function __construct(){
        
        }
        
        public function init(){
            add_action( 'wp_enqueue_scripts', array( $this, 'add_style_and_script' ) );
        }
        
        public function add_style_and_script(){
            wp_enqueue_style( 'gm-bootstrap-5-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' );
            wp_enqueue_style( 'gm-custom-styles', plugin_dir_url( __DIR__ ).'css/styles.css' );
            wp_enqueue_script( 'gm-bootstrap-5-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array(), '', true );
            wp_enqueue_script( 'gm-vehicle-search-js', plugin_dir_url( __DIR__ ).'js/vehicle-search.js', array(), '', true );
            
        }
    }