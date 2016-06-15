<?php

if( !class_exists('Wyde_Widget') ){

    class Wyde_Widget{

        function __construct() {
            add_filter( 'widget_text', 'do_shortcode' );
            $this->load_widgets();
	    }

        /* Find and include all widget classes within classes folder */
	    public function load_widgets() {

            $files = glob( WYDE_PLUGIN_DIR. 'widgets/classes/*.php' );
            
            if( is_array($files) ){
                foreach( $files as $filename ) {
                    include_once( $filename );
                }
            }

	    }

    }

    new Wyde_Widget();

}

?>