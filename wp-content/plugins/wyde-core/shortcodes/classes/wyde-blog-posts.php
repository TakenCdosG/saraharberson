<?php   
/* Blog Posts
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Blog_Posts extends WPBakeryShortCode {

    public function get_masonry_layout( $layout = '' ){
        return apply_filters('wyde_blog_masonry_layout', $layout);
    }

}

vc_map( array(
    'name' => __('Blog Posts', 'wyde-core'),
    'description' => __('Displays Blog Posts list.', 'wyde-core'),
    'base' => 'wyde_blog_posts',
    'controls' => 'full',
    'icon' =>  'wyde-icon blog-posts-icon', 
    'weight'    => 900,
    'category' => __('Wyde', 'wyde-core'),
    'params' => array(
        array(
            'param_name' => 'view',
            'type' => 'dropdown',                   
            'heading' => __('Layout', 'wyde-core'),            
            'admin_label' => true,
            'value' => array(
                __('Default', 'wyde-core') => '',                        
                __('List', 'wyde-core') => 'list', 
                __('Masonry', 'wyde-core') => 'masonry',
            ),
            'description' => __('Select blog posts view.', 'wyde-core')
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
                'value' => array('masonry')
            )
        ),
        array(
            'param_name' => 'posts_query',
	        'type' => 'loop',
	        'heading' => __( 'Custom Posts', 'wyde-core' ),			        
	        'settings' => array(
                'post_type'  => array('hidden' => true),
		        'size' => array( 'hidden' => true),
		        'order_by' => array( 'value' => 'date' ),
		        'order' => array( 'value' => 'DESC' ),
	        ),
	        'description' => __( 'Create WordPress loop, to populate content from your site.', 'wyde-core' )
        ),
        array(
            'param_name'    => 'count',
            'type'      => 'textfield',                    
            'heading'     => __('Number of Posts per Page', 'wyde-core'),                       
            'value'   => '10',        
            'description'  => __('Enter the number of posts per page.', 'wyde-core'),                
        ),
        array(
            'param_name' => 'excerpt_base',
            'type' => 'dropdown',
            'heading' => __('Excerpt Limit', 'wyde-core'),
            'value' => array(
                __('Words', 'wyde-core') => '', 
                __('Characters', 'wyde-core') => '1',                       
                ),
            'description' => __('Limit the post excerpt length by using number of words or characters.', 'wyde-core'),
            
        ),
        array(
            'param_name'    => 'excerpt_length',
            'type'      => 'textfield',                    
            'heading'     => __('Excerpt Length', 'wyde-core'),                 
            'value'   => '40',          
            'description'  => __('Enter the limit of post excerpt length.', 'wyde-core'),          
                
        ),
        array(
            'param_name' => 'excerpt_more',
            'type' => 'dropdown',
            'heading' => __('Read More', 'wyde-core'),                    
            'value' => array(
                __('[...]', 'wyde-core') => '', 
                __('Link to Full Post', 'wyde-core') => '1',                       
            ),
            'description' => __('Select read more style to display after the excerpt.', 'wyde-core'),                    
        ),
        array(
            'param_name' => 'pagination',
            'type' => 'dropdown',
            'heading' => __('Pagination Type', 'wyde-core'),              
            'value' => array(
                __('Numeric Pagination', 'wyde-core') => '1', 
                __('Infinite Scroll', 'wyde-core') => '2',
                __('Next and Previous', 'wyde-core') => '3',
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