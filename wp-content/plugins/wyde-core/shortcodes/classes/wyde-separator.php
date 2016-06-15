<?php

/* Separator (Divider)
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Separator extends WPBakeryShortCode {
}

$icon_picker_options = array();
$icon_picker_options = apply_filters('wyde_iconpicker_options', $icon_picker_options);
$icon_picker_options[0]['dependency'] = array(
		                    'element' => 'label_style',
		                    'value' => array('icon')
		                );

vc_map( array(
    'name' => __( 'Separator', 'wyde-core' ),
    'base' => 'wyde_separator',
    'icon' => 'wyde-icon separator-icon',
    'show_settings_on_create' => true,
    'weight'    => 900,
    'category' => __('Wyde', 'wyde-core'),
    'description' => __( 'Horizontal separator line', 'wyde-core' ),
    'params' => array(
        array(
            'param_name' => 'label_style',
            'type' => 'dropdown',
            'heading' => __('Label Style', 'wyde-core'),                
            'value' => array(
                __('None', 'wyde-core') => '', 
                __('Text', 'wyde-core') => 'text', 
                __('Icon', 'wyde-core') => 'icon', 
            ),
            'description' => __('Select a label style.', 'wyde-core')
        ),
        array(
            'param_name' => 'title',
            'type' => 'textfield',
            'heading' => __('Title', 'wyde-core'),                
            'description' => __('Input a title text separator.', 'wyde-core'),
            'dependency' => array(
	            'element' => 'label_style',
	            'value' => array('text'),
	        )
        ),
        $icon_picker_options[0],
        $icon_picker_options[1],
        $icon_picker_options[2],
        $icon_picker_options[3],
        $icon_picker_options[4],
        $icon_picker_options[5],
        array(
            'param_name' => 'text_align',
            'type' => 'dropdown',
            'heading' => __('Text Alignment', 'wyde-core'),                
            'value' => array(
                __('Left', 'wyde-core') => 'left', 
                __('Center', 'wyde-core') => 'center', 
                __('Right', 'wyde-core') => 'right', 
            ),
            'description' => __('Select text alignment.', 'wyde-core')
        ),
        array(
            'param_name' => 'style',
		    'type' => 'dropdown',
		    'heading' => __('Style', 'wyde-core' ),			    
		    'value' => array(
        	    __('Solid', 'wyde-core') => '',
	            __('Dashed', 'wyde-core') => 'dashed',
	            __('Dotted', 'wyde-core') => 'dotted',
	            __('Double', 'wyde-core') => 'double',
            ),
		    'description' => __( 'Separator style', 'wyde-core' )
	    ),
	    array(
            'param_name' => 'border_width',
		    'type' => 'dropdown',
		    'heading' => __( 'Border Thickness', 'wyde-core' ),			    
		    'value' => array(
                '1px',
                '2px',
                '3px',
                '4px',
                '5px',
                '6px',
                '7px',
                '8px',
                '9px',
                '10px',
            ),
		    'description' => __( 'Select border thickness.', 'wyde-core' ),
	    ),
	    array(
            'param_name' => 'el_width',
		    'type' => 'dropdown',
		    'heading' => __( 'Width', 'wyde-core' ),			    
		    'value' => array(
                '10%',
                '20%',
                '30%',
                '40%',
                '50%',
                '60%',
                '70%',
                '80%',
                '90%',
                '100%',
            ),
		    'description' => __( 'Separator element width in percents.', 'wyde-core' ),
	    ),
        array(
            'param_name' => 'color',
		    'type' => 'colorpicker',
		    'heading' => __( 'Color', 'wyde-core' ),			    
		    'description' => __( 'Select separator border and text color.', 'wyde-core' ),
	    ),
	    array(
            'param_name' => 'el_class',
		    'type' => 'textfield',
		    'heading' => __( 'Extra CSS Class', 'wyde-core' ),			    
		    'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
	    ),
	    array(
            'param_name' => 'css',
		    'type' => 'css_editor',
		    'heading' => __( 'CSS', 'wyde-core' ),			    
		    'group' => __( 'Design Options', 'wyde-core' )
	    )
    )
));