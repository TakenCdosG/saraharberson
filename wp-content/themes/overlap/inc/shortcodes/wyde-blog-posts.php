<?php
/***************************************** 
/* BLOG POSTS
/*****************************************/
vc_map( array(
    'name' => esc_html__('Blog Posts', 'overlap'),
    'description' => esc_html__('Displays Blog Posts list.', 'overlap'),
    'base' => 'wyde_blog_posts',
    'controls' => 'full',
    'icon' =>  'wyde-icon blog-posts-icon', 
    'weight'    => 900,
    'category' => esc_html__('Wyde', 'overlap'),
    'params' => array(
        array(
            'param_name' => 'view',
            'type' => 'dropdown',                   
            'heading' => esc_html__('Layout', 'overlap'),            
            'admin_label' => true,
            'value' => array(
                esc_html__('Default', 'overlap') => '',                        
                esc_html__('Masonry', 'overlap') => 'masonry',
                esc_html__('Overlap', 'overlap') => 'overlap', 
            ),
            'description' => esc_html__('Select blog posts view.', 'overlap')
        ),
        array(
            'param_name' => 'posts_query',
            'type' => 'loop',
            'heading' => esc_html__( 'Custom Posts', 'overlap' ),                 
            'settings' => array(
                'post_type'  => array('hidden' => true),
                'size' => array( 'hidden' => true),
                'order_by' => array( 'value' => 'date' ),
                'order' => array( 'value' => 'DESC' ),
            ),
            'description' => esc_html__( 'Create WordPress loop, to populate content from your site.', 'overlap' )
        ),
        array(
            'param_name'    => 'count',
            'type'      => 'textfield',                    
            'heading'     => esc_html__('Number of Posts per Page', 'overlap'),                       
            'value'   => '10',        
            'description'  => esc_html__('Enter the number of posts per page.', 'overlap'),                
        ),
        array(
            'param_name' => 'excerpt_base',
            'type' => 'dropdown',
            'heading' => esc_html__('Excerpt Limit', 'overlap'),
            'value' => array(
                esc_html__('Words', 'overlap') => '', 
                esc_html__('Characters', 'overlap') => '1',                       
                ),
            'description' => esc_html__('Limit the post excerpt length by using number of words or characters.', 'overlap'),
            
        ),
        array(
            'param_name'    => 'excerpt_length',
            'type'      => 'textfield',                    
            'heading'     => esc_html__('Excerpt Length', 'overlap'),                 
            'value'   => '40',          
            'description'  => esc_html__('Enter the limit of post excerpt length.', 'overlap'),          
                
        ),
        array(
            'param_name' => 'excerpt_more',
            'type' => 'dropdown',
            'heading' => esc_html__('Read More', 'overlap'),                    
            'value' => array(
                esc_html__('[...]', 'overlap') => '', 
                esc_html__('Link to Full Post', 'overlap') => '1',                       
            ),
            'description' => esc_html__('Select read more style to display after the excerpt.', 'overlap'),                    
        ),
        array(
            'param_name' => 'pagination',
            'type' => 'dropdown',
            'heading' => esc_html__('Pagination Type', 'overlap'),              
            'value' => array(
                esc_html__('Numeric Pagination', 'overlap') => '1', 
                esc_html__('Infinite Scroll', 'overlap') => '2',
                esc_html__('Next and Previous', 'overlap') => '3',
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
