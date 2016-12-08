<?php
    
if( ! defined( 'ABSPATH' ) ) {
    die;
}

if( ! class_exists( 'Overlap_Shortcode' ) ) {

    class Overlap_Shortcode{

    	function __construct() {

    		if( !class_exists('Wyde_Core') ){
    			// Remove all filters to remove the Visual Composer errors.
    			remove_all_filters( 'the_content' );

    			// Display the error message "Wyde Core plugin is required"
    			//add_filter( 'the_content', array($this, 'shortcode_error') );

    			// Remove shortcodes from content, display the plain content instead
    			add_filter( 'the_content', array($this, 'remove_shortcodes_from_content') );
    			return;
    		}

    		/* Visual Composer hooks */
    		add_action( 'vc_before_init', array($this, 'init_shortcodes') );
            add_filter( 'vc_load_default_templates', array($this, 'load_templates'), 100 );        
            
            add_filter( 'vc_iconpicker-type-linecons', array($this, 'get_linecons_icons'), 100 );
            add_filter( 'vc_iconpicker-type-bigmug_line', array($this, 'get_bigmug_line_icons'), 100 );
            add_filter( 'vc_iconpicker-type-simple_line', array($this, 'get_simple_line_icons'), 100 );

            /* Wyde Core hooks */
            add_action( 'wyde_load_shortcodes', array($this, 'load_shortcodes') );
    		add_action( 'wyde_update_plugins_shortcodes', array($this, 'update_plugins_shortcodes') );
    		add_filter( 'wyde_blog_masonry_layout', array($this, 'get_blog_masonry_layout') );
            add_filter( 'wyde_portfolio_masonry_layout', array($this, 'get_portfolio_masonry_layout') );
            add_filter( 'wyde_gallery_masonry_layout', array($this, 'get_gallery_masonry_layout') );
            add_filter( 'wyde_google_maps_api_key', array($this, 'get_google_maps_api_key') );
            add_filter( 'wyde_iconpicker_options', array($this, 'get_iconpicker_options'), 100 );            
            
    	}

    	function Overlap_Shortcode(){
    		$this->__construct();
    	}    	

    	function remove_shortcodes_from_content( $content ){

    		$pattern = get_shortcode_regex();
		    $content = preg_replace_callback("/$pattern/s", 'overlap_remove_shortcode', $content);
		    $content = str_replace(']]>', ']]&gt;', $content);

    		$content = 	'<div class="w-section"><div class="container"><div class="row"><div class="col col-12">'
			    		.$content
			    		.'</div></div></div></div>';

    		return $content;
    	}

    	function shortcode_error( $content ){
    		$content = '<div class="w-section"><div class="container"><div class="row"><div class="col col-12">'
    		.'<div class="page-error-wrapper">'
    		.'<h4 class="page-error-title">'. esc_html__('Wyde Core plugin is required!', 'overlap') .'</h4>'
    		.'<h6 class="page-error-text">'. esc_html__('This theme requires Wyde Core plugin for rendering shortcodes and content.', 'overlap') .'</h6>'
    		.'</div>'
    		.'</div></div></div></div>';
    		return $content;
    	}

    	public function load_templates($templates){
    		return $templates;
    	}

    	public function init_shortcodes(){
			//Set Shortcodes Templates Directory
            vc_set_shortcodes_templates_dir( get_template_directory() .'/templates/shortcodes' );
    	}

        /* Find and include all shortcodes within shortcodes folder */
	    public function load_shortcodes() {

            $files = glob( get_template_directory(). '/inc/shortcodes/*.php' );
            
            if( is_array($files) ){
    		    foreach( $files as $filename ) {
    			    include_once( $filename );
    		    }
            }
            
		    $this->update_shortcodes();
	    }

		function update_shortcodes(){
 
			/***************************************** 
            /* SINGLE IMAGE
            /*****************************************/
            vc_map( array(
                'name' => esc_html__( 'Single Image', 'overlap' ),
                'base' => 'vc_single_image',
                'icon' => 'wyde-icon image-icon',
                'weight'    => 998,
                'category' => esc_html__( 'Content', 'overlap' ),
                'description' => esc_html__( 'Insert an image', 'overlap' ),
                'params' => array(
                    array(
                        'param_name' => 'image',
                        'type' => 'attach_image',
                        'heading' => esc_html__( 'Image', 'overlap' ),                      
                        'description' => esc_html__( 'Select an image from media library.', 'overlap' )
                    ),
                    array(
                        'param_name' => 'img_size',
                        'type' => 'dropdown',
                        'heading' => esc_html__( 'Image Size', 'overlap' ),                     
                        'value' => array(
                            esc_html__('Thumbnail (150x150)', 'overlap' ) => 'thumbnail',
                            esc_html__('Medium (340x340)', 'overlap' ) => 'overlap-medium',
                            esc_html__('Large (640x640)', 'overlap' ) => 'overlap-large',
                            esc_html__('Extra Large (960x960)', 'overlap' ) => 'overlap-xlarge',
                            esc_html__('Full Width (min-width: 1280px)', 'overlap' ) => 'overlap-fullwidth',
                            esc_html__('Original', 'overlap' ) => 'full',
                        ),
                        'std'   => 'full',
                        'description' => esc_html__( 'Select image size.', 'overlap' )
                    ),
                    array(
                        'param_name' => 'style',
                        'type' => 'dropdown',
                        'heading' => esc_html__( 'Image Style', 'overlap' ),                        
                        'admin_label' => true,
                        'value' => array(
                            esc_html__('Default', 'overlap' ) => '',
                            esc_html__('Border', 'overlap' ) => 'border',
                            esc_html__('Outline', 'overlap' ) => 'outline',
                            esc_html__('Shadow', 'overlap' ) => 'shadow',
                            esc_html__('Round', 'overlap' ) => 'round',
                            esc_html__('Round Border', 'overlap' ) => 'round-border',
                            esc_html__('Round Outline', 'overlap' ) => 'round-outline', 
                            esc_html__('Round Shadow', 'overlap' ) => 'round-shadow', 
                            esc_html__('Circle', 'overlap' ) => 'circle', 
                            esc_html__('Circle Border', 'overlap' ) => 'circle-border', 
                            esc_html__('Circle Outline', 'overlap' ) => 'circle-outline',
                            esc_html__('Circle Shadow', 'overlap' ) => 'circle-shadow',
                        ),
                        'description' => esc_html__( 'Select image alignment.', 'overlap' )
                    ),
                    array(
                        'param_name' => 'border_color',
                        'type' => 'colorpicker',
                        'heading' => esc_html__( 'Border Color', 'overlap' ),                       
                        'description' => esc_html__( 'Select image border color.', 'overlap' ),
                        'dependency' => array(
                            'element' => 'style',
                            'value' => array( 'border', 'outline', 'round-border', 'round-outline', 'circle-border', 'circle-outline' )
                        )
                    ),
                    array(
                        'param_name' => 'alignment',
                        'type' => 'dropdown',
                        'heading' => esc_html__( 'Image Alignment', 'overlap' ),                        
                        'value' => array(
                            esc_html__( 'Align Left', 'overlap' ) => 'left',
                            esc_html__( 'Align Center', 'overlap' ) => 'center',
                            esc_html__( 'Align Right', 'overlap' ) => 'right',
                        ),
                        'description' => esc_html__( 'Select image alignment.', 'overlap' )
                    ),
                    array(
                        'param_name' => 'onclick',
                        'type' => 'dropdown',
                        'heading' => esc_html__( 'On Click Action', 'overlap' ),                        
                        'value' => array(
                            esc_html__( 'None', 'overlap' ) => '',
                            esc_html__( 'Link to large image', 'overlap' ) => 'img_link_large',
                            esc_html__( 'Open prettyPhoto', 'overlap' ) => 'link_image',
                            esc_html__( 'Open custom link', 'overlap' ) => 'custom_link',
                        ),
                        'description' => esc_html__( 'Select action for click action.', 'overlap' ),
                        'std' => '',
                    ),
                    array(
                        'param_name' => 'link',
                        'type' => 'href',
                        'heading' => esc_html__( 'Image Link', 'overlap' ),                     
                        'description' => esc_html__( 'Set an image link.', 'overlap' ),
                        'dependency' => array(
                            'element' => 'onclick',
                            'value' => 'custom_link',
                        ),
                    ),
                    array(
                        'param_name' => 'link_target',
                        'type' => 'dropdown',
                        'heading' => esc_html__( 'Link Target', 'overlap' ),                        
                        'value' => array(
                            esc_html__( 'Same window', 'overlap' ) => '_self',
                            esc_html__( 'New window', 'overlap' ) => "_blank",
                        ),
                        'dependency' => array(
                            'element' => 'onclick',
                            'value' =>  array( 'custom_link', 'img_link_large' )
                        )
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
                    array(
                        'param_name' => 'css',
                        'type' => 'css_editor',
                        'heading' => esc_html__( 'Css', 'overlap' ),                        
                        'group' => esc_html__( 'Design Options', 'overlap' )
                    ) 
                )
            ) );
            
		}

		public function update_plugins_shortcodes(){
            // Update plugins
        }

        public function get_google_maps_api_key(){
        	return overlap_get_option('google_maps_api_key');
        }

        public function get_iconpicker_options($options){
        	$options = array(
	            array(
	            	'param_name' => 'icon_set',
	                'type' => 'dropdown',
	                'heading' => esc_html__( 'Icon Set', 'overlap' ),
	                'value' => array(
		                'Font Awesome' => '',
		                'Typicons' => 'typicons',
		                'Linecons' => 'linecons',
	                    'Big Mug Line' => 'bigmug_line',
	                    'Simple Line' => 'simple_line',
	                ),	                
	                'description' => esc_html__('Select an icon set.', 'overlap')
	            ),
	            array(
	            	'param_name' => 'icon',
	                'type' => 'iconpicker',
	                'heading' => esc_html__( 'Icon', 'overlap' ),	                
	                'settings' => array(
		                'emptyIcon' => true, 
		                'iconsPerPage' => 4000, 
	                ),
	                'description' => esc_html__('Select an icon.', 'overlap'),
	                'dependency' => array(
		                'element' => 'icon_set',
		                'is_empty' => true,
	                ),
	            ),
	            array(
	            	'param_name' => 'icon_typicons',
	                'type' => 'iconpicker',
	                'heading' => esc_html__( 'Icon', 'overlap' ),	                
	                'settings' => array(
	                    'emptyIcon' => true, 
	                    'type' => 'typicons',
	                    'iconsPerPage' => 4000, 
	                ),
	                'description' => esc_html__('Select an icon.', 'overlap'),
	                'dependency' => array(
	                    'element' => 'icon_set',
	                    'value' => 'typicons',
	                ),
	            ),
	            array(
	            	'param_name' => 'icon_linecons',
	                'type' => 'iconpicker',
	                'heading' => esc_html__( 'Icon', 'overlap' ),	                
	                'settings' => array(
	                    'emptyIcon' => true, 
	                    'type' => 'linecons',
	                    'iconsPerPage' => 4000,
	                ),
	                'description' => esc_html__('Select an icon.', 'overlap'),
	                'dependency' => array(
	                    'element' => 'icon_set',
	                    'value' => 'linecons',
	                ),
	            ),
	            array(
					'param_name' => 'icon_bigmug_line',
	                'type' => 'iconpicker',
	                'heading' => esc_html__( 'Icon', 'overlap' ),	                
	                'settings' => array(
	                    'emptyIcon' => true, 
	                    'type' => 'bigmug_line',
	                    'iconsPerPage' => 4000,
	                ),
	                'description' => esc_html__('Select an icon.', 'overlap'),
	                'dependency' => array(
	                    'element' => 'icon_set',
	                    'value' => 'bigmug_line',
	                ),
	            ),
	            array(
	            	'param_name' => 'icon_simple_line',
	                'type' => 'iconpicker',
	                'heading' => esc_html__( 'Icon', 'overlap' ),                
	                'settings' => array(
	                    'emptyIcon' => true, 
	                    'type' => 'simple_line',
	                    'iconsPerPage' => 4000,
	                ),
	                'description' => esc_html__('Select an icon.', 'overlap'),
	                'dependency' => array(
	                    'element' => 'icon_set',
	                    'value' => 'simple_line',
	                ),
	            ),
                        

            );

			return $options;
        }		

        public function get_linecons_icons( $icons ){
        
            $icons = array(
		        array( "linecons-heart" => "Heart" ),
		        array( "linecons-cloud" => "Cloud" ),
		        array( "linecons-star" => "Star" ),
		        array( "linecons-tv" => "Tv" ),
		        array( "linecons-sound" => "Sound" ),
		        array( "linecons-video" => "Video" ),
		        array( "linecons-trash" => "Trash" ),
		        array( "linecons-user" => "User" ),
		        array( "linecons-key" => "Key" ),
		        array( "linecons-search" => "Search" ),
		        array( "linecons-settings" => "Settings" ),
		        array( "linecons-camera" => "Camera" ),
		        array( "linecons-tag" => "Tag" ),
		        array( "linecons-lock" => "Lock" ),
		        array( "linecons-bulb" => "Bulb" ),
		        array( "linecons-pen" => "Pen" ),
		        array( "linecons-diamond" => "Diamond" ),
		        array( "linecons-display" => "Display" ),
		        array( "linecons-location" => "Location" ),
		        array( "linecons-eye" => "Eye" ),
		        array( "linecons-bubble" => "Bubble" ),
		        array( "linecons-stack" => "Stack" ),
		        array( "linecons-cup" => "Cup" ),
		        array( "linecons-phone" => "Phone" ),
		        array( "linecons-news" => "News" ),
		        array( "linecons-mail" => "Mail" ),
		        array( "linecons-like" => "Like" ),
		        array( "linecons-photo" => "Photo" ),
		        array( "linecons-note" => "Note" ),
		        array( "linecons-clock" => "Clock" ),
		        array( "linecons-paperplane" => "Paperplane" ),
		        array( "linecons-params" => "Params" ),
		        array( "linecons-banknote" => "Banknote" ),
		        array( "linecons-data" => "Data" ),
		        array( "linecons-music" => "Music" ),
		        array( "linecons-megaphone" => "Megaphone" ),
		        array( "linecons-study" => "Study" ),
		        array( "linecons-lab" => "Lab" ),
		        array( "linecons-food" => "Food" ),
		        array( "linecons-t-shirt" => "T Shirt" ),
		        array( "linecons-fire" => "Fire" ),
		        array( "linecons-clip" => "Clip" ),
		        array( "linecons-shop" => "Shop" ),
		        array( "linecons-calendar" => "Calendar" ),
		        array( "linecons-wallet" => "Wallet" ),
		        array( "linecons-vynil" => "Vynil" ),
		        array( "linecons-truck" => "Truck" ),
		        array( "linecons-world" => "World" ),
	        );

            return $icons;
        }

        public function get_bigmug_line_icons( $icons ){
            $icons = array(
		        array( "bigmug-line-plus" => "Plus" ),
                array( "bigmug-line-plus-circle" => "Plus-circle" ),
                array( "bigmug-line-plus-square" => "Plus-square" ),
                array( "bigmug-line-airplane" => "airplane" ),
                array( "bigmug-line-alarm" => "Alarm" ),
                array( "bigmug-line-collapse1" => "Collapse1" ),
                array( "bigmug-line-attach1" => "Attach1" ),
                array( "bigmug-line-attach2" => "Attach2" ),
                array( "bigmug-line-volumn-off" => "Volumn-off" ),
                array( "bigmug-line-arrow-circle-left" => "Arrow-circle-left" ),
                array( "bigmug-line-arrow-square-left" => "Arrow-square-square" ),
                array( "bigmug-line-mappin" => "Map-pin" ),
                array( "bigmug-line-book" => "Book" ),
                array( "bigmug-line-bookmark" => "Bookmark" ),
                array( "bigmug-line-bottle" => "Bottle" ),
                array( "bigmug-line-th" => "Thumbnails" ),
                array( "bigmug-line-gamepad" => "Gamepad" ),
                array( "bigmug-line-tablet" => "Tablet" ),
                array( "bigmug-line-mobile" => "Mobile" ),
                array( "bigmug-line-align-center" => "Align-center" ),
                array( "bigmug-line-chat1" => "Chat1" ),
                array( "bigmug-line-chat2" => "Chat2" ),
                array( "bigmug-line-checkmark" => "Checkmark" ),
                array( "bigmug-line-checkmark-square" => "Checkmark-square" ),
                array( "bigmug-line-checkmark-circle" => "Checkmark-circle" ),
                array( "bigmug-line-certificate" => "Certificate" ),
                array( "bigmug-line-target" => "Target" ),
                array( "bigmug-line-pie-chart" => "Pie-chart" ),
                array( "bigmug-line-refresh" => "Refresh" ),
                array( "bigmug-line-clipboard" => "Clipboard" ),
                array( "bigmug-line-cross-circle" => "Cross-circle" ),
                array( "bigmug-line-cloud" => "Cloud" ),
                array( "bigmug-line-cloud-rain" => "Cloud-rain" ),
                array( "bigmug-line-glass" => "Glass" ),
                array( "bigmug-line-code" => "Code" ),
                array( "bigmug-line-collapse2" => "Collapse2" ),
                array( "bigmug-line-comment" => "Comment" ),
                array( "bigmug-line-compass" => "Compass" ),
                array( "bigmug-line-collapse-square" => "Collapse-square" ),
                array( "bigmug-line-copy" => "Copy" ),
                array( "bigmug-line-crescent" => "Crescent" ),
                array( "bigmug-line-cropping" => "Cropping" ),
                array( "bigmug-line-cross" => "Cross" ),
                array( "bigmug-line-cross-square" => "Cross-square" ),
                array( "bigmug-line-layer" => "Layer" ),
                array( "bigmug-line-arrow-v" => "Arrow-v" ),
                array( "bigmug-line-chavron-double-right" => "Arrow-double-right" ),
                array( "bigmug-line-arrow-h" => "Arrow-h" ),
                array( "bigmug-line-arrow-circle-down" => "Arrow-circle-down" ),
                array( "bigmug-line-arrow-square-down" => "Arrow-square-down" ),
                array( "bigmug-line-download1" => "Download1" ),
                array( "bigmug-line-chevron-square-down" => "Chevron-square-down" ),
                array( "bigmug-line-chevron-down" => "Chevron-down" ),
                array( "bigmug-line-download2" => "Download2" ),
                array( "bigmug-line-download3" => "Download3" ),
                array( "bigmug-line-download4" => "Download4" ),
                array( "bigmug-line-download5" => "Download5" ),
                array( "bigmug-line-arrow-circle-down" => "Arrow-circle-down" ),
                array( "bigmug-line-electrical" => "Electrical" ),
                array( "bigmug-line-electronic" => "Electronic" ),
                array( "bigmug-line-email1" => "Email1" ),
                array( "bigmug-line-email2" => "Email2" ),
                array( "bigmug-line-equalizar1" => "Equalizar1" ),
                array( "bigmug-line-equalizar2" => "Equalizar2" ),
                array( "bigmug-line-event" => "Event" ),
                array( "bigmug-line-expand-square" => "Expand-square" ),
                array( "bigmug-line-expand" => "Expand" ),
                array( "bigmug-line-forward" => "Forward" ),
                array( "bigmug-line-star" => "Star" ),
                array( "bigmug-line-file1" => "File1" ),
                array( "bigmug-line-file2" => "File2" ),
                array( "bigmug-line-film" => "Film" ),
                array( "bigmug-line-flag" => "Flag" ),
                array( "bigmug-line-foggy-moon" => "Foggy-moon" ),
                array( "bigmug-line-foggy-sun" => "Foggy-sun" ),
                array( "bigmug-line-folder" => "Folder" ),
                array( "bigmug-line-fork" => "Fork" ),
                array( "bigmug-line-th-large" => "Th-large" ),
                array( "bigmug-line-full" => "Full" ),
                array( "bigmug-line-gameboy" => "Gameboy" ),
                array( "bigmug-line-gear" => "Gear" ),
                array( "bigmug-line-giftbox" => "Giftbox" ),
                array( "bigmug-line-graphical" => "Graphical" ),
                array( "bigmug-line-headphones" => "Headphones" ),
                array( "bigmug-line-fire" => "Fire" ),
                array( "bigmug-line-images" => "Images" ),
                array( "bigmug-line-ink" => "Ink" ),
                array( "bigmug-line-tag1" => "Tag1" ),
                array( "bigmug-line-tag2" => "Tag2" ),
                array( "bigmug-line-tag3" => "Tag3" ),
                array( "bigmug-line-left-square" => "Left-square" ),
                array( "bigmug-line-chevron-left" => "Chevron-left" ),
                array( "bigmug-line-chevron-circle-left" => "Chevron-circle-left" ),
                array( "bigmug-line-chevron-square-left" => "Chevron-square-left" ),
                array( "bigmug-line-align-left" => "Align-left" ),
                array( "bigmug-line-undo" => "Undo" ),
                array( "bigmug-line-heart" => "Heart" ),
                array( "bigmug-line-link" => "Icon-googleplus" ),
                array( "bigmug-line-list1" => "List1" ),
                array( "bigmug-line-list2" => "List2" ),
                array( "bigmug-line-lock" => "Lock" ),
                array( "bigmug-line-login1" => "Login1" ),
                array( "bigmug-line-login2" => "Login2" ),
                array( "bigmug-line-map" => "Map" ),
                array( "bigmug-line-megaphone" => "Megaphone" ),
                array( "bigmug-line-menu-bar1" => "Menu-bar1" ),
                array( "bigmug-line-menu-bar2" => "Menu-bar2" ),
                array( "bigmug-line-menu-bar2" => "Menu-bar3" ),
                array( "bigmug-line-microphone1" => "Microphone1" ),
                array( "bigmug-line-microphone2" => "Microphone2" ),
                array( "bigmug-line-minus-circle" => "Minus-circle" ),
                array( "bigmug-line-minus-square" => "Minus-square" ),
                array( "bigmug-line-zoom-out" => "Zoom-out" ),
                array( "bigmug-line-minus" => "Minus" ),
                array( "bigmug-line-monitor" => "Monitor" ),
                array( "bigmug-line-music1" => "Music1" ),
                array( "bigmug-line-music2" => "Music2" ),
                array( "bigmug-line-music3" => "Music3" ),
                array( "bigmug-line-music4" => "Music4" ),
                array( "bigmug-line-music5" => "Music5" ),
                array( "bigmug-line-mute1" => "Mute1" ),
                array( "bigmug-line-mute2" => "Mute2" ),
                array( "bigmug-line-clock" => "Clock" ),
                array( "bigmug-line-edit" => "Edit" ),
                array( "bigmug-line-notebook" => "Notebook" ),
                array( "bigmug-line-notification1" => "Notification1" ),
                array( "bigmug-line-notification2" => "Notification2" ),
                array( "bigmug-line-email4" => "Email4" ),
                array( "bigmug-line-comment2" => "Comment2" ),
                array( "bigmug-line-brush" => "brush" ),
                array( "bigmug-line-paper-plane" => "Paper-plane" ),
                array( "bigmug-line-pause" => "Pause" ),
                array( "bigmug-line-pencil" => "Pencil" ),
                array( "bigmug-line-phone" => "Phone" ),
                array( "bigmug-line-camera" => "Camera" ),
                array( "bigmug-line-pin" => "Pin" ),
                array( "bigmug-line-planet" => "Planet" ),
                array( "bigmug-line-play" => "Play" ),
                array( "bigmug-line-portfolio" => "Portfolio" ),
                array( "bigmug-line-print" => "Print" ),
                array( "bigmug-line-radio" => "Radio" ),
                array( "bigmug-line-cloud-rain2" => "Cloud-rain2" ),
                array( "bigmug-line-comment3" => "Comment3" ),
                array( "bigmug-line-trash" => "Trash" ),
                array( "bigmug-line-rewind" => "Rewind" ),
                array( "bigmug-line-arrow-circle-righ" => "Circle-righ" ),
                array( "bigmug-line-map-signs" => "Map-signs" ),
                array( "bigmug-line-arrow-square-right" => "Arrow-square-right" ),
                array( "bigmug-line-right-square" => "Right-square" ),
                array( "bigmug-line-chevron-circle-right" => "Chevron-circle-right" ),
                array( "bigmug-line-redo" => "Redo" ),
                array( "bigmug-line-chevron-right" => "Chevron-right" ),
                array( "bigmug-line-chevron-square-right" => "Chevron-square-right" ),
                array( "bigmug-line-mouse" => "Mouse" ),
                array( "bigmug-line-hourglass" => "Hourglass" ),
                array( "bigmug-line-save" => "Save" ),
                array( "bigmug-line-search" => "Search" ),
                array( "bigmug-line-pin2" => "Pin2" ),
                array( "bigmug-line-share" => "share" ),
                array( "bigmug-line-shopping-bag" => "Shopping-bag" ),
                array( "bigmug-line-shopping-basket" => "Shopping-basket" ),
                array( "bigmug-line-shopping-cart1" => "Shopping-cart1" ),
                array( "bigmug-line-shopping-cart2" => "Shopping-cart2" ),
                array( "bigmug-line-shuffle" => "Shuffle" ),
                array( "bigmug-line-sort-up" => "Sort-up" ),
                array( "bigmug-line-sort-down" => "Sort-down" ),
                array( "bigmug-line-speaker" => "Speaker" ),
                array( "bigmug-line-speaker2" => "Speaker2" ),
                array( "bigmug-line-speaker3" => "Speaker3" ),
                array( "bigmug-line-volumn-up" => "Volumn-up" ),
                array( "bigmug-line-volumn-down" => "Volumn-down" ),
                array( "bigmug-line-speech" => "Speech" ),
                array( "bigmug-line-target-square" => "Target-square" ),
                array( "bigmug-line-square" => "Square" ),
                array( "bigmug-line-point" => "Point" ),
                array( "bigmug-line-store" => "Store" ),
                array( "bigmug-line-sun" => "Sun" ),
                array( "bigmug-line-sunrise" => "Sunrise" ),
                array( "bigmug-line-switch1" => "Switch1" ),
                array( "bigmug-line-switch2" => "Switch2" ),
                array( "bigmug-line-tag4" => "Tag4" ),
                array( "bigmug-line-television" => "Television" ),
                array( "bigmug-line-align-left" => "Align-left" ),
                array( "bigmug-line-text" => "Text" ),
                array( "bigmug-line-chart" => "Chart" ),
                array( "bigmug-line-timer" => "Timer" ),
                array( "bigmug-line-tool" => "Tool" ),
                array( "bigmug-line-triangle" => "Triangle" ),
                array( "bigmug-line-trophy" => "Trophy" ),
                array( "bigmug-line-refrash2" => "Refrash2" ),
                array( "bigmug-line-refrash3" => "Refrash3" ),
                array( "bigmug-line-tint" => "Tint" ),
                array( "bigmug-line-chevron-double-left" => "Chevron-double-left" ),
                array( "bigmug-line-clone" => "Clone" ),
                array( "bigmug-line-unlocked" => "Unlocked" ),
                array( "bigmug-line-chevron-circle-up" => "chevron-circle-u" ),
                array( "bigmug-line-spoon" => "Spoon" ),
                array( "bigmug-line-arrow-square-up" => "Arrow-square-up" ),
                array( "bigmug-line-upload" => "Upload" ),
                array( "bigmug-line-chevron-square-up" => "Chevron-square-up" ),
                array( "bigmug-line-home" => "Home" ),
                array( "bigmug-line-chevron-up" => "Chevron-up" ),
                array( "bigmug-line-up-square" => "Up-square" ),
                array( "bigmug-line-arrow-circle-up" => "Arrow-circle-up" ),
                array( "bigmug-line-up-square2" => "Up-square2" ),
                array( "bigmug-line-upload2" => "Upload2" ),
                array( "bigmug-line-upload3" => "Upload3" ),
                array( "bigmug-line-expand2" => "Expand2" ),
                array( "bigmug-line-user1" => "User1" ),
                array( "bigmug-line-user2" => "User2" ),
                array( "bigmug-line-video" => "Video" ),
                array( "bigmug-line-wallet" => "Wallet" ),
                array( "bigmug-line-weather" => "Weather" ),
                array( "bigmug-line-calendar1" => "Calendar1" ),
                array( "bigmug-line-calendar2" => "Calendar2" ),
                array( "bigmug-line-wind" => "Wind" ),
                array( "bigmug-line-window" => "Window" ),
                array( "bigmug-line-winds" => "Winds" ),
                array( "bigmug-line-wrench" => "Wrench" ),
                array( "bigmug-line-zoom-in" => "Zoom-in" )

	        );
            return $icons;
        }

        public function get_simple_line_icons( $icons ){
            $icons = array(
            	array( "sl-user-female" => "Female" ),
                array( "sl-user-follow" => "Follow" ),
                array( "sl-user-following" => "Following" ),
                array( "sl-user-unfollow" => "Unfollow" ),
                array( "sl-trophy" => "Trophy" ),
                array( "sl-screen-smartphone" => "Smartphone" ),
                array( "sl-screen-desktop" => "Desktop" ),
                array( "sl-plane" => "Plane" ),
                array( "sl-notebook" => "Notebook" ),
                array( "sl-moustache" => "Moustache" ),
                array( "sl-mouse" => "Mouse" ),
                array( "sl-magnet" => "Magnet" ),
                array( "sl-energy" => "Energy" ),
                array( "sl-emoticon-smile" => "Emoticon-smile" ),
                array( "sl-disc" => "Disc" ),
                array( "sl-cursor-move" => "Cursor-move" ),
                array( "sl-crop" => "Crop" ),
                array( "sl-credit-card" => "Credit-card" ),
                array( "sl-chemistry" => "Chemistry" ),
                array( "sl-user" => "User" ),
                array( "sl-speedometer" => "Speedometer" ),
                array( "sl-social-youtube" => "Youtube" ),
                array( "sl-social-twitter" => "Twitter" ),
                array( "sl-social-tumblr" => "Tumblr" ),
                array( "sl-social-facebook" => "Facebook" ),
                array( "sl-social-dropbox" => "Dropbox" ),
                array( "sl-social-dribbble" => "Dribbble" ),
                array( "sl-shield" => "Shield" ),
                array( "sl-screen-tablet" => "Tablet" ),
                array( "sl-magic-wand" => "Magic-wand" ),
                array( "sl-hourglass" => "Hourglass" ),
                array( "sl-graduation" => "Graduation" ),
                array( "sl-ghost" => "Ghost" ),
                array( "sl-game-controller" => "Game-controller" ),
                array( "sl-fire" => "Fire" ),
                array( "sl-eyeglasses" => "Eyeglasses" ),
                array( "sl-envelope-open" => "Envelope-open" ),
                array( "sl-envelope-letter" => "Envelope-letter" ),
                array( "sl-bell" => "Bell" ),
                array( "sl-badge" => "Badge" ),
                array( "sl-anchor" => "Anchor" ),
                array( "sl-wallet" => "Wallet" ),
                array( "sl-vector" => "Vector" ),
                array( "sl-speech" => "Speech" ),
                array( "sl-puzzle" => "Puzzle" ),
                array( "sl-printer" => "Printer" ),
                array( "sl-present" => "Present" ),
                array( "sl-playlist" => "Playlist" ),
                array( "sl-pin" => "Pin" ),
                array( "sl-picture" => "Picture" ),
                array( "sl-map" => "Map" ),
                array( "sl-layers" => "Layers" ),
                array( "sl-handbag" => "Handbag" ),
                array( "sl-globe-alt" => "Globe-alt" ),
                array( "sl-globe" => "Globe" ),
                array( "sl-frame" => "Frame" ),
                array( "sl-folder-alt" => "Folder-alt" ),
                array( "sl-film" => "Film" ),
                array( "sl-feed" => "Feed" ),
                array( "sl-earphones-alt" => "Earphones-alt" ),
                array( "sl-earphones" => "Earphones" ),
                array( "sl-drop" => "Drop" ),
                array( "sl-drawer" => "Drawer" ),
                array( "sl-docs" => "Docs" ),
                array( "sl-directions" => "Directions" ),
                array( "sl-direction" => "Direction" ),
                array( "sl-diamond" => "Diamond" ),
                array( "sl-cup" => "Cup" ),
                array( "sl-compass" => "Compress" ),
                array( "sl-call-out" => "Call-out" ),
                array( "sl-call-in" => "Call-in" ),
                array( "sl-call-end" => "Call-end" ),
                array( "sl-calculator" => "Calculator" ),
                array( "sl-bubbles" => "Bubbles" ),
                array( "sl-briefcase" => "Briefcase" ),
                array( "sl-book-open" => "Book-open" ),
                array( "sl-basket-loaded" => "Basket-loaded" ),
                array( "sl-basket" => "Basket" ),
                array( "sl-bag" => "Bag" ),
                array( "sl-action-undo" => "Action-undo" ),
                array( "sl-action-redo" => "Action-redo" ),
                array( "sl-wrench" => "Wrench" ),
                array( "sl-umbrella" => "Umbrella" ),
                array( "sl-trash" => "Trash" ),
                array( "sl-tag" => "Tag" ),
                array( "sl-support" => "Support" ),
                array( "sl-size-fullscreen" => "Size-fullscreen" ),
                array( "sl-size-actual" => "Size-actual" ),
                array( "sl-shuffle" => "Shuffle" ),
                array( "sl-share-alt" => "Share-alt" ),
                array( "sl-share" => "Share" ),
                array( "sl-rocket" => "Rocket" ),
                array( "sl-question" => "Question" ),
                array( "sl-pie-chart" => "Pie-chart" ),
                array( "sl-pencil" => "Pencil" ),
                array( "sl-note" => "Note" ),
                array( "sl-music-tone-alt" => "Music-tone-alt" ),
                array( "sl-music-tone" => "Music-tone" ),
                array( "sl-microphone" => "Microphone" ),
                array( "sl-loop" => "Loop" ),
                array( "sl-logout" => "Logout" ),
                array( "sl-login" => "Login" ),
                array( "sl-list" => "List" ),
                array( "sl-like" => "Like" ),
                array( "sl-home" => "Home" ),
                array( "sl-grid" => "Grid" ),
                array( "sl-graph" => "Graph" ),
                array( "sl-equalizer" => "Equalizer" ),
                array( "sl-dislike" => "Dislike" ),
                array( "sl-cursor" => "Cursor" ),
                array( "sl-control-start" => "Control-start" ),
                array( "sl-control-rewind" => "Control-rewind" ),
                array( "sl-control-play" => "Control-play" ),
                array( "sl-control-pause" => "Control-pause" ),
                array( "sl-control-forward" => "Control-forward" ),
                array( "sl-control-end" => "Control-end" ),
                array( "sl-calendar" => "Calendar" ),
                array( "sl-bulb" => "Bulb" ),
                array( "sl-bar-chart" => "Bar-chart" ),
                array( "sl-arrow-up" => "Arrow-up" ),
                array( "sl-arrow-right" => "Arrow-right" ),
                array( "sl-arrow-left" => "Arrow-left" ),
                array( "sl-arrow-down" => "Arrow-down" ),
                array( "sl-ban" => "Ban" ),
                array( "sl-bubble" => "Bubble" ),
                array( "sl-camcorder" => "Camcorder" ),
                array( "sl-camera" => "Camera" ),
                array( "sl-check" => "Check" ),
                array( "sl-clock" => "Clock" ),
                array( "sl-close" => "Close" ),
                array( "sl-cloud-download" => "Cloud-download" ),
                array( "sl-cloud-upload" => "Cloud-upload" ),
                array( "sl-doc" => "Doc" ),
                array( "sl-envelope" => "Envelope" ),
                array( "sl-eye" => "Eye" ),
                array( "sl-flag" => "Flag" ),
                array( "sl-folder" => "Folder" ),
                array( "sl-heart" => "Heart" ),
                array( "sl-info" => "Info" ),
                array( "sl-key" => "Key" ),
                array( "sl-link" => "Link" ),
                array( "sl-lock" => "Lock" ),
                array( "sl-lock-open" => "Lock-open" ),
                array( "sl-magnifier" => "Magnifier" ),
                array( "sl-magnifier-add" => "Magnifier-add" ),
                array( "sl-magnifier-remove" => "Magnifier-remove" ),
                array( "sl-paper-clip" => "Paper-clip" ),
                array( "sl-paper-plane" => "Paper-plane" ),
                array( "sl-plus" => "Plus" ),
                array( "sl-pointer" => "Pointer" ),
                array( "sl-power" => "Power" ),
                array( "sl-refresh" => "Refresh" ),
                array( "sl-reload" => "Reload" ),
                array( "sl-settings" => "Settings" ),
                array( "sl-star" => "Star" ),
                array( "sl-symbol-female" => "Symbol-female" ),
                array( "sl-symbol-male" => "Symbol-male" ),
                array( "sl-target" => "Target" ),
                array( "sl-volume-1" => "Volume-1" ),
                array( "sl-volume-2" => "Volume-2" ),
                array( "sl-volume-off" => "Volume-off" ),
                array( "sl-users" => "Users" ),
	        );
            return $icons;
        }

        public function get_blog_masonry_layout( $layout = '' ){
            
            $masonry_layout = array();

          	switch( $layout ){
		        case 'w-masonry':
		        $masonry_layout = array('w-item w-w2 w-h2', 'w-item', 'w-item w-h2', 'w-item w-h2', 'w-item', 'w-item w-w2', 'w-item w-item-h', 'w-item', 'w-item w-w2 w-h2', 'w-item w-h2', 'w-item w-w2', 'w-item w-h2', 'w-item', 'w-item w-item-h', 'w-item', 'w-item w-h2', 'w-item w-w2 w-h2', 'w-item w-h2', 'w-item w-w2', 'w-item', 'w-item w-item-h');
		        break;
		        default:
		        $masonry_layout = array('w-item', 'w-item w-h2', 'w-item', 'w-item w-h2', 'w-item w-h2', 'w-item w-h2', 'w-item', 'w-item');
		        break;
		    }

		    return $masonry_layout;
		}

        public function get_portfolio_masonry_layout( $layout = '' ){
             
            $masonry_layout = array();
		      
            switch( $layout ){
		        case 'masonry':
		        $masonry_layout = array('w-item w-h2', 'w-item w-w2 w-h2', 'w-item', 'w-item', 'w-item', 'w-item', 'w-item w-w2 w-h2', 'w-item w-w2', 'w-item w-w2 w-h2', 'w-item', 'w-item w-h2', 'w-item');
		        break;
		        default:
		        $masonry_layout = array('w-item', 'w-item w-h2');
		        break;
		    }

		    return $masonry_layout;
		}

		public function get_gallery_masonry_layout( $layout = '' ){
			
            $masonry_layout = array();
	        
            switch( $layout ){
	            case '1':
	            $masonry_layout = array('w-item', 'w-item w-h2', 'w-item', 'w-item w-h2', 'w-item w-h2', 'w-item w-h2', 'w-item', 'w-item');
	            break;
	            case '2':
	            $masonry_layout = array('w-item w-h2', 'w-item', 'w-item w-h2', 'w-item', 'w-item', 'w-item w-h2', 'w-item w-h2', 'w-item w-h2', 'w-item w-h2', 'w-item', 'w-item');
	            break;
	            default:
	            $masonry_layout = $this->get_portfolio_masonry_layout( 'masonry' );
	            break;
	        }

	        return $masonry_layout;
		}

	}

	new Overlap_Shortcode();

}