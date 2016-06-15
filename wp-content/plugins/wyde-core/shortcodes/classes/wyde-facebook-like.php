<?php
/* Facebook Like Box
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Facebook_Like extends WPBakeryShortCode {
}

vc_map( array(
	'name' => __( 'Facebook Like Box', 'wyde-core' ),
    'base' => 'wyde_facebook_like',
	'icon' => 'wyde-icon facebook-like-icon',
    'weight'    => 900,
	'category' => __('Wyde', 'wyde-core'),
	'description' => __( 'Facebook Like box.', 'wyde-core' ),
	"params" => array(        
        array(
        	'param_name' => 'page_url',
            'type' => 'textfield',
            'heading' => __('Facebook Page URL', 'wyde-core'),            
            'admin_label' => true,
            'description' => __('The URL of the Facebook Page.', 'wyde-core'),                
        ),
        array(
        	'param_name' => 'width',
            'type' => 'textfield',
            'heading' => __('Width', 'wyde-core'),            
            'value' => '340',
            'description' => __('The pixel width of the plugin. Min. is 180 & Max. is 500.', 'wyde-core'),                
        ),
        array(
        	'param_name' => 'height',
            'type' => 'textfield',
            'heading' => __('Height', 'wyde-core'),            
            'value' => '500',
            'description' => __('The pixel height of the plugin. Min. is 70.', 'wyde-core'),                
        ),
        array(
            'param_name' => 'show_facepile',
            'type' => 'checkbox',            
            'heading' => __('Show Friend\'s Faces', 'wyde-core'),            
            'description' => __('Show profile photos when friends like this.', 'wyde-core')
        ),
        array(
        	'param_name' => 'small_header',
            'type' => 'checkbox',            
			'heading' => __('Small Header', 'wyde-core'),
            'description' => __('Use the small header instead.', 'wyde-core')
        ),
        array(
        	'param_name' => 'tabs',
            'type' => 'checkbox',            
			'heading' => __('Tabs', 'wyde-core'),
            'value' => array(
                __('Timeline', 'wyde-core') => 'timeline',
                __('Events', 'wyde-core') => 'events',
                __('Messages', 'wyde-core') => 'messages'
            ),
            'description' => __('Displays timeline, events and messages tabs.', 'wyde-core')
        ),
		array(
			'param_name' => 'el_class',
			'type' => 'textfield',
			'heading' => __( 'Extra CSS Class', 'wyde-core' ),			
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
		),
	)
) );