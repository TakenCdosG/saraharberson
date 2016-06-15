<?php
/* Pricing Box
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Pricing_Box extends WPBakeryShortCode {
}

vc_map( array(
    'name' => __('Pricing Box', 'wyde-core'),
    'description' => __('Create pricing box.', 'wyde-core'),
    'base' => 'wyde_pricing_box',
    'controls' => 'full',
    'icon' =>  'wyde-icon pricing-box-icon', 
    'weight'    => 900,
    'category' => __('Wyde', 'wyde-core'),
    'params' => array(
        array(
            'param_name' => 'heading',
            'type' => 'textfield',                
            'heading' => __('Title', 'wyde-core'),                
            'admin_label' => true,
            'description' => __('Enter the heading or package name.', 'wyde-core')
        ),
        array(
            'param_name' => 'sub_heading',
            'type' => 'textfield',
            'heading' => __('Sub Heading', 'wyde-core'),
            'description' => __('Enter a short description.', 'wyde-core')
        ),
        array(
            'param_name' => 'image',
            'type' => 'attach_image',
            'heading' => __( 'Image', 'wyde-core' ),
            'description' => __( 'Select image from media library.', 'wyde-core' )
        ),
        array(
            'param_name' => 'price',
            'type' => 'textfield',
            'heading' => __('Price', 'wyde-core'),                
            'admin_label' => true,
            'description' => __('Enter a price. E.g. 100, 150, etc.', 'wyde-core')
        ),
        array(
            'param_name' => 'price_unit',
            'type' => 'textfield',
            'heading' => __('Price Unit', 'wyde-core'),
            'description' => __('Enter a price unit. E.g. $, €, etc.', 'wyde-core')
        ),
        array(
            'param_name' => 'price_term',
            'type' => 'textfield',
            'heading' => __('Price Term', 'wyde-core'),
            'description' => __('Enter a price term. E.g. per month, per year, etc.', 'wyde-core')
        ),
        array(
            'param_name' => 'color',
		    'type' => 'colorpicker',
		    'heading' => __( 'Box Color', 'wyde-core' ),			    
		    'description' => __( 'Select box color.', 'wyde-core' ),
        ),
        array(
            'param_name' => 'content',
            'type' => 'textarea_html',
            'heading' => __('Features', 'wyde-core'),         
            'description' => __('Enter the features list or table description.', 'wyde-core')
        ),
        array(
            'param_name' => 'button_text',
            'type' => 'textfield',
            'heading' => __('Button Text', 'wyde-core'),
            'description' => __('Enter a button text.', 'wyde-core')
        ),
        array(
            'param_name' => 'button_color',
		    'type' => 'colorpicker',
		    'heading' => __( 'Button Color', 'wyde-core' ),			    
		    'description' => __( 'Select button background color.', 'wyde-core' ),
        ),
        array(
            'param_name' => 'link',
            'type' => 'vc_link',
            'heading' => __('Button Link', 'wyde-core'),
            'description' => __('Select or enter the link for button.', 'wyde-core')
        ),
        array(
            'param_name' => 'featured',
            'type' => 'checkbox',
            'heading' => __('Featured Box', 'wyde-core'),                
            'description' => __('Make this box as featured', 'wyde-core')                
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