<?php
/* Progress Bar
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Progress_Bar extends WPBakeryShortCode {

}

vc_map( array(
    'name' => __( 'Progress Bar', 'wyde-core' ),
    'base' => 'wyde_progress_bar',
    'icon' =>  'wyde-icon progress-bar-icon', 
    'weight'    => 900,
    'category' => __('Wyde', 'wyde-core'),
    'description' => __( 'Animated progress bar', 'wyde-core' ),
    'params' => array(
        array(
        	'param_name' => 'title',
	        'type' => 'textfield',
	        'heading' => __( 'Title', 'wyde-core' ),			        
            'admin_label' => true,
	        'description' => __( 'Enter text which will be used as graph title.', 'wyde-core' )
        ),
        array(
        	'param_name' => 'value',
	        'type' => 'textfield',
	        'heading' => __( 'Value', 'wyde-core' ),			        
	        'description' => __( 'Input graph value', 'wyde-core')
        ),
        array(
        	'param_name' => 'unit',
	        'type' => 'textfield',
	        'heading' => __( 'Unit', 'wyde-core' ),			        
	        'description' => __( 'Enter measurement unit (if needed) Eg. %, px, points, etc. Graph value and unit will be appended to the graph tooltip.', 'wyde-core' )
        ),
        array(
        	'param_name' => 'color',
	        'type' => 'colorpicker',
	        'heading' => __( 'Color', 'wyde-core' ),			        
	        'description' => __( 'Select bar color.', 'wyde-core' ),
        ),
        array(
        	'param_name' => 'options',
	        'type' => 'checkbox',
	        'heading' => __( 'Options', 'wyde-core' ),			        
	        'value' => array(
                __( 'Hide Counter', 'wyde-core' ) => 'hidecounter',
	        )
        ),
        array(
        	'param_name' => 'el_class',
	        'type' => 'textfield',
	        'heading' => __( 'Extra CSS Class', 'wyde-core' ),			        
	        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
        ),
    )
) );
