<?php

/***************************************** 
/* CLIENTS CAROUSEL
/*****************************************/
vc_map( array(
    'name' => esc_html__('Clients Carousel', 'overlap'),
    'description' => esc_html__('Create beautiful responsive carousel slider.', 'overlap'),
    'base' => 'wyde_clients_carousel',
    'controls' => 'full',
    'icon' =>  'wyde-icon clients-carousel-icon', 
    'weight'    => 900,
    'category' => esc_html__('Wyde', 'overlap'),
    'params' => array(
        array(
            'param_name' => 'images',
            'type' => 'attach_images',
            'heading' => esc_html__('Images', 'overlap'),                    
            'description' => esc_html__('Upload or select images from media library.', 'overlap')
        ),
        array(
            'param_name' => 'image_size',
            'type' => 'dropdown',
            'heading' => esc_html__( 'Image Size', 'overlap' ),                   
            'value' => array(
                esc_html__('Thumbnail (150x150)', 'overlap' ) => 'thumbnail',
                esc_html__('Medium (340x340)', 'overlap' ) => 'overlap-medium',
                esc_html__('Large (640x640)', 'overlap' ) => 'overlap-large',
                esc_html__('Extra Large (960x960)', 'overlap' ) => 'overlap-xlarge',
                esc_html__('Original', 'overlap' ) => 'full',
            ),
            'description' => esc_html__( 'Select image size.', 'overlap' )
        ),
        array(
            'param_name' => 'visible_items',
            'type' => 'dropdown',                   
            'heading' => esc_html__('Visible Items', 'overlap'),                    
            'value' => array('auto', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'),
            'std' => '3',
            'description' => esc_html__('The maximum amount of items displayed at a time.', 'overlap')
        ),
        array(
            'param_name' => 'show_navigation',
            'type' => 'checkbox',
            'heading' => esc_html__( 'Show Navigation', 'overlap' ),                    
            'description' => esc_html__('Display "next" and "prev" buttons.', 'overlap')
        ),
        array(
            'param_name' => 'show_pagination',
            'type' => 'checkbox',
            'heading' => esc_html__('Show Pagination', 'overlap'),                    
            'description' => esc_html__('Show pagination.', 'overlap')
        ),
        array(
            'param_name' => 'auto_play',
            'type' => 'checkbox',
            'heading' => esc_html__('Auto Play', 'overlap'),                    
            'description' => esc_html__('Auto play slide.', 'overlap')
        ),
        array(
            'param_name' => 'speed',
            'type' => 'dropdown',
            'heading' => esc_html__('Speed', 'overlap'),                    
            'value' => array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10'),
            'std' => '4',
            'description' => esc_html__('The amount of time between each slideshow interval (in seconds).', 'overlap'),
            'dependency' => array(
                'element' => 'auto_play',
                'value' => 'true'
            )
        ),
        array(
            'param_name' => 'loop',
            'type' => 'checkbox',
            'heading' => esc_html__('Loop', 'overlap'),                    
            'description' => esc_html__('Inifnity loop. Duplicate last and first items to get loop illusion.', 'overlap')
        ),
        array(
            'param_name' => 'animation',
            'type' => 'wyde_animation',
            'heading' => esc_html__('Animation', 'overlap'),                    
            'description' => esc_html__('Select a CSS3 Animation that applies to this element.', 'overlap')
        ),
        array(
            'param_name' => 'animation_delay',
            'type' => 'textfield',                    
            'heading' => esc_html__('Animation Delay', 'overlap'),                 
            'description' => esc_html__('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'overlap'),
            'dependency' => array(
                'element' => 'animation',
                'not_empty' => true
            )
        ),
        array(
            'param_name' => 'el_class',
            'type' => 'textfield',
            'heading' => esc_html__( 'Extra CSS Class', 'overlap' ),                  
            'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'overlap' )
        ),
    )
) );