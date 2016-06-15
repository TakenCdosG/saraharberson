<?php
/* Clients Carousel
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Clients_Carousel extends WPBakeryShortCode {
}

vc_map( array(
    'name' => __('Clients Carousel', 'wyde-core'),
    'description' => __('Create beautiful responsive carousel slider.', 'wyde-core'),
    'base' => 'wyde_clients_carousel',
    'controls' => 'full',
    'icon' =>  'wyde-icon clients-carousel-icon', 
    'weight'    => 900,
    'category' => __('Wyde', 'wyde-core'),
    'params' => array(
        array(
            'param_name' => 'images',
            'type' => 'attach_images',
            'heading' => __('Images', 'wyde-core'),                    
            'description' => __('Upload or select images from media library.', 'wyde-core')
        ),
        array(
            'param_name' => 'image_size',
	        'type' => 'dropdown',
	        'heading' => __( 'Image Size', 'wyde-core' ),			        
	        'value' => array(
		        __('Thumbnail', 'wyde-core' ) => 'thumbnail',
                __('Medium', 'wyde-core' ) => 'medium',
                __('Large', 'wyde-core' ) => 'large',
                __('Original', 'wyde-core' ) => 'full',
	        ),
	        'description' => __( 'Select image size.', 'wyde-core' )
        ),
        array(
            'param_name' => 'visible_items',
            'type' => 'dropdown',                   
            'heading' => __('Visible Items', 'wyde-core'),                    
            'value' => array('auto', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'),
            'std' => '3',
            'description' => __('The maximum amount of items displayed at a time.', 'wyde-core')
        ),
        array(
            'param_name' => 'show_navigation',
            'type' => 'checkbox',
            'heading' => __( 'Show Navigation', 'wyde-core' ),                    
            'description' => __('Display "next" and "prev" buttons.', 'wyde-core')
        ),
        array(
            'param_name' => 'show_pagination',
            'type' => 'checkbox',
            'heading' => __('Show Pagination', 'wyde-core'),                    
            'description' => __('Show pagination.', 'wyde-core')
        ),
        array(
            'param_name' => 'auto_play',
            'type' => 'checkbox',
            'heading' => __('Auto Play', 'wyde-core'),                    
            'description' => __('Auto play slide.', 'wyde-core')
        ),
        array(
            'param_name' => 'loop',
            'type' => 'checkbox',
            'heading' => __('Loop', 'wyde-core'),                    
            'description' => __('Inifnity loop. Duplicate last and first items to get loop illusion.', 'wyde-core')
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