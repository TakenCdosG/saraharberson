<?php 
/* Counter Box
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Counter_Box extends WPBakeryShortCode {
}

$icon_picker_options = array();
$icon_picker_options = apply_filters('wyde_iconpicker_options', $icon_picker_options);

vc_map( array(
    'name' => __('Counter Box', 'wyde-core'),
    'description' => __('Animated numbers.', 'wyde-core'),
    'base' => 'wyde_counter_box',
    'controls' => 'full',
    'icon' =>  'wyde-icon counter-box-icon', 
    'weight'    => 900,
    'category' => __('Wyde', 'wyde-core'),
    'params' => array(
        $icon_picker_options[0],
        $icon_picker_options[1],
        $icon_picker_options[2],
        $icon_picker_options[3],
        $icon_picker_options[4],
        $icon_picker_options[5],
        array(
            'param_name' => 'title',
            'type' => 'textfield',
            'heading' => __('Title', 'wyde-core'),                        
            'admin_label' => true,
            'description' => __('Set counter title.', 'wyde-core')
        ),
        array(
            'param_name' => 'start',
            'type' => 'textfield',
            'heading' => __('Start From', 'wyde-core'),                        
            'value' => '0',
            'description' => __('Set start value.', 'wyde-core')
        ),
        array(
            'param_name' => 'value',
            'type' => 'textfield',                        
            'heading' => __('Value', 'wyde-core'),                        
            'value' => '100',
            'description' => __('Set counter value.', 'wyde-core')
        ),
        array(
            'param_name' => 'color',
            'type' => 'colorpicker',
            'heading' => __( 'Color', 'wyde-core' ),			            
            'description' => __( 'Select a color.', 'wyde-core' ),
        ),
        array(
            'param_name' => 'style',
            'type' => 'dropdown',
            'heading' => __('Style', 'wyde-core'),                        
            'value' => array(
                __('Classic', 'wyde-core') => '1', 
                __('Theme Default', 'wyde-core') => '2', 
            ),
            'description' => __('Select counter box style.', 'wyde-core')
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