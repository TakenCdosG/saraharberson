<?php
/* WooCommerce Shop
---------------------------------------------------------- */
if ( class_exists( 'WooCommerce' ) ) {     
	
	class WPBakeryShortCode_Wyde_WC_Shop extends WPBakeryShortCode {
	}

	/* Shop Page */
	vc_map( array(
	    'name' => __( 'Shop Page', 'wyde-core' ),
	    'base' => 'wyde_wc_shop',
	    'icon' => 'icon-wpb-woocommerce',
	    'category' => __( 'WooCommerce', 'wyde-core' ),
	    'description' => __( 'Create custom shop page with archive products.', 'wyde-core' ),
	    'params' => array(
	        array(
	        	'param_name' => 'columns',
                'type' => 'dropdown',
                'heading' => __('Columns', 'wyde-core'),                
                'value' => array(
                    '1', 
                    '2', 
                    '3', 
                    '4',
                    '5',
                    '6',
                ),
                'std' => '4',
                'description' => __('Select the number of columns.', 'wyde-core'),
            ),
	        array(
	        	'param_name' => 'orderby',
	            'type' => 'dropdown',
	            'heading' => __( 'Order by', 'wyde-core' ),	            
	            'value' => array(
	                '',
	                __( 'Date', 'wyde-core' ) => 'date',
	                __( 'ID', 'wyde-core' ) => 'ID',
	                __( 'Author', 'wyde-core' ) => 'author',
	                __( 'Title', 'wyde-core' ) => 'title',
	                __( 'Modified', 'wyde-core' ) => 'modified',
	                __( 'Random', 'wyde-core' ) => 'rand',
	                __( 'Comment count', 'wyde-core' ) => 'comment_count',
	                __( 'Menu order', 'wyde-core' ) => 'menu_order',
	            ),
	            'std' => 'menu_order',
	            'save_always' => true,
	            'description' => sprintf( __( 'Select how to sort retrieved products. More at %s. Default by Title', 'wyde-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
	        ),
	        array(
	        	'param_name' => 'order',
	            'type' => 'dropdown',
	            'heading' => __( 'Sort order', 'wyde-core' ),	            
	            'value' => array(
	                '',
	                __( 'Descending', 'wyde-core' ) => 'DESC',
	                __( 'Ascending', 'wyde-core' ) => 'ASC',
	            ),
	            'save_always' => true,
	            'description' => sprintf( __( 'Designates the ascending or descending order. More at %s. Default by ASC', 'wyde-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
	        ),
	        array(
	        	'param_name' => 'per_page',
	            'type' => 'textfield',
	            'heading' => __('Per page', 'wyde-core'),	            
	            'value' => '12',
	            'description' => __('Number of items per page.', 'wyde-core'),
	        ),
	        array(
	        	'param_name' => 'pagination',
	            'type' => 'dropdown',
	            'heading' => __('Pagination Type', 'wyde-core'),	            
	            'value' => array(
	                __('Numeric Pagination', 'wyde-core') => '1', 
	                __('Infinite Scroll', 'wyde-core') => '2',
	                __('Next and Previous', 'wyde-core') => '3',
	                __('Hide', 'wyde-core') => 'hide',
	                ),
	            'description' => __('Select the pagination type.', 'wyde-core')
	        ),
		    array(
		    	'param_name' => 'el_class',
			    'type' => 'textfield',
			    'heading' => __( 'Extra CSS Class', 'wyde-core' ),			    
			    'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
		    ),
	    )
	) );
}