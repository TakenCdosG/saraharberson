<?php
/* Heading
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Heading extends WPBakeryShortCode {
}

vc_map( array(
    'name' => __('Heading', 'wyde-core'),
    'description' => __('Heading text.', 'wyde-core'),
    'base' => 'wyde_heading',
    'controls' => 'full',
    'icon' =>  'wyde-icon heading-icon', 
    'weight'    => 999,
    'category' => __('Wyde', 'wyde-core'),
    'params' => array(
        array(
            'param_name' => 'title',
            'type' => 'textfield',
            'heading' => __('Heading', 'wyde-core'),                    
            'admin_label' => true,
            'description' => __('Enter heading text.', 'wyde-core')
        ),
        array(
            'param_name' => 'heading_color',
            'type' => 'colorpicker',
            'heading' => __( 'Heading Color', 'wyde-core' ),                    
            'description' => __( 'Select heading text color.', 'wyde-core' )
        ),
        array(
            'param_name' => 'subheading',
            'type' => 'textfield',                    
            'heading' => __('Subheading', 'wyde-core'),                    
            'admin_label' => true,                    
            'description' => __('Enter subheading text.', 'wyde-core')
        ),
        array(
            'param_name' => 'subheading_color',
            'type' => 'colorpicker',
            'heading' => __( 'Subheading Color', 'wyde-core' ),                    
            'description' => __( 'Select subheading text color.', 'wyde-core' )
        ),
        array(
            'param_name' => 'style',
            'type' => 'dropdown',                    
            'heading' => __('Style', 'wyde-core'),                    
            'value' => array(                        
                __('Top Subtitle', 'wyde-core') => '1', 
                __('Bottom Subtitle ', 'wyde-core') => '2', 
                __('Background', 'wyde-core') => '3', 
                __('Top Line', 'wyde-core') => '4', 
                __('Bottom Line', 'wyde-core') => '5',      
                __('Theme Default', 'wyde-core') => '6', 
                __('Simple', 'wyde-core') => '7',                  
            ),
            'description' => __('Select a heading style.', 'wyde-core')
        ),
        array(
            'param_name' => 'text_align',
            'type' => 'dropdown',
            'heading' => __('Text Alignment', 'wyde-core'),                    
            'value' => array(                        
                __('Left', 'wyde-core') => 'left', 
                __('Center', 'wyde-core') =>'center', 
                __('Right', 'wyde-core') => 'right', 
            ),
            'description' => __('Select text alignment.', 'wyde-core'),
            'std' => 'center',
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
    )
) );