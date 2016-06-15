<?php
/* Image Gallery
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Image_Gallery extends WPBakeryShortCode {
    
    public function get_masonry_layout( $layout = '1' ){
        return apply_filters('wyde_gallery_masonry_layout', $layout);
    }

}

vc_map( array(
    'name' => __('Image Gallery', 'wyde-core'),
    'description' => __('Create beautiful responsive image gallery.', 'wyde-core'),
    'base' => 'wyde_image_gallery',
    'controls' => 'full',
    'icon' =>  'wyde-icon image-gallery-icon',
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
            'param_name' => 'gallery_type',
	        'type' => 'dropdown',
	        'heading' => __( 'Gallery Type', 'wyde-core' ),			        
	        'value' => array(
                __('Grid (Without Space)', 'wyde-core') => 'grid', 
                __('Grid (With Space)', 'wyde-core') => 'grid-space',
		        __('Masonry', 'wyde-core') => 'masonry',
		        __('Slider', 'wyde-core') => 'slider',
	        ),
	        'description' => __( 'Select image size.', 'wyde-core' )
        ),
        array(
            'param_name' => 'columns',
            'type' => 'dropdown',
            'heading' => __('Columns', 'wyde-core'),                    
            'value' => array(
                '1', 
                '2', 
                '3', 
                '4',
                '5',
                '6',
            ),
            'std' => '4',
            'description' => __('Select the number of grid columns.', 'wyde-core'),
            'dependency' => array(
                'element' => 'gallery_type',
                'value' => array('grid', 'grid-space')
            )
        ),
        array(
            'param_name' => 'layout',
	        'type' => 'dropdown',
	        'heading' => __( 'Masonry Layout', 'wyde-core' ),			        
	        'value' => array(
                __('Default', 'wyde-core') => '', 
                __('Basic 1', 'wyde-core') => '1',
		        __('Basic 2', 'wyde-core') => '2',
	        ),
	        'description' => __( 'Select masonry layout.', 'wyde-core' ),
            'dependency' => array(
                'element' => 'gallery_type',
                'value' => array('masonry')
            )
        ),
        array(
            'param_name' => 'hover_effect',
            'type' => 'dropdown',
            'heading' => __('Hover Effect', 'wyde-core'),                    
            'admin_label' => true,
            'value' => array(
                __('None', 'wyde-core') => '', 
                __('Zoom In', 'wyde-core') => 'zoomIn', 
                __('Zoom Out', 'wyde-core') => 'zoomOut',
                __('Rotate Zoom In', 'wyde-core') => 'rotateZoomIn',
            ),
            'description' => __('Select the hover effect for image.', 'wyde-core'),
            'dependency' => array(
                'element' => 'gallery_type',
                'value' => array('grid', 'grid-space', 'masonry')
            )
        ),
        array(
            'param_name' => 'transition',
            'type' => 'dropdown',
            'heading' => __('Transition', 'wyde-core'),                    
            'value' => array(
                __('Slide', 'wyde-core') => '', 
                __('Fade', 'wyde-core') => 'fade', 
            ),
            'description' => __('The maximum amount of items displayed at a time.', 'wyde-core'),
            'dependency' => array(
                'element' => 'gallery_type',
                'value' => array('slider')
            )
        ),
        array(
            'param_name' => 'visible_items',
            'type' => 'dropdown',
            'heading' => __('Visible Items', 'wyde-core'),                    
            'value' => array('auto', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'),
            'std' => '3',
            'description' => __('The maximum amount of items displayed at a time.', 'wyde-core'),
            'dependency' => array(
                'element' => 'gallery_type',
                'value' => array('slider')
            )
        ),
        array(
            'param_name' => 'show_navigation',
            'type' => 'checkbox',
            'heading' => __('Show Navigation', 'wyde-core'),                    
            'description' => __('Display "next" and "prev" buttons.', 'wyde-core'),
            'dependency' => array(
                'element' => 'gallery_type',
                'value' => array('slider')
            )
        ),
        array(
            'param_name' => 'show_pagination',
            'type' => 'checkbox',
            'heading' => __('Show Pagination', 'wyde-core'),                    
            'description' => __('Show pagination.', 'wyde-core'),
            'dependency' => array(
                'element' => 'gallery_type',
                'value' => array('slider')
            )
        ),
        array(
            'param_name' => 'auto_play',
            'type' => 'checkbox',
            'heading' => __('Auto Play', 'wyde-core'),                    
            'description' => __('Auto play slide.', 'wyde-core'),
            'dependency' => array(
                'element' => 'gallery_type',
                'value' => array('slider')
            )
        ),
        array(
            'param_name' => 'loop',
            'type' => 'checkbox',
            'heading' => __('Loop', 'wyde-core'),                    
            'description' => __('Inifnity loop. Duplicate last and first items to get loop illusion.', 'wyde-core'),
            'dependency' => array(
                'element' => 'gallery_type',
                'value' => array('slider')
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