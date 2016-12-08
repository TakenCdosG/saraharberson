<?php

/***************************************** 
/* SLIDE
/*****************************************/
vc_map( array(
    'name' => __( 'Slide', 'overlap' ),
    'base' => 'wyde_slide',
    'icon' => 'wyde-icon slide-icon',
    'content_element' => false,
    'params' => array(
        array(
            'param_name' => "slide_id",
            'type' => 'tab_id',
            'heading' => __( 'Slide ID', 'overlap' ),             
        ),
        array(
            'param_name' => 'title',
            'type' => 'textfield',
            'heading' => __( 'Title', 'overlap' ),                
            'description' => __( 'Slide heading.', 'overlap' ),
        ),
        array(
            'param_name' => 'subtitle',
            'type' => 'textfield',
            'heading' => __( 'Subtitle', 'overlap' ),             
            'description' => __( 'Slide subheading.', 'overlap' ),
        ),
        array(
            'param_name' => 'image',
            'type' => 'attach_image',
            'heading' => __( 'Image', 'overlap' ),
            'description' => __( 'Select image from media library.', 'overlap' )
        ),
        array(
            'param_name' => 'image_size',
            'type' => 'dropdown',
            'heading' => __( 'Image Size', 'overlap' ),               
            'value' => array(
                __('Medium (340x340)', 'overlap' ) => 'overlap-medium',
                __('Large (640x640)', 'overlap' ) => 'overlap-large',
                __('Extra Large (960x960)', 'overlap' ) => 'overlap-large',
                __('Landscape - Large (960x540)', 'overlap') => 'overlap-land-large',
                __('Full Width (min-width: 1280px)', 'overlap') => 'overlap-fullwidth',
                __('Original', 'overlap') => 'full',
            ),
            'description' => __( 'Select image size.', 'overlap' ),
            'std'   => 'full',
        ),
        array(
            'param_name' => 'content',
            'type' => 'textarea_html',
        ),
        array(
            'param_name' => 'el_class',
            'type' => 'textfield',
            'heading' => __( 'Extra CSS Class', 'overlap' ),              
            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'overlap' )
        ),
        array(
            'param_name' => 'css',
            'type' => 'css_editor',
            'heading' => __( 'CSS', 'overlap' ),              
            'group' => __( 'Design Options', 'overlap' )
        ),

    ),
    'js_view' => 'WydeSlideView'
) );
