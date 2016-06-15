<?php
/* Team Members Slider
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Team_Slider extends WPBakeryShortCode {
}

vc_map( array(
    'name' => __('Team Members Slider', 'wyde-core'),
    'description' => __('Team Members in slider view.', 'wyde-core'),
    'base' => 'wyde_team_slider',
    'controls' => 'full',
    'icon' =>  'wyde-icon team-slider-icon', 
    'weight'    => 900,
    'category' => __('Wyde', 'wyde-core'),
    'params' => array(
        array(
            'param_name' => 'hide_member_name',
            'type' => 'checkbox',
            'heading' => __('Hide Member Name', 'wyde-core'),                
            'description' => __('Check this to hide member\'s name on cover image.', 'wyde-core')
        ),
        array(
            'param_name' => 'color',
            'type' => 'colorpicker',                
            'heading' => __('Member Name Color', 'wyde-core'),                
            'description' => __('Select color of member\'s name (on cover image).', 'wyde-core')
        ),
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
		    'description' => __( 'Create custom query, to populate content from your site.', 'wyde-core' )
	    ),
        array(
            'param_name' => 'count',
            'type' => 'textfield',
            'heading' => __('Post Count', 'wyde-core'),                
            'value' => '10',
            'description' => __('Number of posts to show.', 'wyde-core'),                
        ),
        array(
            'param_name' => 'layout',
            'type' => 'dropdown',
            'heading' => __('Layout', 'wyde-core'),                
            'value' => array(
                __('Slider', 'wyde-core') => '', 
                __('Grid', 'wyde-core') => 'grid', 
            ),
            'description' => __('Select a post layout.', 'wyde-core'),                
        ),
        array(
            'param_name' => 'columns',
            'type' => 'dropdown',
            'heading' => __('Columns', 'wyde-core'),                
            'value' => array('1', '2', '3', '4', '5'),
            'std' => '3',
            'description' => __('Select a grid columns.', 'wyde-core'),
            'dependency' => array(
                'element' => 'layout',
                'value' => array('grid')
            )
        ),
        array(
            'param_name' => 'visible_items',
            'type' => 'dropdown',
            'heading' => __('Visible Items', 'wyde-core'),                
            'value' => array('1', '2', '3', '4', '5'),
            'std' => '3',
            'description' => __('The maximum amount of items displayed at a time.', 'wyde-core'),
            'dependency' => array(
                'element' => 'layout',
                'is_empty' => true
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
            'description' => __('Select animation type.', 'wyde-core'),
            'dependency' => array(
			    'element' => 'visible_items',
			    'value' => array('1')
		    )
        ),
        array(
            'param_name' => 'show_navigation',
            'type' => 'checkbox',
            'heading' => __('Show Navigation', 'wyde-core'),                
            'description' => __('Display "next" and "prev" buttons.', 'wyde-core'),
            'dependency' => array(
                'element' => 'layout',
                'is_empty' => true
            )
        ),
        array(
            'param_name' => 'show_pagination',
            'type' => 'checkbox',
            'heading' => __('Show Pagination', 'wyde-core'),                
            'description' => __('Show pagination.', 'wyde-core'),
            'dependency' => array(
                'element' => 'layout',
                'is_empty' => true
            )
        ),
        array(
            'param_name' => 'slide_loop',
            'type' => 'checkbox',
            'heading' =>  __('Loop', 'wyde-core'),                
            'description' => __('Inifnity loop. Duplicate last and first items to get loop illusion.', 'wyde-core'),
            'dependency' => array(
                'element' => 'layout',
                'is_empty' => true
            )
        ),
        array(
            'param_name' => 'auto_play',
            'type' => 'checkbox',             
            'heading' => __('Auto Play', 'wyde-core'),                
            'description' => __('Auto play slide.', 'wyde-core'),
            'dependency' => array(
                'element' => 'layout',
                'is_empty' => true
            )
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