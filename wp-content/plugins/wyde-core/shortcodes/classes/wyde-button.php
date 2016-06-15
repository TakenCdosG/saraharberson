<?php
/* Button
---------------------------------------------------------- */  
class WPBakeryShortCode_Wyde_Button extends WPBakeryShortCode {
}

vc_map( array(
    'name' => __('Button', 'wyde-core'),
    'description' => __('Add button.', 'wyde-core'),
    'base' => 'wyde_button',
    'controls' => 'full',
    'icon' =>  'wyde-icon button-icon', 
    'weight'    => 900,
    'category' => __('Wyde', 'wyde-core'),
    'params' => array(
        array(
            'param_name' => 'title',
            'type' => 'textfield',                       
            'heading' => __('Title', 'wyde-core'),             
            'admin_label' => true,
            'value' => '',
            'description' => __('Text on the button.', 'wyde-core')
        ),
        array(
            'param_name' => 'link',
            'type' => 'vc_link',
            'heading' => __( 'URL (Link)', 'wyde-core' ),			            
            'description' => __( 'Set a button link.', 'wyde-core' )
        ),
        array(
            'param_name' => 'style',
            'type' => 'dropdown',
            'heading' => __('Style', 'wyde-core'),                    
            'value' => array(
                __('Square', 'wyde-core') => '', 
                __('Round', 'wyde-core') => 'round', 
                __('Rounded', 'wyde-core') => 'rounded', 
                __('Square Outline', 'wyde-core') => 'outline', 
                __('Round Outline', 'wyde-core') => 'round-outline', 
                __('Rounded Outline', 'wyde-core') => 'rounded-outline',
            ),
            'description' => __('Select button style.', 'wyde-core')
        ),
        array(
            'param_name' => 'size',
            'type' => 'dropdown',
            'heading' => __('Size', 'wyde-core'),
            'value' => array(
                __('Small', 'wyde-core') => '', 
                __('Large', 'wyde-core') =>'large', 
            ),
            'description' => __('Select button size.', 'wyde-core')
        ),
        array(
            'param_name' => 'color',
            'type' => 'colorpicker',
            'heading' => __( 'Text Color', 'wyde-core' ),			            
            'description' => __( 'Select button text color.', 'wyde-core' ),
            'dependency' => array(
	            'element' => 'style',
	            'value' => array('', 'round')
            )
        ),
        array(
            'param_name' => 'bg_color',
            'type' => 'colorpicker',
            'heading' => __( 'Background Color', 'wyde-core' ),			            
            'description' => __( 'Select button background color.', 'wyde-core' ),
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
        array(
            'param_name' => 'css',
            'type' => 'css_editor',
            'heading' => __( 'CSS', 'wyde-core' ),			            
            'group' => __( 'Design Options', 'wyde-core' )
        ),
    )
));