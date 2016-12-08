<?php
/***************************************** 
/* SLIDER
/*****************************************/
$slide_id_1 = 'def' . time() . '-1-' . rand( 0, 100 );
$slide_id_2 = 'def' . time() . '-2-' . rand( 0, 100 );
vc_map( array(
    "name" => __( 'Slider', 'overlap' ),
    'base' => 'wyde_slider',
    'show_settings_on_create' => false,
    'is_container' => true,
    'wrapper_class' => 'vc_clearfix',
    'icon' => 'wyde-icon slider-icon',
    'weight'    => 900,
    'category' => __('Wyde', 'overlap'),
    'description' => __( 'Content slider', 'overlap' ),
    'params' => array(
        array(
            'param_name' => 'transition',
            'type' => 'dropdown',
            'heading' => __('Transition', 'overlap'),            
            'value' => array(
                __('Slide', 'overlap') => '', 
                __('Fade', 'overlap') => 'fade', 
            ),
            'description' => __('The maximum amount of items displayed at a time.', 'overlap'),
        ),
        array(
            'param_name' => 'auto_play',
            'type' => 'checkbox',             
            'heading' => __('Auto Play', 'overlap'),                
            'description' => __('The autoplay speed.', 'overlap'),            
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
            'param_name' => 'el_class',
            'type' => 'textfield',
            'heading' => __( 'Extra CSS Class', 'overlap' ),          
            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'overlap' )
        ),
    ),
    'custom_markup' => '<div class="wpb_tabs_holder wpb_holder vc_container_for_children"><ul class="tabs_controls"></ul>%content%</div>',
    'default_content' => '[wyde_slide title="' . esc_html__( 'Slide 1', 'overlap' ) . '" slide_id="' . $slide_id_1 . '"][/wyde_slide][wyde_slide title="' . esc_html__( 'Slide 2', 'overlap' ) . '" slide_id="' . $slide_id_2 . '"][/wyde_slide]',
    'js_view' => 'WydeSliderView',
) );