<?php
/* Flickr
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Flickr extends WPBakeryShortCode {
}

vc_map( array(
	'name' => __( 'Flickr Stream', 'wyde-core' ),
    'base' => 'wyde_flickr',
	'icon' => 'wyde-icon flickr-icon',
    'weight'    => 900,
	'category' => __('Wyde', 'wyde-core'),
	'description' => __( 'Image feed from Flickr account.', 'wyde-core' ),
	"params" => array(
        array(
        	'param_name' => 'title',
			'type' => 'textfield',
			'heading' => __( 'Title', 'wyde-core' ),			
			'admin_label' => true,
            'description' => __('Enter title text.', 'wyde-core')
		),
        array(
        	'param_name' => 'type',
			'type' => 'dropdown',
			'heading' => __( 'Type', 'wyde-core' ),			
            'admin_label' => true,
			'value' => array(
				__( 'User', 'wyde-core' ) => 'user',
				__( 'Group', 'wyde-core' ) => 'group'
			),
			'description' => __( 'Select photo stream type.', 'wyde-core' )
		),
        array(
        	'param_name' => 'flickr_id',
			'type' => 'textfield',
			'heading' => __( 'Flickr ID', 'wyde-core' ),			
			'admin_label' => true,
			'description' => sprintf( __( 'To find your flickID visit %s.', 'wyde-core' ), '<a href="http://idgettr.com/" target="_blank">idGettr</a>' )
		),
		array(
			'param_name' => 'count',
			'type' => 'dropdown',
			'heading' => __( 'Number of photos', 'wyde-core' ),			
			'value' => array( 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20 ),
			'description' => __( 'Select number of photos to display.', 'wyde-core' )
		),
		array(
			'param_name' => 'columns',
			'type' => 'dropdown',
			'heading' => __( 'Columns', 'wyde-core' ),			
			'value' => array( 2, 3, 4, 5, 6, 12 ),
            'admin_label' => true,
			'description' => __( 'Select number of grid columns to display.', 'wyde-core' )
		),
		array(
			'param_name' => 'el_class',
			'type' => 'textfield',
			'heading' => __( 'Extra CSS Class', 'wyde-core' ),			
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
		),
	)
) );