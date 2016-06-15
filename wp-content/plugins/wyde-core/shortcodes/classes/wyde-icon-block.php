<?php 
/* Icon Block
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Icon_Block extends WPBakeryShortCode {
}

$icon_picker_options = array();
$icon_picker_options = apply_filters('wyde_iconpicker_options', $icon_picker_options);

vc_map( array(
    'name' => __('Icon Block', 'wyde-core'),
    'description' => __('Add icon block.', 'wyde-core'),
    'base' => 'wyde_icon_block',
    'controls' => 'full',
    'icon' =>  'wyde-icon icon-block-icon', 
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
            'param_name' => 'size',
            'type' => 'dropdown',
            'heading' => __('Icon Size', 'wyde-core'),                        
            'value' => array(
                __('Small', 'wyde-core') => 'small', 
                __('Medium', 'wyde-core') => 'medium', 
                __('Large', 'wyde-core') => 'large',
            ),
            'description' => __('Select icon size.', 'wyde-core')
        ),
        array(
            'param_name' => 'style',
            'type' => 'dropdown',
            'heading' => __('Icon Style', 'wyde-core'),                        
            'value' => array(
                __('Circle', 'wyde-core') => 'circle',
                __('Square', 'wyde-core') => 'square',
                __('None', 'wyde-core') => 'none',
            ),
            'description' => __('Select icon style.', 'wyde-core')
        ),
        array(
            'param_name' => 'hover',
            'type' => 'dropdown',
            'heading' => __('Hover Effect', 'wyde-core'),                        
            'value' => array(
                __('None', 'wyde-core') => 'none',
                __('Zoom In', 'wyde-core') => '1',
                __('Zoom Out', 'wyde-core')  => '2',
                __('Pulse', 'wyde-core')  => '3',
                __('Left to Right', 'wyde-core')  => '4',
                __('Right to Left', 'wyde-core') => '5',
                __('Bottom to Top', 'wyde-core') => '6',
                __('Top to Bottom', 'wyde-core') => '7'
            ),
            'description' => __('Select icon hover effect.', 'wyde-core'),
            'dependency' => array(
                'element' => 'style',
                'value' => array('circle', 'square')
            )
        ),
        array(
            'param_name' => 'color',
            'type' => 'colorpicker',
            'heading' => __('Color', 'wyde-core'),                        
            'description' => __('Select icon text color.', 'wyde-core'),
            'dependency' => array(
                'element' => 'style',
                'value' => array('none')
            )
        ),
        array(
            'param_name' => 'bg_color',
            'type' => 'colorpicker',
            'heading' => __('Background Color', 'wyde-core'),                        
            'description' => __('Select icon background color.', 'wyde-core'),
            'dependency' => array(
                'element' => 'style',
                'value' => array('circle', 'square')
            )
        ),
        array(
            'param_name' => 'link',
            'type' => 'vc_link',
            'heading' => __('URL', 'wyde-core'),                        
            'description' => __('Icon link.', 'wyde-core'),                        
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
        )
    )
) );