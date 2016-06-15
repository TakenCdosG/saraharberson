<?php
/* Action Box
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Section_Separator extends WPBakeryShortCode {
}

vc_map( array(
	'name' => __( 'Section Separator', 'wyde-core' ),
	'base' => 'wyde_section_separator',
    'icon' =>  'wyde-icon section-separator-icon', 
	'category' => __('Wyde', 'wyde-core'),
    'weight'    => 900,
	'description' => __( 'Section separator.', 'wyde-core' ),
	'params' => array(
        array(
            'param_name' => 'style',
            'type' => 'dropdown',
            'heading' => __('Style', 'wyde-core'),            
            'value' => array(
                __('Wave', 'wyde-core') => 'wave', 
                __('Wave Flip', 'wyde-core') => 'wave-alt', 
                __('Mountain', 'wyde-core') => 'mountain', 
                __('Mountain Flip', 'wyde-core') => 'mountain-alt', 
            ),
            'description' => __('Select separator style.', 'wyde-core'),
        ),
        array(
            'param_name' => 'overlap',
            'type' => 'dropdown',
            'heading' => __('Overlap', 'wyde-core'),            
            'value' => array(
                __('Top', 'wyde-core') => 'top',
                __('Bottom', 'wyde-core') => 'bottom', 
            ),
            'description' => __('Select the direction of another object that will be overlapped.', 'wyde-core')
        ),
        array(
            'param_name' => 'background_color',
            'type' => 'colorpicker',
            'heading' => __( 'Background Color', 'wyde-core' ),            
            'description' => __( 'Select background color.', 'wyde-core' ),
            'value' => '#fff',
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
        