<?php
/***************************************** 
/* TESTIMONIALS SLIDER
/*****************************************/
vc_map( array(
    'name' => __('Testimonials Slider', 'overlap'),
    'description' => __('Testimonials in slider view.', 'overlap'),
    'base' => 'wyde_testimonials_slider',
    'controls' => 'full',
    'icon' =>  'wyde-icon testimonials-slider-icon', 
    'weight'    => 900,
    'category' => __('Wyde', 'overlap'),
    'params' => array(
        array(
            'param_name' => 'title',
            'type' => 'textfield',
            'heading' => __( 'Title', 'overlap' ),                
            'description' => __( 'Enter text used as widget title.', 'overlap' ),
        ),
        array(
            'param_name' => 'subtitle',
            'type' => 'textfield',
            'heading' => __( 'Subtitle', 'overlap' ),                
            'description' => __( 'Enter text used as widget sub heading.', 'overlap' ),
        ),
        array(
            'param_name' => 'posts_query',
            'type' => 'loop',
            'heading' => __( 'Custom Posts', 'overlap' ),             
            'settings' => array(
                'post_type'  => array('hidden' => true),
                'categories'  => array('hidden' => true),
                'tags'  => array('hidden' => true),
                'size' => array( 'hidden' => true),
                'order_by' => array( 'value' => 'date' ),
                'order' => array( 'value' => 'DESC' ),
            ),
            'description' => __( 'Create WordPress loop, to populate content from your site.', 'overlap' )
        ),
        array(
            'param_name' => 'count',
            'type' => 'textfield',                
            'heading' => __('Post Count', 'overlap'),                
            'value' => '10',
            'description' => __('Number of posts to show.', 'overlap')
        ),
        array(
            'param_name' => 'transition',
            'type' => 'dropdown',
            'heading' => __('Transition', 'overlap'),                
            'value' => array(
                __('Slide', 'overlap') => '', 
                __('Fade', 'overlap') => 'fade', 
            ),
            'description' => __('Select animation type.', 'overlap')
        ),
        array(
            'param_name' => 'show_navigation',
            'type' => 'checkbox',                
            'heading' => __('Show Navigation', 'overlap'),
            'description' => __('Display "next" and "prev" buttons.', 'overlap')
        ),
        array(
            'param_name' => 'show_pagination',
            'type' => 'checkbox',
            'heading' => __('Show Pagination', 'overlap'),                
            'description' => __('Show pagination.', 'overlap')
        ),
        array(
            'param_name' => 'auto_play',
            'type' => 'checkbox',
            'heading' => __('Auto Play', 'overlap'),                
            'description' => __('Auto play slide.', 'overlap')
        ),
        array(
            'param_name' => 'animation',
            'type' => 'wyde_animation',
            'heading' => __('Animation', 'overlap'),                
            'description' => __('Select a CSS3 Animation that applies to this element.', 'overlap')
        ),
        array(
            'param_name' => 'animation_delay',
            'type' => 'textfield',
            'heading' => __('Animation Delay', 'overlap'),
            'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'overlap'),
            'dependency' => array(
                'element' => 'animation',
                'not_empty' => true
            )
        ),
        array(
            'param_name' => 'el_class',
            'type' => 'textfield',
            'heading' => __( 'Extra CSS Class', 'overlap' ),              
            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'overlap' )
        ),
    )
) );