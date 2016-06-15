<?php
/* Donut Chart
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Donut_Chart extends WPBakeryShortCode {
}

$icon_picker_options = array();
$icon_picker_options = apply_filters('wyde_iconpicker_options', $icon_picker_options);
$icon_picker_options[0]['dependency'] = array(
		                    'element' => 'label_style',
		                    'value' => array('icon')
		                );

vc_map( array(
    'name' => __('Donut Chart', 'wyde-core'),
    'description' => __('Animated donut chart.', 'wyde-core'),
    'base' => 'wyde_donut_chart',
    'controls' => 'full',
    'icon' =>  'wyde-icon donut-chart-icon', 
    'weight'    => 900,
    'category' => __('Wyde', 'wyde-core'),
    'params' => array(
        array(
            'param_name' => 'title',
            'type' => 'textfield',
            'heading' => __('Title', 'wyde-core'),                        
            'admin_label' => true,
            'description' => __('Set chart title.', 'wyde-core')
        ),
        array(
            'param_name' => 'value',
            'type' => 'textfield',
            'heading' => __('Chart Value', 'wyde-core'),
            'admin_label' => true,
            'description' => __('Input chart value here. can be 1 to 100.', 'wyde-core')
        ),
        array(
            'param_name' => 'label_style',
            'type' => 'dropdown',                        
            'heading' => __('Label Style', 'wyde-core'),                        
            'value' => array(
                'Text' => '', 
                'Icon' => 'icon', 
            ),
            'description' => __('Select a label style.', 'wyde-core')
        ),
        array(
            'param_name' => 'label',
            'type' => 'textfield',
            'heading' => __('Label', 'wyde-core'),                      
            'description' => __('Set chart label.', 'wyde-core'),
            'dependency' => array(
                'element' => 'label_style',
                'is_empty' => true,
            )
        ),
        $icon_picker_options[0],
        $icon_picker_options[1],
        $icon_picker_options[2],
        $icon_picker_options[3],
        $icon_picker_options[4],
        $icon_picker_options[5],
        array(
            'param_name' => 'style',
            'type' => 'dropdown',
            'heading' => __('Style', 'wyde-core'),                        
            'value' => array(
                __('Default', 'wyde-core') => '', 
                __('Outline', 'wyde-core') => 'outline', 
                __('Inline', 'wyde-core') => 'inline', 
            ),
            'description' => __('Select style.', 'wyde-core')
        ),
        array(
            'param_name' => 'bar_color',
            'type' => 'colorpicker',
            'heading' => __('Bar Color', 'wyde-core'),                        
            'description' => __('Select bar color.', 'wyde-core')
        ),
        array(
            'param_name' => 'bar_border_color',
            'type' => 'colorpicker',                        
            'heading' => __('Border Color', 'wyde-core'),
            'description' => __('Select border color.', 'wyde-core')
        ),
        array(
            'param_name' => 'fill_color',
            'type' => 'colorpicker',
            'heading' => __('Fill Color', 'wyde-core'),
            'description' => __('Select background color of the whole circle.', 'wyde-core')
        ),
        array(
            'param_name' => 'start',
            'type' => 'dropdown',
            'heading' => __('Start', 'wyde-core'),                        
            'value' => array(
                __('Default', 'wyde-core') => '', 
                __('90 degree', 'wyde-core') => '90', 
                __('180 degree', 'wyde-core') => '180', 
                __('270 degree', 'wyde-core') => '270', 
            ),
            'description' => __('Select the degree to start animate.', 'wyde-core')
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
));