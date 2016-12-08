<?php

/***************************************** 
/* PORTFOLIO GRID
/*****************************************/
vc_map( array(
    'name' => esc_html__('Portfolio Grid', 'overlap'),
    'description' => esc_html__('Displays Portfolio list.', 'overlap'),
    'base' => 'wyde_portfolio_grid',    
    'controls' => 'full',
    'icon' =>  'wyde-icon portfolio-grid-icon', 
    'weight'    => 900,
    'category' => esc_html__('Wyde', 'overlap'),
    'params' => array(
        array(
            'param_name' => 'title',
            'type' => 'textfield',
            'heading' => esc_html__( 'Title', 'overlap' ),                
            'description' => esc_html__( 'Enter text used as widget title.', 'overlap' ),
        ),
        array(
            'param_name' => 'subtitle',
            'type' => 'textfield',
            'heading' => esc_html__( 'Subtitle', 'overlap' ),                
            'description' => esc_html__( 'Enter text used as widget sub heading.', 'overlap' ),
        ),
        array(
            'param_name' => 'view',
            'type' => 'dropdown',                
            'heading' => esc_html__('View', 'overlap'),                
            'admin_label' => true,
            'value' => array(
                esc_html__('Grid (Without Space)', 'overlap') => 'grid', 
                esc_html__('Grid (With Space)', 'overlap') => 'grid-space',
                esc_html__('Photoset', 'overlap') => 'photoset',
                esc_html__('Masonry', 'overlap') => 'masonry',
                esc_html__('Overlap', 'overlap') => 'overlap',
            ),
            'description' => esc_html__('Select portfolio layout style.', 'overlap')
        ),
        array(
            'param_name' => 'columns',
            'type' => 'dropdown',
            'heading' => esc_html__('Columns', 'overlap'),                
            'value' => array(
                '2', 
                '3', 
                '4',
            ),
            'std' => '4',
            'description' => esc_html__('Select the number of grid columns.', 'overlap'),
            'dependency' => array(
                'element' => 'view',
                'value' => array('grid', 'grid-space', 'photoset')
            )
        ),
        array(
            'param_name' => 'hover_effect',
            'type' => 'dropdown',
            'heading' => esc_html__('Hover Effect', 'overlap'),                
            'admin_label' => true,
            'value' => array(
                esc_html__('Apollo', 'overlap') => 'apollo', 
                esc_html__('Duke', 'overlap') => 'duke',
                esc_html__('Grayscale 1', 'overlap') => 'grayscale-1',
                esc_html__('Grayscale 2', 'overlap') => 'grayscale-2',                   
                esc_html__('Romeo', 'overlap') => 'romeo',
                esc_html__('Rotate Zoom In', 'overlap') => 'rotateZoomIn',                           
                esc_html__('Overlap', 'overlap') => 'overlap', 
            ),
            'description' => esc_html__('Select the hover effect for portfolio items.', 'overlap'),
            'dependency' => array(
                'element' => 'view',
                'value' => array('grid', 'grid-space', 'masonry', 'overlap')
            )
        ),
        array(
            'param_name' => 'posts_query',
            'type' => 'loop',
            'heading' => esc_html__( 'Custom Posts', 'overlap' ),             
            'settings' => array(
                'post_type'  => array('hidden' => true),
                'categories'  => array('hidden' => true),
                'tags'  => array('hidden' => true),
                'size' => array( 'hidden' => true),
                'order_by' => array( 'value' => 'date' ),
                'order' => array( 'value' => 'DESC' ),
            ),
            'description' => esc_html__( 'Create WordPress loop, to populate content from your site.', 'overlap' )
        ),
        array(
            'param_name' => 'count',
            'type' => 'textfield',  
            'heading' => esc_html__('Post Count', 'overlap'),                
            'value' => '10',
            'description' => esc_html__('Number of posts to show.', 'overlap'),
            'dependency' => array(
                'element' => 'view',
                'value' => array('grid', 'grid-space', 'masonry', 'photoset')
            )
        ),
        array(
            'param_name' => 'hide_filter',
            'type' => 'checkbox',
            'heading' => esc_html__('Hide Filter', 'overlap'),                
            'description' => esc_html__('Display animated category filter to your grid.', 'overlap')
        ),
        array(
            'param_name' => 'pagination',
            'type' => 'dropdown',         
            'heading' => esc_html__('Pagination Type', 'overlap'),                
            'value' => array(
                esc_html__('Infinite Scroll', 'overlap') => '1',
                esc_html__('Show More Button', 'overlap') => '2',
                esc_html__('Hide', 'overlap') => 'hide',
                ),
            'description' => esc_html__('Select the pagination type.', 'overlap')
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