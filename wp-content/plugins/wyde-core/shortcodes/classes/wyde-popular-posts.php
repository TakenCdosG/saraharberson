<?php
/* Popular Posts
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Popular_Posts extends WPBakeryShortCode {
}

vc_map( array(
	'name' => __( 'Popular Posts', 'wyde-core' ),
    'base' => 'wyde_popular_posts',
	'icon' => 'wyde-icon popular-posts-icon',
    'weight'    => 900,
	'category' => __('Wyde', 'wyde-core'),
	'description' => __( 'Displays the popular posts with thumbnails.', 'wyde-core' ),
	"params" => array(
        array(
            'param_name' => 'count',
            'type' => 'textfield',
            'heading' => __('Post Count', 'wyde-core'),            
            'value' => '10',
            'description' => __('Number of posts to show.', 'wyde-core'),                
        ),
        array(
            'param_name' => 'show_date',
            'type' => 'checkbox',
            'heading' => __('Show Date', 'wyde-core'),            
            'description' => __('Display post date.', 'wyde-core'),            
        ),
		array(
            'param_name' => 'el_class',
			'type' => 'textfield',
			'heading' => __( 'Extra CSS Class', 'wyde-core' ),			
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
		),
	)
) );