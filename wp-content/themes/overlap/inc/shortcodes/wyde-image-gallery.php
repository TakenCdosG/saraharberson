<?php

/***************************************** 
/* IMAGE GALLERY
/*****************************************/
vc_map( array(
    'name' => esc_html__('Image Gallery', 'overlap'),
    'description' => esc_html__('Create beautiful responsive image gallery.', 'overlap'),
    'base' => 'wyde_image_gallery',
    'controls' => 'full',
    'icon' =>  'wyde-icon image-gallery-icon',
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
                esc_html__('Full Width (min-width: 1280px)', 'overlap' ) => 'overlap-fullwidth',
                esc_html__('Original', 'overlap' ) => 'full',
            ),
            'description' => esc_html__( 'Select image size.', 'overlap' )
        ),
        array(
            'param_name' => 'gallery_type',
            'type' => 'dropdown',
            'heading' => esc_html__( 'Gallery Type', 'overlap' ),                 
            'value' => array(
                esc_html__('Grid (Without Space)', 'overlap') => 'grid', 
                esc_html__('Grid (With Space)', 'overlap') => 'grid-space',
                esc_html__('Masonry', 'overlap') => 'masonry',
                esc_html__('Slider', 'overlap') => 'slider',
            ),
            'description' => esc_html__( 'Select image size.', 'overlap' )
        ),
        array(
            'param_name' => 'columns',
            'type' => 'dropdown',
            'heading' => esc_html__('Columns', 'overlap'),                    
            'value' => array(
                '1', 
                '2', 
                '3', 
                '4',
                '5',
                '6',
            ),
            'std' => '4',
            'description' => esc_html__('Select the number of grid columns.', 'overlap'),
            'dependency' => array(
                'element' => 'gallery_type',
                'value' => array('grid', 'grid-space')
            )
        ),
        array(
            'param_name' => 'layout',
            'type' => 'dropdown',
            'heading' => esc_html__( 'Masonry Layout', 'overlap' ),                   
            'value' => array(
                esc_html__('Overlap', 'overlap') => '', 
                esc_html__('Basic 1', 'overlap') => '1',
                esc_html__('Basic 2', 'overlap') => '2',
            ),
            'description' => esc_html__( 'Select masonry layout.', 'overlap' ),
            'dependency' => array(
                'element' => 'gallery_type',
                'value' => array('masonry')
            )
        ),
        array(
            'param_name' => 'hover_effect',
            'type' => 'dropdown',
            'heading' => esc_html__('Hover Effect', 'overlap'),                    
            'admin_label' => true,
            'value' => array(
                esc_html__('None', 'overlap') => '', 
                esc_html__('Zoom In', 'overlap') => 'zoomIn', 
                esc_html__('Zoom Out', 'overlap') => 'zoomOut',
                esc_html__('Rotate Zoom In', 'overlap') => 'rotateZoomIn',
            ),
            'description' => esc_html__('Select the hover effect for image.', 'overlap'),
            'dependency' => array(
                'element' => 'gallery_type',
                'value' => array('grid', 'grid-space', 'masonry')
            )
        ),
        array(
            'param_name' => 'transition',
            'type' => 'dropdown',
            'heading' => esc_html__('Transition', 'overlap'),                    
            'value' => array(
                esc_html__('Slide', 'overlap') => '', 
                esc_html__('Fade', 'overlap') => 'fade', 
            ),
            'description' => esc_html__('The maximum amount of items displayed at a time.', 'overlap'),
            'dependency' => array(
                'element' => 'gallery_type',
                'value' => array('slider')
            )
        ),
        array(
            'param_name' => 'visible_items',
            'type' => 'dropdown',
            'heading' => esc_html__('Visible Items', 'overlap'),                    
            'value' => array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10'),
            'std' => '3',
            'description' => esc_html__('The maximum amount of items displayed at a time.', 'overlap'),
            'dependency' => array(
                'element' => 'gallery_type',
                'value' => array('slider')
            )
        ),
        array(
            'param_name' => 'show_navigation',
            'type' => 'checkbox',
            'heading' => esc_html__('Show Navigation', 'overlap'),                    
            'description' => esc_html__('Display "next" and "prev" buttons.', 'overlap'),
            'dependency' => array(
                'element' => 'gallery_type',
                'value' => array('slider')
            )
        ),
        array(
            'param_name' => 'show_pagination',
            'type' => 'checkbox',
            'heading' => esc_html__('Show Pagination', 'overlap'),                    
            'description' => esc_html__('Show pagination.', 'overlap'),
            'dependency' => array(
                'element' => 'gallery_type',
                'value' => array('slider')
            )
        ),
        array(
            'param_name' => 'auto_play',
            'type' => 'checkbox',
            'heading' => esc_html__('Auto Play', 'overlap'),                    
            'description' => esc_html__('Auto play slide.', 'overlap'),
            'dependency' => array(
                'element' => 'gallery_type',
                'value' => array('slider')
            )
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
            'description' => esc_html__('Inifnity loop. Duplicate last and first items to get loop illusion.', 'overlap'),
            'dependency' => array(
                'element' => 'gallery_type',
                'value' => array('slider')
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