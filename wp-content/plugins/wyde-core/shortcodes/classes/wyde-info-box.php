<?php
/* Info Box
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Info_Box extends WPBakeryShortCode {

}

$icon_picker_options = array();
$icon_picker_options = apply_filters('wyde_iconpicker_options', $icon_picker_options);

vc_map( array(
    'name' => __('Info Box', 'wyde-core'),
    'description' => __('Content box with icon.', 'wyde-core'),
    'base' => 'wyde_info_box',
    'controls' => 'full',
    'icon' =>  'wyde-icon info-box-icon', 
    'weight'    => 900,
    'category' => __('Wyde', 'wyde-core'),
    'params' => array(
        array(
            'param_name' => 'title',
            'type' => 'textfield',                    
            'heading' => __('Title', 'wyde-core'),                    
            'admin_label' => true, 
            'description' => __('Set info box title.', 'wyde-core')
        ),
        array(
            'param_name' => 'content',
            'type' => 'textarea_html',
            'heading' => __('Content', 'wyde-core'),                    
            'description' => __('Enter your content.', 'wyde-core')
        ),
        $icon_picker_options[0],
        $icon_picker_options[1],
        $icon_picker_options[2],
        $icon_picker_options[3],
        $icon_picker_options[4],
        $icon_picker_options[5],
        array(
            'param_name' => 'icon_size',
            'type' => 'dropdown',
            'heading' => __('Icon Size', 'wyde-core'),                    
            'value' => array(
                __('Small', 'wyde-core') => 'small', 
                __('Medium', 'wyde-core') => 'medium', 
                __('Large', 'wyde-core') => 'large'
            ),
            'description' => __('Select icon size.', 'wyde-core')
        ),
        array(
            'param_name' => 'icon_position',
            'type' => 'dropdown',
            'heading' => __('Icon Position', 'wyde-core'),                    
            'value' => array(
                __('Top', 'wyde-core') => 'top', 
                __('Left', 'wyde-core') => 'left', 
                __('Right', 'wyde-core') => 'right', 
            ),
            'description' => __('Select icon position.', 'wyde-core'),
            'dependency' => array(
                'element' => 'icon_size',
                'value' => array('small', 'medium')
            )
        ),
        array(
            'param_name' => 'icon_style',
            'type' => 'dropdown',
            'heading' => __('Border Style', 'wyde-core'),                    
            'value' => array(
                __('None', 'wyde-core') => 'none', 
                __('Circle', 'wyde-core') => 'circle', 
            ),
            'description' => __('Select icon border style.', 'wyde-core'),
            'dependency' => array(
                'element' => 'icon_size',
                'value' => array('small', 'medium')
            )
        ),
        array(
            'param_name' => 'color',
            'type' => 'colorpicker',
            'heading' => __('Color', 'wyde-core'),                    
            'value' => '',
            'description' => __('Select an icon color.', 'wyde-core')
        ),
        array(
            'param_name' => 'link',
	        'type' => 'vc_link',
	        'heading' => __( 'URL (Link)', 'wyde-core' ),			        
	        'description' => __( 'Set a Read More link.', 'wyde-core' )
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