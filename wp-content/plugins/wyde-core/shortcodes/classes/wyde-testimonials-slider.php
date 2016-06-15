<?php
/* Testimonials Slider
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Testimonials_Slider extends WPBakeryShortCode {
}

vc_map( array(
    'name' => __('Testimonials Slider', 'wyde-core'),
    'description' => __('Testimonials in slider view.', 'wyde-core'),
    'base' => 'wyde_testimonials_slider',
    'controls' => 'full',
    'icon' =>  'wyde-icon testimonials-slider-icon', 
    'weight'    => 900,
    'category' => __('Wyde', 'wyde-core'),
    'params' => array(        
        array(
            'param_name' => 'posts_query',
		    'type' => 'loop',
		    'heading' => __( 'Custom Posts', 'wyde-core' ),			    
		    'settings' => array(
                'post_type'  => array('hidden' => true),
                'categories'  => array('hidden' => true),
                'tags'  => array('hidden' => true),
			    'size' => array( 'hidden' => true),
			    'order_by' => array( 'value' => 'date' ),
			    'order' => array( 'value' => 'DESC' ),
		    ),
		    'description' => __( 'Create WordPress loop, to populate content from your site.', 'wyde-core' )
	    ),
        array(
            'param_name' => 'count',
            'type' => 'textfield',                
            'heading' => __('Post Count', 'wyde-core'),                
            'value' => '10',
            'description' => __('Number of posts to show.', 'wyde-core')
        ),
        array(
            'param_name' => 'transition',
            'type' => 'dropdown',
            'heading' => __('Transition', 'wyde-core'),                
            'value' => array(
                __('Slide', 'wyde-core') => '', 
                __('Fade', 'wyde-core') => 'fade', 
            ),
            'description' => __('Select animation type.', 'wyde-core')
        ),
        array(
            'param_name' => 'show_navigation',
            'type' => 'checkbox',                
            'heading' => __('Show Navigation', 'wyde-core'),
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