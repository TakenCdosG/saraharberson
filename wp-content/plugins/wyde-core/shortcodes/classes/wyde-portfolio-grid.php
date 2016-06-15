<?php
/* Portfolio Grid
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Portfolio_Grid extends WPBakeryShortCode {
    
    public function get_masonry_layout( $layout = '' ){
        return apply_filters('wyde_portfolio_masonry_layout', $layout);
    }
    
}

vc_map( array(
    'name' => __('Portfolio Grid', 'wyde-core'),
    'description' => __('Displays Portfolio list.', 'wyde-core'),
    'base' => 'wyde_portfolio_grid',    
    'controls' => 'full',
    'icon' =>  'wyde-icon portfolio-grid-icon', 
    'weight'    => 900,
    'category' => __('Wyde', 'wyde-core'),
    'params' => array(
        array(
            'param_name' => 'title',
            'type' => 'textfield',
            'heading' => __( 'Title', 'wyde-core' ),                
            'description' => __( 'Enter text used as widget title.', 'wyde-core' ),
        ),
        array(
            'param_name' => 'subtitle',
            'type' => 'textfield',
            'heading' => __( 'Subtitle', 'wyde-core' ),                
            'description' => __( 'Enter text used as widget sub heading.', 'wyde-core' ),
        ),
        array(
            'param_name' => 'view',
            'type' => 'dropdown',                
            'heading' => __('View', 'wyde-core'),                
            'admin_label' => true,
            'value' => array(
                __('Grid (Without Space)', 'wyde-core') => 'grid', 
                __('Grid (With Space)', 'wyde-core') => 'grid-space',
                __('Photoset', 'wyde-core') => 'photoset',
                __('Masonry', 'wyde-core') => 'masonry',
            ),
            'description' => __('Select portfolio layout style.', 'wyde-core')
        ),
        array(
            'param_name' => 'columns',
            'type' => 'dropdown',
            'heading' => __('Columns', 'wyde-core'),                
            'value' => array(
                '2', 
                '3', 
                '4',
            ),
            'std' => '4',
            'description' => __('Select the number of grid columns.', 'wyde-core'),
            'dependency' => array(
	            'element' => 'view',
	            'value' => array('grid', 'grid-space', 'photoset')
	        )
        ),
        array(
            'param_name' => 'hover_effect',
            'type' => 'dropdown',
            'heading' => __('Hover Effect', 'wyde-core'),                
            'admin_label' => true,
            'value' => array(
                __('Apollo', 'wyde-core') => 'apollo', 
                __('Duke', 'wyde-core') => 'duke',
                __('Grayscale 1', 'wyde-core') => 'grayscale-1',
                __('Grayscale 2', 'wyde-core') => 'grayscale-2',                   
                __('Romeo', 'wyde-core') => 'romeo',
                __('Rotate Zoom In', 'wyde-core') => 'rotateZoomIn',                           
            ),
            'description' => __('Select the hover effect for portfolio items.', 'wyde-core'),
            'dependency' => array(
	            'element' => 'view',
	            'value' => array('grid', 'grid-space', 'masonry', 'wyde-core')
	        )
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
		    'description' => __( 'Create WordPress loop, to populate content from your site.', 'wyde-core' )
	    ),
        array(
            'param_name' => 'count',
            'type' => 'textfield',  
            'heading' => __('Post Count', 'wyde-core'),                
            'value' => '10',
            'description' => __('Number of posts to show.', 'wyde-core'),
            'dependency' => array(
                'element' => 'view',
                'value' => array('grid', 'grid-space', 'masonry', 'photoset')
            )
        ),
        array(
            'param_name' => 'hide_filter',
            'type' => 'checkbox',
            'heading' => __('Hide Filter', 'wyde-core'),                
            'description' => __('Display animated category filter to your grid.', 'wyde-core')
        ),
        array(
            'param_name' => 'pagination',
            'type' => 'dropdown',         
            'heading' => __('Pagination Type', 'wyde-core'),                
            'value' => array(
                __('Infinite Scroll', 'wyde-core') => '1',
                __('Show More Button', 'wyde-core') => '2',
                __('Hide', 'wyde-core') => 'hide',
                ),
            'description' => __('Select the pagination type.', 'wyde-core')
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