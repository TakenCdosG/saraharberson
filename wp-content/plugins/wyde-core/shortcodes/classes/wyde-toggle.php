<?php

/* Toggle (FAQ)
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Toggle extends WPBakeryShortCode {
}

vc_map( array(
    'name' => __( 'FAQ', 'wyde-core' ),
    'base' => 'wyde_toggle',
    'icon' => 'wyde-icon toggle-icon',
    'weight'    => 900,
    'category' => __('Wyde', 'wyde-core'),
    'description' => __( 'Toggle element for Q&A block', 'wyde-core' ),
    'params' => array(
	    array(
	    	'param_name' => 'title',
		    'type' => 'textfield',
		    'holder' => 'h4',
		    'class' => 'vc_toggle_title',
		    'heading' => __( 'Toggle title', 'wyde-core' ),			    
		    'value' => __( 'Toggle title', 'wyde-core' ),
		    'description' => __( 'Toggle block title.', 'wyde-core' )
	    ),
	    array(
	    	'param_name' => 'content',
		    'type' => 'textarea_html',
		    'holder' => 'div',
		    'class' => 'vc_toggle_content',
		    'heading' => __( 'Toggle content', 'wyde-core' ),			    
		    'value' => __( '<p>Toggle content goes here, click edit button to change this text.</p>', 'wyde-core' ),
		    'description' => __( 'Toggle block content.', 'wyde-core' )
	    ),
        array(
        	'param_name' => 'color',
            'type' => 'colorpicker',                
            'heading' => __('Color', 'wyde-core'),
            'description' => __('Select an element color.', 'wyde-core')
        ),
	    array(
	    	'param_name' => 'open',
		    'type' => 'dropdown',
		    'heading' => __( 'Default State', 'wyde-core' ),			    
		    'value' => array(
			    __( 'Closed', 'wyde-core' ) => 'false',
			    __( 'Open', 'wyde-core' ) => 'true'
		    ),
		    'description' => __( 'Select "Open" if you want toggle to be open by default.', 'wyde-core' )
	    ),
        array(
        	'param_name' => 'animation',
            'type' => 'wyde_animation',
            'heading' => __('Animation', 'wyde-core'),                
            'description' => __('Select a CSS3 Animation that applies to this element.', 'wyde-core')
        ),
        array(
        	'param_name' => 'animation_delay',
            'type' => 'textfield',
            'heading' => __('Animation Delay', 'wyde-core'),
            'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'wyde-core'),
            'dependency' => array(
			    'element' => 'animation',
			    'not_empty' => true
		    )
        ),
	    array(
	    	'param_name' => 'el_class',
		    'type' => 'textfield',
		    'heading' => __( 'Extra CSS Class', 'wyde-core' ),			    
		    'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
	    ),
    ),
    'js_view' => 'VcToggleView'
) );