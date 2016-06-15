<?php
    
if( ! defined( 'ABSPATH' ) ) {
    die;
}

if( ! class_exists( 'Overlap_Widget' ) ) {

    class Overlap_Widget {

    	function __construct() {    		 
            add_action('wyde_social_icons', 'overlap_social_icons');
            $this->load_widgets();
    	}

    	function Overlap_Widget(){
    		$this->__construct();
    	}    	

        /* Find and include all shortcodes within shortcodes folder */
	    public function load_widgets() {
            
            $files = glob( get_template_directory(). '/inc/widgets/*.php' );
            
            if( is_array($files) ){
                foreach( $files as $filename ) {
                    include_once( $filename );
                }
            }
		    
	    }	

	}	

	new Overlap_Widget();

}