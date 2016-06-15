<?php
if( !class_exists('Wyde_Shortcode') ){


    class Wyde_Shortcode{

        function __construct() {

		    add_action( 'init', array($this, 'init'));
            add_action( 'init', array($this, 'deregister_grid_element'), 100);
            add_action( 'wp_enqueue_scripts', array($this, 'load_shortcodes_scripts' ) );
            add_action( 'admin_enqueue_scripts', array($this, 'register_admin_scripts' ) );
            add_action( 'plugins_loaded', array( $this, 'integrate_with_vc' ), 100 );
            add_action( 'plugins_loaded', array( $this, 'revslider_set_as_theme' ), 100 );
            add_action( 'wp_footer', array($this, 'body_stylesheets') );

            /** Visual Composer hooks **/
            add_action( 'vc_before_init', array($this, 'vc_before_init') );
            add_action( 'vc_after_init', array($this, 'vc_after_init') );
            add_action( 'vc_load_default_params', array($this, 'load_default_params') );
            add_action( 'vc_mapper_init_after', array($this, 'vc_mapper_init_after') );
            add_action( 'vc_after_init_base', array($this, 'vc_after_init_base') );
            add_action( 'vc_settings_tabs', array($this, 'vc_settings_tabs') );  
            add_action( 'vc_backend_editor_enqueue_js_css', array($this, 'load_editor_scripts'));
            add_action( 'vc_frontend_editor_enqueue_js_css', array($this, 'load_editor_scripts'));
            add_filter( 'vc_load_default_templates', array($this, 'clear_default_templates'), 50 );
            add_filter( 'vc_google_fonts_get_fonts_filter', array($this, 'get_google_fonts'), 100 );            

            add_filter( 'wyde_iconpicker_options', array($this, 'get_iconpicker_options') );

	    }

        function init() {

            if ( get_user_option('rich_editing') == 'true' ) {
                add_filter( 'tiny_mce_before_init', array( $this, 'init_tinymce' ) );
                add_filter( 'mce_buttons', array( $this, 'register_buttons' ), 500 );
                add_filter( 'mce_external_plugins', array( $this, 'add_buttons' ) );
		    }
    
	    }

        /* Initialize tinymce settings */
        function init_tinymce( $in ) {
	        $in['toolbar1'] = 'bold,italic,underline,strikethrough,dropcap,highlight,bullist,numlist,blockquote,alignleft,aligncenter,alignright,alignjustify,link,unlink,wp_fullscreen,wp_adv';
	        $in['toolbar2'] = 'formatselect,fontsizeselect,forecolor,backcolor,hr,removeformat,charmap,outdent,indent,wp_more,spellchecker,wp_help';
	        return $in;
        }

        /* Register tinymce editor buttons */
        public function register_buttons( $buttons ) {

            //Remove buttons
            $removes = array('revslider', 'more');

            //Find the array key and then unset
            foreach( $removes as $remove){
              if ( ( $key = array_search($remove, $buttons) ) !== false )	unset($buttons[$key]);
            }

            return $buttons;
        }

        /* Add Wyde button plugins */
        public function add_buttons( $plugin_array ) {
            $plugin_array['wydeEditor'] = WYDE_PLUGIN_URI. 'shortcodes/js/editor-plugin.js';
            return $plugin_array;
        }

        /* Register Admin scripts */
        public function register_admin_scripts(){
            // Register Google Maps scripts
            $this->register_google_maps_scripts();
        }

        /* Load plugin css and javascript files which you may need for shortcodes */
        public function load_shortcodes_scripts() {

            // Register Google Maps scripts
            $this->register_google_maps_scripts();

            // Animations 
            wp_enqueue_style( 'wyde-animations', WYDE_PLUGIN_URI. 'shortcodes/css/animation.css', null, WYDE_VERSION);

            // Shortcodes scripts
            wp_enqueue_script('wyde-shortcodes', WYDE_PLUGIN_URI. 'shortcodes/js/shortcodes.js', array('wyde-core'), WYDE_VERSION, true);
 
        }       

        /* Load plugin css and javascript files which you may need for backend editor*/
        public function load_editor_scripts() {
            // Animations styles
            wp_enqueue_style( 'wyde-animations', WYDE_PLUGIN_URI. 'shortcodes/css/animation.css', null, WYDE_VERSION);

            // Backend styles
            wp_enqueue_style( 'wyde-backend-style', WYDE_PLUGIN_URI. 'shortcodes/css/backend.css', null, WYDE_VERSION);
            
            // Backend scripts
            wp_enqueue_script('wyde-backend-script', WYDE_PLUGIN_URI. 'shortcodes/js/backend.js', array('vc-backend-min-js'), WYDE_VERSION, true);
            
            // Google Maps scripts
            $this->load_google_maps_scripts();
        }

        /* Register Google Maps scripts */
        public function register_google_maps_scripts(){

            // Google Maps API key, see -> https://developers.google.com/maps/documentation/javascript/get-api-key#key
            $api_key = apply_filters('wyde_google_maps_api_key', '');

            if( !empty($api_key) ){
                $api_key = '?key='.$api_key;
            }

            // Google Maps scripts
            wp_register_script('googlemaps', 'https://maps.googleapis.com/maps/api/js'.$api_key, null, null, false);
        }

        /* Load Google Maps scripts */
        public function load_google_maps_scripts(){
            // Google Maps scripts
            wp_enqueue_script('googlemaps');
        }

        /* Set the RevSlider Plugin as a Theme. This hides the activation notice and the activation area in the Slider Overview */
        function revslider_set_as_theme(){
            global $revSliderAsTheme;

            if( function_exists('set_revslider_as_theme') ){
                $revSliderAsTheme = true;
                update_option('revslider-valid-notice', 'off');
                add_filter('revslider_set_notifications', array($this, 'revslider_set_notifications') );

                remove_action('admin_notices', array('RevSliderAdmin', 'add_plugins_page_notices'));
            }
        }

        /* Disable update notifications */
        function revslider_set_notifications(){
            return 'off';
        }

        /* Integrate with Visual Composer */
        function integrate_with_vc() {
            // Check if Visual Composer is installed
            if ( ! defined( 'WPB_VC_VERSION' ) ) {
                return;
            }

            remove_action( 'vc_activation_hook', 'vc_page_welcome_set_redirect' );
            
            remove_action( 'init', 'vc_page_welcome_redirect' );

            if( function_exists( 'vc_manager' ) && isset( $_GET['post'] ) && $_GET['post'] === get_option( 'page_for_posts' ) ){
                remove_action( 'init', array( vc_manager(), 'init' ), 9 );
            }
            
            include WYDE_PLUGIN_DIR. 'shortcodes/css_editor.php';                     

            $this->update_plugins();
           
        }

        /* Update plugins shortcodes */
        function update_plugins(){       

            remove_action( 'init', array('RevSliderTinyBox', 'add_to_VC' ));
        
            //add_action( 'vc_build_admin_page', array($this, 'update_woocommerce_shortcodes'), 11 );
            //add_action( 'vc_load_shortcode', array($this, 'update_woocommerce_shortcodes'), 11 );

            add_action( 'vc_after_mapping', array( $this, 'update_plugins_shortcodes') );

        }

        /* Add action before vc init */
        public function vc_before_init() {   
            //Disable automatic updates notifications
            vc_set_as_theme(true);
            vc_manager()->disableUpdater(true);

            remove_action( 'admin_enqueue_scripts', 'vc_pointer_load' );
            remove_action( 'admin_init', 'vc_add_admin_pointer' );
            remove_action( 'admin_init', 'vc_frontend_editor_pointer' );
            //Set Default Editor Post Types
            //vc_set_default_editor_post_types( array('page', 'post', 'wyde_portfolio') );
        }

        public function vc_after_init() {
            //Remove license activation notification
            remove_action( 'admin_notices', array( vc_license(), 'adminNoticeLicenseActivation' ) );
            //Remove vc edit button from admin bar
            remove_action( 'admin_bar_menu', array( vc_frontend_editor(), 'adminBarEditLink' ), 1000 );
            //Remove vc edit button from wp edit links
            remove_filter( 'edit_post_link', array( vc_frontend_editor(), 'renderEditButton' ) );
            //Disable frontend editor
            vc_disable_frontend(); 

        }

        function load_default_params(){
            global $vc_params_list;

            WpbakeryShortcodeParams::addField('wyde_animation', array( $this, 'animation_field'), WYDE_PLUGIN_URI. 'shortcodes/js/wyde-animation.js?v='.WYDE_VERSION);
            WpbakeryShortcodeParams::addField('wyde_gmaps', array( $this, 'gmaps_field'), WYDE_PLUGIN_URI. 'shortcodes/js/wyde-gmaps.js?v='.WYDE_VERSION); 
            
            if ( empty( $vc_params_list ) ) {
                return false;
            }
            $script_url = WYDE_PLUGIN_URI. 'shortcodes/js/edit-form.js?v='.WYDE_VERSION;
            foreach ( $vc_params_list as $param ) {
                //vc_add_shortcode_param( $param, 'vc_' . $param . '_form_field', $script_url );
                WpbakeryShortcodeParams::addField( $param, 'vc_' . $param . '_form_field', $script_url );
            }           
        }

        function vc_mapper_init_after(){     

            remove_filter( 'vc_iconpicker-type-fontawesome', 'vc_iconpicker_type_fontawesome' );
            remove_filter( 'vc_iconpicker-type-openiconic', 'vc_iconpicker_type_openiconic' );
            remove_filter( 'vc_iconpicker-type-typicons', 'vc_iconpicker_type_typicons' );
            remove_filter( 'vc_iconpicker-type-entypo', 'vc_iconpicker_type_entypo' );
            remove_filter( 'vc_iconpicker-type-linecons', 'vc_iconpicker_type_linecons' );

            // Add Flora theme shortcodes
            $this->load_shortcodes();    

            // Update Visual Composer shortcodes
            $this->update_vc_shortcodes(); 

            do_action('wyde_load_shortcodes');
            
        }
   
        public function vc_after_init_base() {

            /*
            global $vc_row_layouts;
            $vc_row_layouts = array(
                array( 'cells' => '11', 'mask' => '12', 'title' => '1/1', 'icon_class' => 'col-1' ),
                array( 'cells' => '12_12', 'mask' => '26', 'title' => '1/2 + 1/2', 'icon_class' => 'col-12-12' ),
                array( 'cells' => '23_13', 'mask' => '29', 'title' => '2/3 + 1/3', 'icon_class' => 'col-23-13' ),
                array( 'cells' => '13_23', 'mask' => '29', 'title' => '1/3 + 2/3', 'icon_class' => 'col-13-23' ),
                array( 'cells' => '13_13_13', 'mask' => '312', 'title' => '1/3 + 1/3 + 1/3', 'icon_class' => 'col-13-13-13' ),
                array( 'cells' => '14_14_14_14', 'mask' => '420', 'title' => '1/4 + 1/4 + 1/4 + 1/4', 'icon_class' => 'col-14-14-14-14' ),
                array( 'cells' => '14_34', 'mask' => '212', 'title' => '1/4 + 3/4', 'icon_class' => 'col-14-34' ),
                array( 'cells' => '14_12_14', 'mask' => '313', 'title' => '1/4 + 1/2 + 1/4', 'icon_class' => 'col-14-12-14' ),
                array( 'cells' => '56_16', 'mask' => '218', 'title' => '5/6 + 1/6', 'icon_class' => 'col-56-16' ),
                array( 'cells' => '16_16_16_16_16_16', 'mask' => '642', 'title' => '1/6 + 1/6 + 1/6 + 1/6 + 1/6 + 1/6', 'icon_class' => 'col-16-16-16-16-16-16' ),
                array( 'cells' => '16_23_16', 'mask' => '319', 'title' => '1/6 + 4/6 + 1/6', 'icon_class' => 'col-16-46-16' ),
                array( 'cells' => '16_16_16_12', 'mask' => '424', 'title' => '1/6 + 1/6 + 1/6 + 1/2', 'icon_class' => 'col-16-16-16-12' ),
                array( 'cells' => '15_15_15_15_15', 'mask' => '530', 'title' => '1/5 + 1/5 + 1/5 + 1/5 + 1/5', 'icon_class' => 'col-15-15-15-15-15' )
            );
            */
            $this->vc_metadata();
        }
        

        function vc_settings_tabs($tabs){
            unset( $tabs['vc-updater'] );
            return $tabs;
        }       


        function clear_default_templates( $templates ){
            $templates = array();
            return $templates;
        }       

        /* Find and include all shortcode classes within classes folder */
	    public function load_shortcodes() {

            $files = glob( WYDE_PLUGIN_DIR. 'shortcodes/classes/*.php' );
            
            if( is_array($files) ){
                foreach( $files as $filename ) {
                    include_once( $filename );
                }
            }

	    }

        /* Update VC elements */
        public function update_vc_shortcodes(){

            //Remove VC elements            
            vc_remove_element('vc_icon');
            vc_remove_element('vc_separator');
            vc_remove_element('vc_text_separator');
            vc_remove_element('vc_message');
            vc_remove_element('vc_facebook');
            vc_remove_element('vc_tweetmeme');
            vc_remove_element('vc_googleplus');
            vc_remove_element('vc_pinterest');
            vc_remove_element('vc_toggle');
            vc_remove_element('vc_gallery');
            vc_remove_element('vc_images_carousel');
            vc_remove_element('vc_tta_tabs');
            vc_remove_element('vc_tta_tour');
            vc_remove_element('vc_tta_accordion');
            vc_remove_element('vc_tta_pageable');
            vc_remove_element('vc_tta_section');
            vc_remove_element('vc_btn');
            vc_remove_element('vc_cta');

            vc_remove_element('vc_posts_slider');
            vc_remove_element('vc_gmaps');
            vc_remove_element('vc_flickr');

            vc_remove_element('vc_progress_bar');
            vc_remove_element('vc_pie');
            vc_remove_element('vc_round_chart');
            vc_remove_element('vc_line_chart');

            vc_remove_element('vc_basic_grid');
            vc_remove_element('vc_media_grid');
            vc_remove_element('vc_masonry_grid');
            vc_remove_element('vc_masonry_media_grid');

            vc_remove_element('vc_posts_grid');
            vc_remove_element('vc_carousel');
            vc_remove_element('vc_button');
            vc_remove_element('vc_button2');
            vc_remove_element('vc_cta_button');
            vc_remove_element('vc_cta_button2'); 


            $icon_picker_options = array();
            $icon_picker_options = apply_filters('wyde_iconpicker_options', $icon_picker_options);

            /***************************************** 
            /* Row
            /*****************************************/
            vc_map( array(
                'name' => __( 'Row', 'wyde-core' ),
                'base' => 'vc_row',
                'weight'    => 1001,
                'is_container' => true,
                'icon' => 'icon-wpb-row',
                'show_settings_on_create' => false,
                'category' => __( 'Content', 'wyde-core' ),
                'description' => __( 'Place content elements inside row', 'wyde-core' ),
                'params' => array(
                    array(
                        'param_name' => 'row_style',
                        'type' => 'dropdown',
                        'heading' => __( 'Content Width', 'wyde-core' ),                      
                        'value' => array(
                            __( 'Default', 'wyde-core' ) => '',
                            __( 'Full Width', 'wyde-core' ) => 'full-width',
                        ),
                        'description' => __( 'Select content width options.', 'wyde-core' )
                    ),
                    array(
                        'param_name' => 'overlap',
                        'type' => 'dropdown',
                        'heading' => __('Overlap', 'wyde-core'),                      
                        'value' => array(
                            __('None', 'wyde-core') => '', 
                            __('Left', 'wyde-core') => 'left', 
                            __('Right', 'wyde-core') => 'right', 
                            __('Top', 'wyde-core') => 'top',
                            __('Bottom', 'wyde-core') => 'bottom', 
                        ),
                        'description' => __('Select the direction of another object that will be overlapped.', 'wyde-core')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Overlap Distance', 'wyde-core'),
                        'param_name' => 'overlap_distance',
                        'value' => '50px',
                        'description' => __('Set the distance you want an object to be offset from the current position.', 'wyde-core'),
                        'dependency' => array(
                            'element' => 'overlap',
                            'not_empty' => true
                        )
                    ),
                    array(
                        'param_name' => 'overlap_index',
                        'type' => 'dropdown',
                        'heading' => __('Stack Order', 'wyde-core'),                      
                        'value' => array(
                            '',
                            50,
                            100,
                            150,
                            200,
                            250,
                            300,
                            400,
                            450,
                            500,                           
                        ),
                        'description' => __('Defines a z-index property that specifies the stack order of an element.', 'wyde-core'),       
                    ),
                    array(
                        'param_name' => 'text_style',
                        'type' => 'dropdown',                      
                        'heading' => __('Text Style', 'wyde-core'),                       
                        'value' => array(
                            __('Dark', 'wyde-core') => '',
                            __('Light', 'wyde-core') => 'light',
                        ),
                        'description' => __('Apply text style.', 'wyde-core')
                    ),
                    array(
                        'param_name' => 'equal_height',
                        'type' => 'checkbox',                       
                        'description' => __( 'Columns in this row will be set to equal height.', 'wyde-core' ),
                        'value' => array( __( 'Equal Height', 'wyde-core' ) => 'true' )
                    ),
                    array(
                        'param_name' => 'vertical_align',
                        'type' => 'dropdown',                       
                        'heading' => __('Content Vertical Alignment', 'wyde-core'),                       
                        'value' => array(
                            __('Top', 'wyde-core') => '', 
                            __('Middle', 'wyde-core') =>'middle', 
                            __('Bottom', 'wyde-core') => 'bottom', 
                        ),
                        'description' => __('Select content vertical alignment.', 'wyde-core'),                      
                    ),
                    array(
                        'param_name' => 'padding_size',
                        'type' => 'dropdown',                      
                        'heading' => __('Vertical Padding Size', 'wyde-core'),                       
                        'value' => array(
                             __('Default', 'wyde-core') => '', 
                             __('No Padding', 'wyde-core') => 'no-padding', 
                             __('Small', 'wyde-core') => 's-padding', 
                             __('Medium', 'wyde-core') => 'm-padding', 
                             __('Large', 'wyde-core') => 'l-padding', 
                             __('Extra Large', 'wyde-core') => 'xl-padding'
                        ),
                        'description' => __('Select vertical padding size.', 'wyde-core')
                    ),
                    array(
                        'param_name' => 'el_id',
                        'type' => 'textfield',
                        'heading' => __( 'Row ID', 'wyde-core' ),                     
                        'description' => __( 'Enter row ID (Note: make sure it is unique and valid).', 'wyde-core' )
                    ),
                    array(
                        'param_name' => 'el_class',
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),                        
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    ),
                    array(
                        'param_name' => 'background_color',
                        'type' => 'colorpicker',
                        'heading' => __( 'Background Color', 'wyde-core' ),                       
                        'description' => __( 'Select background color.', 'wyde-core' ),                       
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'background_image',
                        'type' => 'attach_image',
                        'heading' => __( 'Background Image', 'wyde-core' ),                       
                        'description' => __( 'Select background image.', 'wyde-core' ),
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'bg_image_url', 
                        'type' => 'hidden',                                         
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'background_style',
                        'type' => 'dropdown',
                        'heading' => __( 'Background Style', 'wyde-core' ),                       
                        'value' => array(
                            __( 'Default', 'wyde-core' ) => '',
                            __( 'Cover', 'wyde-core' ) => 'cover',
                            __( 'Contain', 'wyde-core' ) => 'contain',
                            __( 'No Repeat', 'wyde-core' ) => 'no-repeat',
                            __( 'Repeat', 'wyde-core' ) => 'repeat',
                        ),
                        'description' => __( 'Select background style.', 'wyde-core' ),
                        'dependency' => array(
                            'element' => 'background_image',
                            'not_empty' => true,
                            'callback' => 'wyde_row_background_image_callback',
                        ),
                        'group' => __( 'Background', 'wyde-core' ),

                    ),
                    array(
                        'param_name' => 'background_position',
                        'type' => 'dropdown',
                        'heading' => __( 'Background Position', 'wyde-core' ),                       
                        'value' => array(
                            __( 'Center Top', 'wyde-core' ) => 'center top',
                            __( 'Center Center', 'wyde-core' ) => 'center center',
                            __( 'Center Bottom', 'wyde-core' ) => 'center bottom',
                            __( 'Left Top', 'wyde-core' ) => 'left top',
                            __( 'Left Center', 'wyde-core' ) => 'left center',
                            __( 'Left Bottom', 'wyde-core' ) => 'left bottom',
                            __( 'Right Top', 'wyde-core' ) => 'right top',
                            __( 'Right Center', 'wyde-core' ) => 'right center',
                            __( 'Right Bottom', 'wyde-core' ) => 'right bottom',                            
                        ),
                        'std' => 'center center',
                        'description' => __( 'Set the starting position of a background image.', 'wyde-core' ),
                        'dependency' => array(
                            'element' => 'background_image',
                            'not_empty' => true,                            
                        ),                        
                        'group' => __( 'Background', 'wyde-core' ),

                    ),
                    array(
                        'param_name' => 'background_overlay',
                        'type' => 'dropdown',                       
                        'heading' => __('Background Overlay', 'wyde-core'),                       
                        'value' => array(
                            __('None', 'wyde-core') => '',
                            __('Color Overlay', 'wyde-core') => 'color',
                        ),
                        'description' => __('Apply an overlay to the background.', 'wyde-core'),
                        'dependency' => array(
                            'element' => 'background_image',
                            'not_empty' => true,
                        ),
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'overlay_color',
                        'type' => 'colorpicker',
                        'heading' => __( 'Background Overlay Color', 'wyde-core' ),                       
                        'description' => __( 'Select background overlay color.', 'wyde-core' ),
                        'value' => '#211F1E',
                        'dependency' => array(
                            'element' => 'background_overlay',
                            'not_empty' => true
                        ),
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'overlay_opacity',
                        'type' => 'dropdown',
                        'heading' => __('Background Overlay Opacity', 'wyde-core'),                       
                        'value' => array(
                            __('Default', 'wyde-core') => '', 
                            '0.1' => '0.1', 
                            '0.2' => '0.2', 
                            '0.3' => '0.3', 
                            '0.4' => '0.4', 
                            '0.5' => '0.5', 
                            '0.6' => '0.6', 
                            '0.7' => '0.7', 
                            '0.8' => '0.8', 
                            '0.9' => '0.9', 
                        ),
                        'description' => __('Select background overlay opacity.', 'wyde-core'),
                        'dependency' => array(
                            'element' => 'background_overlay',
                            'not_empty' => true
                        ),
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'parallax',
                        'type' => 'dropdown',
                        'heading' => __( 'Parallax', 'wyde-core' ),                       
                        'value' => array(
                            __( 'None', 'wyde-core' ) => '',
                            __( 'Simple Parallax', 'wyde-core' ) => 'parallax',
                            __( 'Reverse Parallax', 'wyde-core' ) => 'reverse',
                        ),
                        'description' => __( 'Select parallax background type.', 'wyde-core' ),
                        'dependency' => array(
                            'element' => 'background_image',
                            'not_empty' => true,
                        ),
                        'group' => __( 'Background', 'wyde-core' ),

                    ),
                    array(
                        'param_name' => 'css',
                        'type' => 'css_editor',
                        'heading' => __( 'CSS', 'wyde-core' ),                        
                        'group' => __( 'Design Options', 'wyde-core' )
                    )
                ),
                'js_view' => 'WydeRowView'
            ) );

            /***************************************** 
            /* Row Inner
            /*****************************************/
            vc_map( array(
                'name' => __( 'Row', 'wyde-core' ), //Inner Row
                'base' => 'vc_row_inner',
                'content_element' => false,
                'is_container' => true,
                'icon' => 'icon-wpb-row',
                'weight' => 1000,
                'show_settings_on_create' => false,
                'description' => __( 'Place content elements inside the row', 'wyde-core' ),
                'params' => array(
                    array(
                        'param_name' => 'overlap',
                        'type' => 'dropdown',
                        'heading' => __('Overlap', 'wyde-core'),                      
                        'value' => array(
                            __('None', 'wyde-core') => '', 
                            __('Left', 'wyde-core') => 'left', 
                            __('Right', 'wyde-core') => 'right', 
                            __('Top', 'wyde-core') => 'top',
                            __('Bottom', 'wyde-core') => 'bottom', 
                        ),
                        'description' => __('Select the direction of another object that will be overlapped.', 'wyde-core')
                    ),
                    array(
                        'param_name' => 'overlap_distance',
                        'type' => 'textfield',
                        'heading' => __('Overlap Distance', 'wyde-core'),                     
                        'value' => '50px',
                        'description' => __('Set the distance you want an object to be offset from the current position.', 'wyde-core'),
                        'dependency' => array(
                            'element' => 'overlap',
                            'not_empty' => true
                        )
                    ),
                    array(
                        'param_name' => 'overlap_index',
                        'type' => 'dropdown',
                        'heading' => __('Stack Order', 'wyde-core'),                      
                        'value' => array(
                            '',
                            50,
                            100,
                            150,
                            200,
                            250,
                            300,
                            400,
                            450,
                            500,                           
                        ),
                        'description' => __('Defines a z-index property that specifies the stack order of an element.', 'wyde-core'),        
                    ),
                    array(
                        'param_name' => 'text_style',
                        'type' => 'dropdown',
                        'heading' => __('Text Style', 'wyde-core'),                        
                        'value' => array(
                            __('Dark', 'wyde-core') => '',
                            __('Light', 'wyde-core') => 'light',
                        ),
                        'description' => __('Apply text style.', 'wyde-core')
                    ),
                    array(
                        'param_name' => 'equal_height',
                        'type' => 'checkbox',                       
                        'description' => __( 'Columns in this row will be set to equal height.', 'wyde-core' ),
                        'value' => array( __( 'Equal Height', 'wyde-core' ) => 'true' )
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Content Vertical Alignment', 'wyde-core'),
                        'param_name' => 'vertical_align',
                        'value' => array(
                            __('Top', 'wyde-core') => '', 
                            __('Middle', 'wyde-core') =>'middle', 
                            __('Bottom', 'wyde-core') => 'bottom', 
                        ),
                        'description' => __('Select content vertical alignment.', 'wyde-core'),                       
                    ),
                    array(
                        'param_name' => 'padding_size',
                        'type' => 'dropdown',                        
                        'heading' => __('Vertical Padding Size', 'wyde-core'),                        
                        'value' => array(
                             __('Default', 'wyde-core') => '', 
                             __('No Padding', 'wyde-core') => 'no-padding', 
                             __('Small', 'wyde-core') => 's-padding', 
                             __('Medium', 'wyde-core') => 'm-padding', 
                             __('Large', 'wyde-core') => 'l-padding', 
                             __('Extra Large', 'wyde-core') => 'xl-padding'
                        ),
                        'description' => __('Select vertical padding size.', 'wyde-core')
                    ),
                    array(
                        'param_name' => 'el_id',
                        'type' => 'textfield',
                        'heading' => __( 'Row ID', 'wyde-core' ),                     
                        'description' => sprintf( __( 'Enter row ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">W3C specification</a>).', 'wyde-core' ), 'http://www.w3schools.com/tags/att_global_id.asp' )
                    ),
                    array(
                        'param_name' => 'el_class',
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),                        
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    ),
                    array(
                        'param_name' => 'background_color',
                        'type' => 'colorpicker',
                        'heading' => __( 'Background Color', 'wyde-core' ),                       
                        'description' => __( 'Select background color.', 'wyde-core' ),                    
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'background_image',
                        'type' => 'attach_image',
                        'heading' => __( 'Background Image', 'wyde-core' ),                       
                        'description' => __( 'Select background image.', 'wyde-core' ),
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'bg_image_url',
                        'type' => 'hidden',             
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'background_style',
                        'type' => 'dropdown',
                        'heading' => __( 'Background Style', 'wyde-core' ),                       
                        'value' => array(
                            __( 'Default', 'wyde-core' ) => '',
                            __( 'Cover', 'wyde-core' ) => 'cover',
                            __( 'Contain', 'wyde-core' ) => 'contain',
                            __( 'No Repeat', 'wyde-core' ) => 'no-repeat',
                            __( 'Repeat', 'wyde-core' ) => 'repeat',
                        ),
                        'description' => __( 'Select background style.', 'wyde-core' ),
                        'dependency' => array(
                            'element' => 'background_image',
                            'not_empty' => true,
                            'callback' => 'wyde_row_background_image_callback',
                        ),
                        'group' => __( 'Background', 'wyde-core' ),

                    ),
                    array(
                        'param_name' => 'background_position',
                        'type' => 'dropdown',
                        'heading' => __( 'Background Position', 'wyde-core' ),                        
                        'value' => array(                                
                            __( 'Center Top', 'wyde-core' ) => 'center top',
                            __( 'Center Center', 'wyde-core' ) => 'center center',
                            __( 'Center Bottom', 'wyde-core' ) => 'center bottom',
                            __( 'Left Top', 'wyde-core' ) => 'left top',
                            __( 'Left Center', 'wyde-core' ) => 'left center',
                            __( 'Left Bottom', 'wyde-core' ) => 'left bottom',
                            __( 'Right Top', 'wyde-core' ) => 'right top',
                            __( 'Right Center', 'wyde-core' ) => 'right center',
                            __( 'Right Bottom', 'wyde-core' ) => 'right bottom',
                        ),
                        'std' => 'center center',
                        'description' => __( 'Select the starting position of a background image.', 'wyde-core' ),
                        'dependency' => array(
                            'element' => 'background_image',
                            'not_empty' => true,
                        ),                        
                        'group' => __( 'Background', 'wyde-core' ),

                    ),
                    array(
                        'param_name' => 'background_overlay',
                        'type' => 'dropdown',                        
                        'heading' => __('Background Overlay', 'wyde-core'),                        
                        'value' => array(
                            __('None', 'wyde-core') => '',
                            __('Color Overlay', 'wyde-core') => 'color',
                        ),
                        'description' => __('Apply an overlay to the background.', 'wyde-core'),
                        'dependency' => array(
                            'element' => 'background_image',
                            'not_empty' => true,
                        ),
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'overlay_color',
                        'type' => 'colorpicker',
                        'heading' => __( 'Background Overlay Color', 'wyde-core' ),                       
                        'description' => __( 'Select background overlay color.', 'wyde-core' ),
                        'value' => '#211F1E',
                        'dependency' => array(
                            'element' => 'background_overlay',
                            'not_empty' => true
                        ),
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'overlay_opacity',
                        'type' => 'dropdown',                        
                        'heading' => __('Background Overlay Opacity', 'wyde-core'),                        
                        'value' => array(
                            __('Default', 'wyde-core') => '', 
                            '0.1' => '0.1', 
                            '0.2' => '0.2', 
                            '0.3' => '0.3', 
                            '0.4' => '0.4', 
                            '0.5' => '0.5', 
                            '0.6' => '0.6', 
                            '0.7' => '0.7', 
                            '0.8' => '0.8', 
                            '0.9' => '0.9', 
                        ),
                        'description' => __('Select background overlay opacity.', 'wyde-core'),
                        'dependency' => array(
                            'element' => 'background_overlay',
                            'not_empty' => true
                        ),
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'css',
                        'type' => 'css_editor',
                        'heading' => __( 'CSS', 'wyde-core' ),                        
                        'group' => __( 'Design Options', 'wyde-core' )
                    )
                ),
                'js_view' => 'WydeRowView'
            ) );

            /***************************************** 
            /* Column
            /*****************************************/
            vc_map( array(
                'name' => __( 'Column', 'wyde-core' ),
                'base' => 'vc_column',
                'is_container' => true,
                'content_element' => false,
                'params' => array(
                    array(
                        'param_name' => 'overlap',
                        'type' => 'dropdown',
                        'heading' => __('Overlap', 'wyde-core'),                      
                        'value' => array(
                            __('None', 'wyde-core') => '', 
                            __('Left', 'wyde-core') => 'left', 
                            __('Right', 'wyde-core') => 'right', 
                            __('Top', 'wyde-core') => 'top',
                            __('Bottom', 'wyde-core') => 'bottom', 
                        ),
                        'description' => __('Select the direction of the overlapped object.', 'wyde-core')
                    ),
                    array(
                        'param_name' => 'overlap_distance',
                        'type' => 'textfield',
                        'heading' => __('Overlap Distance', 'wyde-core'),                     
                        'value' => '50px',
                        'description' => __('Set the distance you want an object to be offset from the current position.', 'wyde-core'),
                        'dependency' => array(
                            'element' => 'overlap',
                            'not_empty' => true
                        )
                    ),
                    array(
                        'param_name' => 'overlap_index',
                        'type' => 'dropdown',
                        'heading' => __('Stack Order', 'wyde-core'),                      
                        'value' => array(
                            '',
                            50,
                            100,
                            150,
                            200,
                            250,
                            300,
                            400,
                            450,
                            500,                           
                        ),
                        'description' => __('Defines a z-index property that specifies the stack order of an element.', 'wyde-core'),        
                    ),
                    array(
                        'param_name' => 'text_style',
                        'type' => 'dropdown',
                        'heading' => __('Text Style', 'wyde-core'),                       
                        'value' => array(
                            __('Dark', 'wyde-core') => '',
                            __('Light', 'wyde-core') => 'light',
                            __('Custom', 'wyde-core') => 'custom',
                        ),
                        'description' => __('Apply text style.', 'wyde-core')
                    ),
                    array(
                        'param_name' => 'text_color',
                        'type' => 'colorpicker',                        
                        'heading' => __('Text Color', 'wyde-core'),
                        'description' => __('Choose column text color.', 'wyde-core'),
                        'dependency' => array(
                            'element' => 'text_style',
                            'value' => array('custom')
                        )
                    ),
                    array(
                        'param_name' => 'text_align',
                        'type' => 'dropdown',
                        'heading' => __('Text Alignment', 'wyde-core'),                       
                        'value' => array(
                            __('Left', 'wyde-core') => '', 
                            __('Center', 'wyde-core') =>'center', 
                            __('Right', 'wyde-core') => 'right', 
                        ),
                        'description' => __('Select text alignment.', 'wyde-core')
                    ),
                    array(
                        'param_name' => 'padding_size',
                        'type' => 'dropdown',
                        'heading' => __('Padding Size', 'wyde-core'),                     
                        'value' => array(
                            __('Default', 'wyde-core') => '', 
                            __('Small', 'wyde-core') => 's-padding', 
                            __('Medium', 'wyde-core') => 'm-padding', 
                            __('Large', 'wyde-core') => 'l-padding', 
                            __('No Padding', 'wyde-core') => 'no-padding', 
                        ),
                        'description' => __('Select padding size.', 'wyde-core')
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
                    array(
                        'param_name' => 'background_color',
                        'type' => 'colorpicker',
                        'heading' => __( 'Background Color', 'wyde-core' ),                       
                        'description' => __( 'Select background color.', 'wyde-core' ),                       
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'background_image',
                        'type' => 'attach_image',
                        'heading' => __( 'Background Image', 'wyde-core' ),                       
                        'description' => __( 'Select background image.', 'wyde-core' ),
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'bg_image_url',
                        'type' => 'hidden',                     
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'background_style',
                        'type' => 'dropdown',
                        'heading' => __( 'Background Style', 'wyde-core' ),                       
                        'value' => array(
                            __( 'Default', 'wyde-core' ) => '',
                            __( 'Cover', 'wyde-core' ) => 'cover',
                            __( 'Contain', 'wyde-core' ) => 'contain',
                            __( 'No Repeat', 'wyde-core' ) => 'no-repeat',
                            __( 'Repeat', 'wyde-core' ) => 'repeat',
                        ),
                        'description' => __( 'Select background style.', 'wyde-core' ),
                        'dependency' => array(
                            'element' => 'background_image',
                            'not_empty' => true,
                            'callback' => 'wyde_column_background_image_callback',
                        ),
                        'group' => __( 'Background', 'wyde-core' ),

                    ),
                    array(
                        'param_name' => 'background_position',
                        'type' => 'dropdown',
                        'heading' => __( 'Background Position', 'wyde-core' ),                        
                        'value' => array(                            
                            __( 'Center Top', 'wyde-core' ) => 'center top',
                            __( 'Center Center', 'wyde-core' ) => 'center center',
                            __( 'Center Bottom', 'wyde-core' ) => 'center bottom',
                            __( 'Left Top', 'wyde-core' ) => 'left top',
                            __( 'Left Center', 'wyde-core' ) => 'left center',
                            __( 'Left Bottom', 'wyde-core' ) => 'left bottom',
                            __( 'Right Top', 'wyde-core' ) => 'right top',
                            __( 'Right Center', 'wyde-core' ) => 'right center',
                            __( 'Right Bottom', 'wyde-core' ) => 'right bottom',
                        ),
                        'std' => 'center center',
                        'description' => __( 'Select the starting position of a background image.', 'wyde-core' ),
                        'dependency' => array(
                            'element' => 'background_image',
                            'not_empty' => true,
                        ),                        
                        'group' => __( 'Background', 'wyde-core' ),

                    ),
                    array(
                        'param_name' => 'background_overlay',
                        'type' => 'dropdown',                       
                        'heading' => __('Background Overlay', 'wyde-core'),                       
                        'value' => array(
                            __('None', 'wyde-core') => '',
                            __('Color Overlay', 'wyde-core') => 'color',
                        ),
                        'description' => __('Apply an overlay to the background.', 'wyde-core'),
                        'dependency' => array(
                            'element' => 'background_image',
                            'not_empty' => true,
                        ),
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'overlay_color',
                        'type' => 'colorpicker',
                        'heading' => __( 'Background Overlay Color', 'wyde-core' ),                       
                        'description' => __( 'Select background overlay color.', 'wyde-core' ),
                        'value' => '#211F1E',
                        'dependency' => array(
                            'element' => 'background_overlay',
                            'not_empty' => true
                        ),
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'overlay_opacity',
                        'type' => 'dropdown',                       
                        'heading' => __('Background Overlay Opacity', 'wyde-core'),                       
                        'value' => array(
                            __('Default', 'wyde-core') => '', 
                            '0.1' => '0.1', 
                            '0.2' => '0.2', 
                            '0.3' => '0.3', 
                            '0.4' => '0.4', 
                            '0.5' => '0.5', 
                            '0.6' => '0.6', 
                            '0.7' => '0.7', 
                            '0.8' => '0.8', 
                            '0.9' => '0.9', 
                        ),
                        'description' => __('Select background overlay opacity.', 'wyde-core'),
                        'dependency' => array(
                            'element' => 'background_overlay',
                            'not_empty' => true
                        ),
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'css',
                        'type' => 'css_editor',
                        'heading' => __( 'CSS', 'wyde-core' ),                        
                        'group' => __( 'Design Options', 'wyde-core' )
                    ),
                    array(
                        'param_name' => 'width',
                        'type' => 'dropdown',
                        'heading' => __( 'Width', 'wyde-core' ),                      
                        'value' => array(
                            __( '1 column - 1/12', 'wyde-core' ) => '1/12',
                            __( '2 columns - 1/6', 'wyde-core' ) => '1/6',
                            __( '3 columns - 1/4', 'wyde-core' ) => '1/4',
                            __( '4 columns - 1/3', 'wyde-core' ) => '1/3',
                            __( '5 columns - 5/12', 'wyde-core' ) => '5/12',
                            __( '6 columns - 1/2', 'wyde-core' ) => '1/2',
                            __( '7 columns - 7/12', 'wyde-core' ) => '7/12',
                            __( '8 columns - 2/3', 'wyde-core' ) => '2/3',
                            __( '9 columns - 3/4', 'wyde-core' ) => '3/4',
                            __( '10 columns - 5/6', 'wyde-core' ) => '5/6',
                            __( '11 columns - 11/12', 'wyde-core' ) => '11/12',
                            __( '12 columns - 1/1', 'wyde-core' ) => '1/1',
                        ),
                        'group' => __( 'Responsive Options', 'wyde-core' ),
                        'description' => __( 'Select column width.', 'wyde-core' ),
                        'std' => '1/1',
                    ),
                    array(
                        'param_name' => 'offset',
                        'type' => 'column_offset',
                        'heading' => __( 'Responsiveness', 'wyde-core' ),                     
                        'group' => __( 'Responsive Options', 'wyde-core' ),
                        'description' => __( 'Adjust column for different screen sizes. Control width, offset and visibility settings.', 'wyde-core' ),
                    )
                ),
                'js_view' => 'WydeColumnView'
            ) );

        
            /***************************************** 
            /* Column Inner
            /*****************************************/
            vc_map( array(
                "name" => __( "Column", 'wyde-core' ),
                "base" => "vc_column_inner",
                "class" => "",
                "icon" => "",
                "wrapper_class" => "",
                "controls" => "full",
                "allowed_container_element" => false,
                "content_element" => false,
                "is_container" => true,
                "params" => array(
                    array(
                        'param_name' => 'overlap',
                        'type' => 'dropdown',
                        'heading' => __('Overlap', 'wyde-core'),                      
                        'value' => array(
                            __('None', 'wyde-core') => '', 
                            __('Left', 'wyde-core') => 'left', 
                            __('Right', 'wyde-core') => 'right', 
                            __('Top', 'wyde-core') => 'top',
                            __('Bottom', 'wyde-core') => 'bottom', 
                        ),
                        'description' => __('Select the direction of the overlapped object.', 'wyde-core')
                    ),
                    array(
                        'param_name' => 'overlap_distance',
                        'type' => 'textfield',
                        'heading' => __('Overlap Distance', 'wyde-core'),                     
                        'value' => '50px',
                        'description' => __('Set the distance you want an object to be offset from the current position.', 'wyde-core'),
                        'dependency' => array(
                            'element' => 'overlap',
                            'not_empty' => true
                        )
                    ),
                    array(
                        'param_name' => 'overlap_index',
                        'type' => 'dropdown',
                        'heading' => __('Stack Order', 'wyde-core'),                      
                        'value' => array(
                            '',
                            50,
                            100,
                            150,
                            200,
                            250,
                            300,
                            400,
                            450,
                            500,                           
                        ),
                        'description' => __('Defines a z-index property that specifies the stack order of an element.', 'wyde-core'),        
                    ),
                    array(
                        'param_name' => 'text_style',
                        'type' => 'dropdown',                       
                        'heading' => __('Text Style', 'wyde-core'),                       
                        'value' => array(
                            __('Dark', 'wyde-core') => '',
                            __('Light', 'wyde-core') => 'light',
                            __('Custom', 'wyde-core') => 'custom',
                        ),
                        'description' => __('Apply text style.', 'wyde-core')
                    ),
                    array(
                        'param_name' => 'text_color',
                        'type' => 'colorpicker',                    
                        'heading' => __('Text Color', 'wyde-core'),
                        'description' => __('Choose column text color.', 'wyde-core'),
                        'dependency' => array(
                            'element' => 'text_style',
                            'value' => array('custom')
                        )
                    ),
                    array(
                        'param_name' => 'text_align',
                        'type' => 'dropdown',                      
                        'heading' => __('Text Alignment', 'wyde-core'),                       
                        'value' => array(
                            __('Left', 'wyde-core') => '', 
                            __('Center', 'wyde-core') =>'center', 
                            __('Right', 'wyde-core') => 'right', 
                        ),
                        'description' => __('Select text alignment.', 'wyde-core')
                    ),
                    array(
                        'param_name' => 'padding_size',
                        'type' => 'dropdown',                       
                        'heading' => __('Padding Size', 'wyde-core'),                     
                        'value' => array(
                            __('Default', 'wyde-core') => '', 
                            __('Small', 'wyde-core') => 's-padding', 
                            __('Large', 'wyde-core') => 'l-padding', 
                            __('No Padding', 'wyde-core') => 'no-padding', 
                        ),
                        'description' => __('Select padding size.', 'wyde-core')
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
                    array(
                        'param_name' => 'background_color',
                        'type' => 'colorpicker',
                        'heading' => __( 'Background Color', 'wyde-core' ),                       
                        'description' => __( 'Select background color.', 'wyde-core' ),                       
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'background_image',
                        'type' => 'attach_image',
                        'heading' => __( 'Background Image', 'wyde-core' ),                       
                        'description' => __( 'Select background image.', 'wyde-core' ),
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'bg_image_url',
                        'type' => 'hidden',
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'background_style',
                        'type' => 'dropdown',
                        'heading' => __( 'Background Style', 'wyde-core' ),                       
                        'value' => array(
                            __( 'Default', 'wyde-core' ) => '',
                            __( 'Cover', 'wyde-core' ) => 'cover',
                            __( 'Contain', 'wyde-core' ) => 'contain',
                            __( 'No Repeat', 'wyde-core' ) => 'no-repeat',
                            __( 'Repeat', 'wyde-core' ) => 'repeat',
                        ),
                        'description' => __( 'Select background style.', 'wyde-core' ),
                        'dependency' => array(
                            'element' => 'background_image',
                            'not_empty' => true,
                            'callback' => 'wyde_column_background_image_callback',
                        ),
                        'group' => __( 'Background', 'wyde-core' ),

                    ),
                    array(
                        'param_name' => 'background_position',
                        'type' => 'dropdown',
                        'heading' => __( 'Background Position', 'wyde-core' ),                        
                        'value' => array(                            
                            __( 'Center Top', 'wyde-core' ) => 'center top',
                            __( 'Center Center', 'wyde-core' ) => 'center center',
                            __( 'Center Bottom', 'wyde-core' ) => 'center bottom',
                            __( 'Left Top', 'wyde-core' ) => 'left top',
                            __( 'Left Center', 'wyde-core' ) => 'left center',
                            __( 'Left Bottom', 'wyde-core' ) => 'left bottom',
                            __( 'Right Top', 'wyde-core' ) => 'right top',
                            __( 'Right Center', 'wyde-core' ) => 'right center',
                            __( 'Right Bottom', 'wyde-core' ) => 'right bottom',
                        ),
                        'std' => 'center center',
                        'description' => __( 'Select the starting position of a background image.', 'wyde-core' ),
                        'dependency' => array(
                            'element' => 'background_image',
                            'not_empty' => true,
                        ),                        
                        'group' => __( 'Background', 'wyde-core' ),

                    ),
                    array(
                        'param_name' => 'background_overlay',
                        'type' => 'dropdown',                      
                        'heading' => __('Background Overlay', 'wyde-core'),                       
                        'value' => array(
                            __('None', 'wyde-core') => '',
                            __('Color Overlay', 'wyde-core') => 'color',
                        ),
                        'description' => __('Apply an overlay to the background.', 'wyde-core'),
                        'dependency' => array(
                            'element' => 'background_image',
                            'not_empty' => true,
                        ),
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'overlay_color',
                        'type' => 'colorpicker',
                        'heading' => __( 'Background Overlay Color', 'wyde-core' ),                       
                        'description' => __( 'Select background overlay color.', 'wyde-core' ),
                        'value' => '#211F1E',
                        'dependency' => array(
                            'element' => 'background_overlay',
                            'not_empty' => true
                        ),
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'overlay_opacity',
                        'type' => 'dropdown',                     
                        'heading' => __('Background Overlay Opacity', 'wyde-core'),                       
                        'value' => array(
                            __('Default', 'wyde-core') => '', 
                            '0.1' => '0.1', 
                            '0.2' => '0.2', 
                            '0.3' => '0.3', 
                            '0.4' => '0.4', 
                            '0.5' => '0.5', 
                            '0.6' => '0.6', 
                            '0.7' => '0.7', 
                            '0.8' => '0.8', 
                            '0.9' => '0.9', 
                        ),
                        'description' => __('Select background overlay opacity.', 'wyde-core'),
                        'dependency' => array(
                            'element' => 'background_overlay',
                            'not_empty' => true
                        ),
                        'group' => __( 'Background', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'css',
                        'type' => 'css_editor',
                        'heading' => __( 'CSS', 'wyde-core' ),                        
                        'group' => __( 'Design Options', 'wyde-core' )
                    ),
                    array(
                        'param_name' => 'width',
                        'type' => 'dropdown',
                        'heading' => __( 'Width', 'wyde-core' ),                      
                        'value' => array(
                            __( '1 column - 1/12', 'wyde-core' ) => '1/12',
                            __( '2 columns - 1/6', 'wyde-core' ) => '1/6',
                            __( '3 columns - 1/4', 'wyde-core' ) => '1/4',
                            __( '4 columns - 1/3', 'wyde-core' ) => '1/3',
                            __( '5 columns - 5/12', 'wyde-core' ) => '5/12',
                            __( '6 columns - 1/2', 'wyde-core' ) => '1/2',
                            __( '7 columns - 7/12', 'wyde-core' ) => '7/12',
                            __( '8 columns - 2/3', 'wyde-core' ) => '2/3',
                            __( '9 columns - 3/4', 'wyde-core' ) => '3/4',
                            __( '10 columns - 5/6', 'wyde-core' ) => '5/6',
                            __( '11 columns - 11/12', 'wyde-core' ) => '11/12',
                            __( '12 columns - 1/1', 'wyde-core' ) => '1/1',
                        ),
                        'group' => __( 'Responsive Options', 'wyde-core' ),
                        'description' => __( 'Select column width.', 'wyde-core' ),
                        'std' => '1/1',
                    ),
                    array(
                        'param_name' => 'offset',
                        'type' => 'column_offset',
                        'heading' => __( 'Responsiveness', 'wyde-core' ),                     
                        'group' => __( 'Responsive Options', 'wyde-core' ),
                        'description' => __( 'Adjust column for different screen sizes. Control width, offset and visibility settings.', 'wyde-core' ),
                    )
                ),
                "js_view" => 'WydeColumnView'
            ) );


            /***************************************** 
            /* Text Block
            /*****************************************/
            vc_map( array(
                'name' => __( 'Text Block', 'wyde-core' ),
                'base' => 'vc_column_text',
                'weight'    => 1000,
                'icon' => 'wyde-icon text-block-icon',
                'wrapper_class' => 'clearfix',
                'category' => __( 'Content', 'wyde-core' ),
                'description' => __( 'A block of text with WYSIWYG editor', 'wyde-core' ),
                'params' => array(
                    array(
                        'param_name' => 'content',
                        'type' => 'textarea_html',
                        'holder' => 'div',
                        'heading' => __( 'Text', 'wyde-core' ),                       
                        'value' => __( '<p>I am text block. Click edit button to change this text.</p>', 'wyde-core' )
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
                    array(
                        'param_name' => 'css',
                        'type' => 'css_editor',
                        'heading' => __( 'CSS', 'wyde-core' ),                        
                        'group' => __( 'Design Options', 'wyde-core' )
                    )
                )
            ) );

            /***************************************** 
            /* EMPTY SPACE
            /*****************************************/
            vc_map( array(
                'name' => __( 'Empty Space', 'wyde-core' ),
                'base' => 'vc_empty_space',
                'icon' => 'wyde-icon empty-space-icon',
                'show_settings_on_create' => true,
                'weight'    => 1000,
                'category' => __( 'Content', 'wyde-core' ),
                'description' => __( 'Blank space with custom height', 'wyde-core' ),
                'params' => array(
                    array(
                        'param_name' => 'height',
                        'type' => 'textfield',
                        'heading' => __( 'Height', 'wyde-core' ),                     
                        'value' => '30px',
                        'admin_label' => true,
                        'description' => __( 'Enter empty space height (Note: CSS measurement units allowed).', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'el_class',
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),                        
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    ),
                ),
            ) );
         

            /***************************************** 
            /* RAW HTML
            /*****************************************/
            vc_map( array(
                'name' => __( 'Raw HTML', 'wyde-core' ),
                'base' => 'vc_raw_html',
                'icon' => 'icon-wpb-raw-html',
                'category' => __( 'Structure', 'wyde-core' ),
                'wrapper_class' => 'clearfix',
                'description' => __( 'Output raw HTML code on your page', 'wyde-core' ),
                'params' => array(
                    array(
                        'param_name' => 'content',
                        'type' => 'textarea_raw_html',
                        'holder' => 'div',
                        'heading' => __( 'Raw HTML', 'wyde-core' ),
                        'description' => __( 'Enter your HTML content.', 'wyde-core' )
                    ),
                )
            ) );


            /***************************************** 
            /* RAW JS
            /*****************************************/
            vc_map( array(
                'name' => __( 'Raw JS', 'wyde-core' ),
                'base' => 'vc_raw_js',
                'icon' => 'icon-wpb-raw-javascript',
                'category' => __( 'Structure', 'wyde-core' ),
                'wrapper_class' => 'clearfix',
                'description' => __( 'Output raw JavaScript code on your page', 'wyde-core' ),
                'params' => array(
                    array(
                        'param_name' => 'content',
                        'type' => 'textarea_raw_html',
                        'holder' => 'div',
                        'heading' => __( 'JavaScript Code', 'wyde-core' ),                        
                        'description' => __( 'Enter your JavaScript code.', 'wyde-core' )
                    ),
                )
            ) );


            /***************************************** 
            /* SINGLE IMAGE
            /*****************************************/
            vc_map( array(
                'name' => __( 'Single Image', 'wyde-core' ),
                'base' => 'vc_single_image',
                'icon' => 'wyde-icon image-icon',
                'weight'    => 998,
                'category' => __( 'Content', 'wyde-core' ),
                'description' => __( 'Insert an image', 'wyde-core' ),
                'params' => array(
                    array(
                        'param_name' => 'image',
                        'type' => 'attach_image',
                        'heading' => __( 'Image', 'wyde-core' ),                      
                        'description' => __( 'Select an image from media library.', 'wyde-core' )
                    ),
                    array(
                        'param_name' => 'img_size',
                        'type' => 'dropdown',
                        'heading' => __( 'Image Size', 'wyde-core' ),                     
                        'value' => array(
                            __('Thumbnail', 'wyde-core' ) => 'thumbnail',
                            __('Medium', 'wyde-core' ) => 'medium',
                            __('Large', 'wyde-core' ) => 'large',
                            __('Original', 'wyde-core' ) => 'full',
                        ),
                        'std'   => 'full',
                        'description' => __( 'Select image size.', 'wyde-core' )
                    ),
                    array(
                        'param_name' => 'style',
                        'type' => 'dropdown',
                        'heading' => __( 'Image Style', 'wyde-core' ),                        
                        'admin_label' => true,
                        'value' => array(
                            __('Default', 'wyde-core' ) => '',
                            __('Border', 'wyde-core' ) => 'border',
                            __('Outline', 'wyde-core' ) => 'outline',
                            __('Shadow', 'wyde-core' ) => 'shadow',
                            __('Round', 'wyde-core' ) => 'round',
                            __('Round Border', 'wyde-core' ) => 'round-border',
                            __('Round Outline', 'wyde-core' ) => 'round-outline', 
                            __('Round Shadow', 'wyde-core' ) => 'round-shadow', 
                            __('Circle', 'wyde-core' ) => 'circle', 
                            __('Circle Border', 'wyde-core' ) => 'circle-border', 
                            __('Circle Outline', 'wyde-core' ) => 'circle-outline',
                            __('Circle Shadow', 'wyde-core' ) => 'circle-shadow',
                        ),
                        'description' => __( 'Select image alignment.', 'wyde-core' )
                    ),
                    array(
                        'param_name' => 'border_color',
                        'type' => 'colorpicker',
                        'heading' => __( 'Border Color', 'wyde-core' ),                       
                        'description' => __( 'Select image border color.', 'wyde-core' ),
                        'dependency' => array(
                            'element' => 'style',
                            'value' => array( 'border', 'outline', 'round-border', 'round-outline', 'circle-border', 'circle-outline' )
                        )
                    ),
                    array(
                        'param_name' => 'alignment',
                        'type' => 'dropdown',
                        'heading' => __( 'Image Alignment', 'wyde-core' ),                        
                        'value' => array(
                            __( 'Align Left', 'wyde-core' ) => 'left',
                            __( 'Align Center', 'wyde-core' ) => 'center',
                            __( 'Align Right', 'wyde-core' ) => 'right',
                        ),
                        'description' => __( 'Select image alignment.', 'wyde-core' )
                    ),
                    array(
                        'param_name' => 'onclick',
                        'type' => 'dropdown',
                        'heading' => __( 'On Click Action', 'wyde-core' ),                        
                        'value' => array(
                            __( 'None', 'wyde-core' ) => '',
                            __( 'Link to large image', 'wyde-core' ) => 'img_link_large',
                            __( 'Open prettyPhoto', 'wyde-core' ) => 'link_image',
                            __( 'Open custom link', 'wyde-core' ) => 'custom_link',
                        ),
                        'description' => __( 'Select action for click action.', 'wyde-core' ),
                        'std' => '',
                    ),
                    array(
                        'param_name' => 'link',
                        'type' => 'href',
                        'heading' => __( 'Image Link', 'wyde-core' ),                     
                        'description' => __( 'Set an image link.', 'wyde-core' ),
                        'dependency' => array(
                            'element' => 'onclick',
                            'value' => 'custom_link',
                        ),
                    ),
                    array(
                        'param_name' => 'link_target',
                        'type' => 'dropdown',
                        'heading' => __( 'Link Target', 'wyde-core' ),                        
                        'value' => array(
                            __( 'Same window', 'wyde-core' ) => '_self',
                            __( 'New window', 'wyde-core' ) => "_blank",
                        ),
                        'dependency' => array(
                            'element' => 'onclick',
                            'value' =>  array( 'custom_link', 'img_link_large' )
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
                    array(
                        'param_name' => 'css',
                        'type' => 'css_editor',
                        'heading' => __( 'Css', 'wyde-core' ),                        
                        'group' => __( 'Design Options', 'wyde-core' )
                    ) 
                )
            ) );
        

            /***************************************** 
            /* TABS
            /*****************************************/
            $tab_id_1 = 'def' . time() . '-1-' . rand( 0, 100 );
            $tab_id_2 = 'def' . time() . '-2-' . rand( 0, 100 );
            vc_map( array(
                "name" => __( 'Tabs', 'wyde-core' ),
                'base' => 'vc_tabs',
                'show_settings_on_create' => false,
                'is_container' => true,
                'icon' => 'icon-wpb-ui-tab-content',
                'category' => __( 'Content', 'wyde-core' ),
                'description' => __( 'Tabbed content', 'wyde-core' ),
                'params' => array(
                    array(
                        'param_name' => 'interval',
                        'type' => 'dropdown',
                        'heading' => __( 'Auto rotate tabs', 'wyde-core' ),                       
                        'value' => array( __( 'Disable', 'wyde-core' ) => 0, 3, 5, 10, 15 ),
                        'std' => 0,
                        'description' => __( 'Auto rotate tabs each X seconds.', 'wyde-core' )
                    ),
                    array(
                        'param_name' => 'el_class',
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),                        
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    )
                ),
                'custom_markup' => '<div class="wpb_tabs_holder wpb_holder vc_container_for_children"><ul class="tabs_controls"></ul>%content%</div>',
                'default_content' => '[vc_tab title="' . __( 'Tab 1', 'wyde-core' ) . '" tab_id="' . $tab_id_1 . '"][/vc_tab][vc_tab title="' . __( 'Tab 2', 'wyde-core' ) . '" tab_id="' . $tab_id_2 . '"][/vc_tab]',
                'js_view' => 'VcTabsView'
            ) );


            /***************************************** 
            /* TOUR
            /*****************************************/
            $tab_id_1 = time() . '-1-' . rand( 0, 100 );
            $tab_id_2 = time() . '-2-' . rand( 0, 100 );
            vc_map( array(
                'name' => __( 'Tour', 'wyde-core' ),
                'base' => 'vc_tour',
                'show_settings_on_create' => false,
                'is_container' => true,
                'container_not_allowed' => true,
                'icon' => 'icon-wpb-ui-tab-content-vertical',
                'category' => __( 'Content', 'wyde-core' ),
                'wrapper_class' => 'vc_clearfix',
                'description' => __( 'Vertical tabbed content', 'wyde-core' ),
                'params' => array(
                    array(
                        'param_name' => 'interval',
                        'type' => 'dropdown',
                        'heading' => __( 'Auto rotate slides', 'wyde-core' ),                     
                        'value' => array( __( 'Disable', 'wyde-core' ) => 0, 3, 5, 10, 15 ),
                        'std' => 0,
                        'description' => __( 'Auto rotate slides each X seconds.', 'wyde-core' )
                    ),
                    array(
                        'param_name' => 'el_class',
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),                        
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    )
                ),
                'custom_markup' => '<div class="wpb_tabs_holder wpb_holder vc_clearfix vc_container_for_children"><ul class="tabs_controls"></ul>%content%</div>',
                'default_content' => '[vc_tab title="' . __( 'Tab 1', 'wyde-core' ) . '" tab_id="' . $tab_id_1 . '"][/vc_tab][vc_tab title="' . __( 'Tab 2', 'wyde-core' ) . '" tab_id="' . $tab_id_2 . '"][/vc_tab]',
                'js_view' => 'VcTabsView'
            ) );


            /***************************************** 
            /* TAB SECTION
            /*****************************************/
            vc_map( array(
                'name' => __( 'Tab', 'wyde-core' ),
                'base' => 'vc_tab',
                'allowed_container_element' => 'vc_row',
                'is_container' => true,
                'content_element' => false,
                'params' => array(
                    array(
                        'param_name' => 'title',
                        'type' => 'textfield',
                        'heading' => __( 'Title', 'wyde-core' ),                      
                        'description' => __( 'Tab title.', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => "tab_id",
                        'type' => 'tab_id',
                        'heading' => __( 'Tab ID', 'wyde-core' ),                     
                    ),
                ),
                'js_view' => 'VcTabView'
            ) );


            /***************************************** 
            /* ACCORDION
            /*****************************************/
            vc_map( array(
                'name' => __( 'Accordion', 'wyde-core' ),
                'base' => 'vc_accordion',
                'show_settings_on_create' => false,
                'is_container' => true,
                'icon' => 'wyde-icon accordion-icon',
                'wrapper_class' => 'vc_clearfix',
                'weight'    => 990,
                'category' => __( 'Content', 'wyde-core' ),
                'description' => __( 'Collapsible content panels', 'wyde-core' ),
                'params' => array(
                    array(
                        'param_name' => 'active_tab',
                        'type' => 'textfield',
                        'heading' => __( 'Active section', 'wyde-core' ),                     
                        'description' => __( 'Enter section number to be active on load or enter "false" to collapse all sections.', 'wyde-core' )
                    ),
                    array(
                        'param_name' => 'collapsible',
                        'type' => 'checkbox',
                        'heading' => __( 'Collapse all', 'wyde-core' ),                       
                        'value' => array( __( 'Allow collapse all sections', 'wyde-core' ) => 'yes' )
                    ),
                    array(
                        'param_name' => 'color',
                        'type' => 'colorpicker',                        
                        'heading' => __('Color', 'wyde-core'),
                        'description' => __('Choose color.', 'wyde-core')
                    ),
                    array(
                        'param_name' => 'el_class',
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),                        
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    )
                ),
                'custom_markup' => '<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">%content%</div>
                <div class="tab_controls">
                    <a class="add_tab" title="' . __( 'Add section', 'wyde-core' ) . '"><span class="vc_icon"></span> <span class="tab-label">' . __( 'Add section', 'wyde-core' ) . '</span></a>
                </div>',
                'default_content' => '[vc_accordion_tab title="' . __( 'Section 1', 'wyde-core' ) . '"][/vc_accordion_tab]
                [vc_accordion_tab title="' . __( 'Section 2', 'wyde-core' ) . '"][/vc_accordion_tab]',
                'js_view' => 'VcAccordionView'
            ) );


            /***************************************** 
            /* ACCORDION SECTION
            /*****************************************/
            vc_map( array(
                'name' => __( 'Section', 'wyde-core' ),
                'base' => 'vc_accordion_tab',
                'allowed_container_element' => 'vc_row',
                'wrapper_class' => 'vc_clearfix',
                'is_container' => true,
                'content_element' => false,
                'params' => array(
                    $icon_picker_options[0],
                    $icon_picker_options[1],
                    $icon_picker_options[2],
                    $icon_picker_options[3],
                    $icon_picker_options[4],
                    $icon_picker_options[5],
                    array(
                        'param_name' => 'title',
                        'type' => 'textfield',
                        'heading' => __( 'Title', 'wyde-core' ),                          
                        'description' => __( 'Tab title.', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'el_class',
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),                            
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    ),
                ),
                'js_view' => 'WydeAccordionTabView'
            ) );


            /***************************************** 
            /* CUSTOM HEADING
            /*****************************************/
            vc_map( array(
                'name' => __( 'Custom Heading', 'wyde-core' ),
                'base' => 'vc_custom_heading',
                'icon' => 'icon-wpb-ui-custom_heading',
                'show_settings_on_create' => true,
                'weight'    => 999,
                'category' => __( 'Content', 'wyde-core' ),
                'description' => __( 'Text with Google fonts', 'wyde-core' ),
                'params' => array(
                    array(
                        'param_name' => 'text',
                        'type' => 'textarea',
                        'heading' => __( 'Text', 'wyde-core' ),                       
                        'admin_label' => true,
                        'value' => __( 'This is custom heading element with Google Fonts', 'wyde-core' ),
                        'description' => __( 'Note: If you are using non-latin characters be sure to activate them under Settings/Visual Composer/General Settings.', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'link',
                        'type' => 'vc_link',
                        'heading' => __( 'URL (Link)', 'wyde-core' ),                     
                        'description' => __( 'Add link to custom heading.', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'font_container',
                        'type' => 'font_container',                     
                        'value' => 'tag:h2|text_align:left',
                        'settings' => array(
                            'fields' => array(
                                'tag' => 'h2', // default value h2
                                'text_align',
                                'font_size',
                                'line_height',
                                'color',
                                //'font_style_italic'
                                //'font_style_bold'
                                //'font_family'
                                'tag_description' => __( 'Select element tag.', 'wyde-core' ),
                                'text_align_description' => __( 'Select text alignment.', 'wyde-core' ),
                                'font_size_description' => __( 'Enter font size.', 'wyde-core' ),
                                'line_height_description' => __( 'Enter line height.', 'wyde-core' ),
                                'color_description' => __( 'Select heading color.', 'wyde-core' ),
                                //'font_style_description' => __('Put your description here','wyde-core'),
                                //'font_family_description' => __('Put your description here','wyde-core'),
                            ),
                        ),
                        // 'description' => __( '', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'google_fonts',
                        'type' => 'google_fonts',                       
                        'value' => 'font_family:Abril%20Fatface%3A400|font_style:400%20regular%3A400%3Anormal',
                        // default
                        //'font_family:'.rawurlencode('Abril Fatface:400').'|font_style:'.rawurlencode('400 regular:400:normal')
                        // this will override 'settings'. 'font_family:'.rawurlencode('Exo:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic').'|font_style:'.rawurlencode('900 bold italic:900:italic'),
                        'settings' => array(
                            //'no_font_style' // Method 1: To disable font style
                            //'no_font_style'=>true // Method 2: To disable font style
                            'fields' => array(
                                //'font_family' => 'Abril Fatface:regular',
                                //'Exo:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic',// Default font family and all available styles to fetch
                                //'font_style' => '400 regular:400:normal',
                                // Default font style. Name:weight:style, example: "800 bold regular:800:normal"
                                'font_family_description' => __( 'Select font family.', 'wyde-core' ),
                                'font_style_description' => __( 'Select font styling.', 'wyde-core' )
                            )
                        ),
                    ),
                    array(
                            'type' => 'textfield',
                            'heading' => __( 'Letter Spacing', 'wyde-core' ),
                            'param_name' => 'letter_spacing',
                            'description' => __( 'Input a Letter Spacing (e.g. 1px, 2px, etc.).', 'wyde-core' ),
                    ),
                    array(
                        'param_name' => 'text_transform',
                        'type' => 'dropdown',
                        'heading' => __('Text Transform', 'wyde-core'),                       
                        'value' => array(
                            __('Default', 'wyde-core') => '',
                            __('None', 'wyde-core') => 'none',
                            __('Capitalize', 'wyde-core') => 'capitalize',
                            __('Lowercase', 'wyde-core') => 'lowercase',
                            __('Uppercase', 'wyde-core') => 'uppercase',
                        ),
                        'description' => __('Apply text case and capitalization.', 'wyde-core')
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
                    array(
                        'param_name' => 'css',
                        'type' => 'css_editor',
                        'heading' => __( 'Css', 'wyde-core' ),                        
                        'group' => __( 'Design Options', 'wyde-core' )
                    )
                ),
            ) );


            /***************************************** 
            /* VIDEO
            /*****************************************/
            vc_map( array(
                'name' => __( 'Video Player', 'wyde-core' ),
                'base' => 'vc_video',
                'icon' => 'icon-wpb-film-youtube',
                'category' => __( 'Content', 'wyde-core' ),
                'description' => __( 'Embed YouTube/Vimeo player', 'wyde-core' ),
                'params' => array(
                    array(
                        'param_name' => 'media_url',
                        'type' => 'textfield',
                        'heading' => __( 'Video URL', 'wyde-core' ),                      
                        'admin_label' => true,
                        'description' => wp_kses( __( 'Enter video URL, you can insert self-hosted MP4 video and URL from any major video/audio sites (Youtube, Vimeo, etc.). Supports services listed at <a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">Codex page</a>.', 'wyde-core' ), 
                            array(
                                'a' => array(
                                    'href' => array(),
                                    'title' => array(),
                                    'target' => array()
                                ),
                                'br' => array(),
                                'em' => array(),
                                'strong' => array(),
                            )
                        )                         
                    ),
                    array(
                        'param_name' => 'el_class',
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),                        
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    ),
                    array(
                        'param_name' => 'css',
                        'type' => 'css_editor',
                        'heading' => __( 'Css', 'wyde-core' ),                        
                        'group' => __( 'Design Options', 'wyde-core' )
                    )
                )
            ) );            

            /***************************************** 
            /* ROUND CHART
            /*****************************************/
            vc_map( array(
                'name' => __( 'Round Chart', 'wyde-core' ),
                'base' => 'vc_round_chart',
                'icon' => 'icon-wpb-vc-round-chart',
                'category' => __( 'Content', 'wyde-core' ),
                'description' => __( 'Pie and Doughnat charts', 'wyde-core' ),
                'params' => array(
                    array(
                        'param_name' => 'title',
                        'type' => 'textfield',
                        'heading' => __( 'Widget title', 'wyde-core' ),                       
                        'description' => __( 'Enter text used as widget title (Note: located above content element).', 'wyde-core' ),
                        'admin_label' => true
                    ),
                    array(
                        'param_name' => 'type',
                        'type' => 'dropdown',
                        'heading' => __( 'Design', 'wyde-core' ),                     
                        'value' => array(
                            __( 'Pie', 'wyde-core' ) => 'pie',
                            __( 'Doughnut', 'wyde-core' ) => 'doughnut',
                        ),
                        'description' => __( 'Select type of chart.', 'wyde-core' )
                    ),
                    array(
                        'param_name' => 'style',
                        'type' => 'dropdown',
                        'heading' => __( 'Style', 'wyde-core' ),
                        'description' => __( 'Select chart color style.', 'wyde-core' ),                      
                        'value' => array(
                            __( 'Flat', 'wyde-core' ) => 'flat',
                            __( 'Modern', 'wyde-core' ) => 'modern',
                        ),
                        'dependency' => array(
                            'callback' => 'vcChartCustomColorDependency',
                        )
                    ),
                    array(
                        'param_name' => 'stroke_width',
                        'type' => 'dropdown',
                        'heading' => __( 'Gap', 'wyde-core' ),                        
                        'value' => array(
                            'None' => '',
                            1 => 1,
                            2 => 2,
                            3 => 3,
                        ),
                        'description' => __( 'Select gap size.', 'wyde-core' ),
                        'std' => 2
                    ),
                    array(
                        'param_name' => 'stroke_color',
                        'type' => 'colorpicker',
                        'heading' => __( 'Outline color', 'wyde-core' ),                      
                        'description' => __( 'Select outline color.', 'wyde-core' ),
                        'dependency' => array(
                            'element' => 'stroke_width',
                            'not_empty' => true

                        )
                    ),
                    array(
                        'param_name' => 'legend',
                        'type' => 'checkbox',
                        'heading' => __( 'Show legend?', 'wyde-core' ),                       
                        'description' => __( 'If checked, chart will have legend.', 'wyde-core' ),
                        'value' => array( __( 'Yes', 'wyde-core' ) => 'yes' ),
                        'std' => 'yes'
                    ),
                    array(
                        'param_name' => 'tooltips',
                        'type' => 'checkbox',
                        'heading' => __( 'Show hover values?', 'wyde-core' ),                     
                        'description' => __( 'If checked, chart will show values on hover.', 'wyde-core' ),
                        'value' => array( __( 'Yes', 'wyde-core' ) => 'yes' ),
                        'std' => 'yes'
                    ),
                    array(
                        'param_name' => 'values',
                        'type' => 'param_group',
                        'heading' => __( 'Values', 'wyde-core' ),                     
                        'value' => urlencode( json_encode( array(
                            array(
                                'title' => __( 'One', 'wyde-core' ),
                                'value' => '60',
                                'color' => 'blue'
                            ),
                            array(
                                'title' => __( 'Two', 'wyde-core' ),
                                'value' => '40',
                                'color' => 'pink'
                            )
                        ) ) ),
                        'params' => array(
                            array(
                                'param_name' => 'title',
                                'type' => 'textfield',
                                'heading' => __( 'Title', 'wyde-core' ),                              
                                'description' => __( 'Enter title for chart area.', 'wyde-core' ),
                                'admin_label' => true
                            ),
                            array(
                                'param_name' => 'value',
                                'type' => 'textfield',
                                'heading' => __( 'Value', 'wyde-core' ),                              
                                'description' => __( 'Enter value for area.', 'wyde-core' ),
                            ),
                            array(
                                'param_name' => 'color',
                                'type' => 'colorpicker',
                                'heading' => __( 'Color', 'wyde-core' ),                              
                                'description' => __( 'Select area color.', 'wyde-core' ),
                            ),
                        ),
                        'callbacks' => array(
                            'after_add' => 'vcChartParamAfterAddCallback'
                        )
                    ),
                    array(
                        'param_name' => 'animation',
                        'type' => 'dropdown',
                        'heading' => __( 'Chart Animation', 'wyde-core' ),
                        'description' => __( 'Select chart animation style.', 'wyde-core' ),                      
                        'value' => array(
                            'Bounce' => 'easeOutBounce',
                            'Elastic' => 'easeOutElastic',
                            'Back' => 'easeOutBack',
                            'Cubic' => 'easeinOutCubic',
                            'Quint' => 'easeinOutQuint',
                            'Quart' => 'easeOutQuart',
                            'Quad' => 'easeinQuad',
                            'Sine' => 'easeOutSine'
                        ),
                        'std' => 'easeinOutCubic'
                    ),
                    array(
                        'param_name' => 'el_class',
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),                        
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    ),
                    array(
                        'param_name' => 'css',
                        'type' => 'css_editor',
                        'heading' => __( 'CSS', 'wyde-core' ),                        
                        'group' => __( 'Design Options', 'wyde-core' )
                    ),
                )
            ) );

            /***************************************** 
            /* LINE CHART
            /*****************************************/
            vc_map( array(
                'name' => __( 'Line Chart', 'wyde-core' ),
                'base' => 'vc_line_chart',
                'icon' => 'icon-wpb-vc-line-chart',
                'category' => __( 'Content', 'wyde-core' ),
                'description' => __( 'Line and Bar charts', 'wyde-core' ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Widget title', 'wyde-core' ),
                        'param_name' => 'title',
                        'description' => __( 'Enter text used as widget title (Note: located above content element).', 'wyde-core' ),
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __( 'Design', 'wyde-core' ),
                        'param_name' => 'type',
                        'value' => array(
                            __( 'Line', 'wyde-core' ) => 'line',
                            __( 'Bar', 'wyde-core' ) => 'bar',
                        ),
                        'std' => 'bar',
                        'description' => __( 'Select type of chart.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __( 'Style', 'wyde-core' ),
                        'description' => __( 'Select chart color style.', 'wyde-core' ),
                        'param_name' => 'style',
                        'value' => array(
                            __( 'Flat', 'wyde-core' ) => 'flat',
                            __( 'Modern', 'wyde-core' ) => 'modern',
                        ),
                        'dependency' => array(
                            'callback' => 'vcChartCustomColorDependency',
                        )
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __( 'Show legend?', 'wyde-core' ),
                        'param_name' => 'legend',
                        'description' => __( 'If checked, chart will have legend.', 'wyde-core' ),
                        'value' => array( __( 'Yes', 'wyde-core' ) => 'yes' ),
                        'std' => 'yes'
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __( 'Show hover values?', 'wyde-core' ),
                        'param_name' => 'tooltips',
                        'description' => __( 'If checked, chart will show values on hover.', 'wyde-core' ),
                        'value' => array( __( 'Yes', 'wyde-core' ) => 'yes' ),
                        'std' => 'yes'
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'X-axis values', 'wyde-core' ),
                        'param_name' => 'x_values',
                        'description' => __( 'Enter values for axis (Note: separate values with ";").', 'wyde-core' ),
                        'value' => 'JAN; FEB; MAR; APR; MAY; JUN; JUL; AUG'
                    ),
                    array(
                        'type' => 'param_group',
                        'heading' => __( 'Values', 'wyde-core' ),
                        'param_name' => 'values',
                        'value' => urlencode( json_encode( array(
                            array(
                                'title' => __( 'One', 'wyde-core' ),
                                'y_values' => '10; 15; 20; 25; 27; 25; 23; 25',
                                'color' => 'blue'
                            ),
                            array(
                                'title' => __( 'Two', 'wyde-core' ),
                                'y_values' => '25; 18; 16; 17; 20; 25; 30; 35',
                                'color' => 'pink'
                            )
                        ) ) ),
                        'params' => array(
                            array(
                                'type' => 'textfield',
                                'heading' => __( 'Title', 'wyde-core' ),
                                'param_name' => 'title',
                                'description' => __( 'Enter title for chart dataset.', 'wyde-core' ),
                                'admin_label' => true
                            ),
                            array(
                                'type' => 'textfield',
                                'heading' => __( 'Y-axis values', 'wyde-core' ),
                                'param_name' => 'y_values',
                                'description' => __( 'Enter values for axis (Note: separate values with ";").', 'wyde-core' ),
                            ),
                            array(
                                'type' => 'colorpicker',
                                'heading' => __( 'Color', 'wyde-core' ),
                                'param_name' => 'color',
                                'description' => __( 'Select chart color.', 'wyde-core' ),
                            ),
                        ),
                        'callbacks' => array(
                            'after_add' => 'vcChartParamAfterAddCallback'
                        )
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __( 'Chart Animation', 'wyde-core' ),
                        'description' => __( 'Select chart animation style.', 'wyde-core' ),
                        'param_name' => 'animation',
                        'value' => array(
                            'Bounce' => 'easeOutBounce',
                            'Elastic' => 'easeOutElastic',
                            'Back' => 'easeOutBack',
                            'Cubic' => 'easeinOutCubic',
                            'Quint' => 'easeinOutQuint',
                            'Quart' => 'easeOutQuart',
                            'Quad' => 'easeinQuad',
                            'Sine' => 'easeOutSine'
                        ),
                        'std' => 'easeinOutCubic'
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),
                        'param_name' => 'el_class',
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'css_editor',
                        'heading' => __( 'CSS', 'wyde-core' ),
                        'param_name' => 'css',
                        'group' => __( 'Design Options', 'wyde-core' )
                    ),
                )
            ) );

            /***************************************** 
            /* MESSAGE BOX
            /*****************************************/
            vc_map( array(
                'name' => __( 'Message Box', 'wyde-core' ),
                'base' => 'vc_message',
                'icon' => 'icon-wpb-information-white',
                'category' => __( 'Content', 'wyde-core' ),
                'description' => __( 'Notification box', 'wyde-core' ),
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => __( 'Style', 'wyde-core' ),
                        'param_name' => 'message_box_style',
                        'value' => array(
                            __('Standard', 'wyde-core' ) => 'standard',
                            __('Solid', 'wyde-core' ) => 'solid',
                            __('Solid icon', 'wyde-core' ) => 'solid-icon',
                            __('Outline', 'wyde-core' ) => 'outline',
                            __('3D', 'wyde-core' ) => '3d',
                        ),
                        'description' => __( 'Select message box design style.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __( 'Shape', 'wyde-core' ),
                        'param_name' => 'style', // due to backward compatibility message_box_shape
                        'std' => 'rounded',
                        'value' => array(
                            __( 'Square', 'wyde-core' ) => 'square',
                            __( 'Rounded', 'wyde-core' ) => 'rounded',
                            __( 'Round', 'wyde-core' ) => 'round',
                        ),
                        'description' => __( 'Select message box shape.', 'wyde-core' ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __( 'Color', 'wyde-core' ),
                        'param_name' => 'message_box_color',
                        'value' => array(
                            __( 'Informational', 'wyde-core' ) => 'info',
                            __( 'Warning', 'wyde-core' ) => 'warning',
                            __( 'Success', 'wyde-core' ) => 'success',
                            __( 'Error', 'wyde-core' ) => "danger",
                            __( 'Informational Classic', 'wyde-core' ) => 'alert-info',
                            __( 'Warning Classic', 'wyde-core' ) => 'alert-warning',
                            __( 'Success Classic', 'wyde-core' ) => 'alert-success',
                            __( 'Error Classic', 'wyde-core' ) => "alert-danger",
                            __( 'Custom', 'wyde-core' ) => "cutom",
                        ),
                        'description' => __( 'Select message box color.', 'wyde-core' ),
                        'param_holder_class' => 'vc_message-type vc_colored-dropdown',
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Custom Color', 'wyde-core' ),
                        'param_name' => 'color',
                        'description' => __( 'Select message box color.', 'wyde-core' ),
                        'dependency' => array(
                            'element' => 'message_box_color',
                            'value' => array('custom')
                        )
                    ),
                    $icon_picker_options[0],
                    $icon_picker_options[1],
                    $icon_picker_options[2],
                    $icon_picker_options[3],
                    $icon_picker_options[4],
                    $icon_picker_options[5],
                    array(
                        'type' => 'textarea_html',
                        'holder' => 'div',
                        'class' => 'messagebox_text',
                        'heading' => __( 'Message text', 'wyde-core' ),
                        'param_name' => 'content',
                        'value' => __( '<p>I am message box. Click edit button to change this text.</p>', 'wyde-core' )
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
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),
                        'param_name' => 'el_class',
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'css_editor',
                        'heading' => __( 'CSS', 'wyde-core' ),
                        'param_name' => 'css',
                        'group' => __( 'Design Options', 'wyde-core' )
                    ),
                ),
                'js_view' => 'VcMessageView_Backend'
            ) );

            /***************************************** 
            /* WIDGETS
            /*****************************************/
            vc_map( array(
                'name' => __( 'Widgetised Sidebar', 'wyde-core' ),
                'base' => 'vc_widget_sidebar',
                'class' => 'wpb_widget_sidebar_widget',
                'icon' => 'icon-wpb-layout_sidebar',
                'category' => __( 'Structure', 'wyde-core' ),
                'description' => __( 'WordPress widgetised sidebar', 'wyde-core' ),
                'params' => array(
                    array(
                        'type' => 'widgetised_sidebars',
                        'heading' => __( 'Sidebar', 'wyde-core' ),
                        'param_name' => 'sidebar_id',
                        'description' => __( 'Select widget area to display.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),
                        'param_name' => 'el_class',
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    )
                )
            ) );

            /* WordPress default Widgets (Appearance->Widgets)
            ---------------------------------------------------------- */
            vc_map( array(
                'name' => 'WP ' . __( "Search", 'wyde-core' ),
                'base' => 'vc_wp_search',
                'icon' => 'icon-wpb-wp',
                'category' => __( 'WordPress Widgets', 'wyde-core' ),
                'class' => 'wpb_vc_wp_widget',
                'weight' => - 50,
                'description' => __( 'A search form for your site', 'wyde-core' ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Widget title', 'wyde-core' ),
                        'param_name' => 'title',
                        'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),
                        'param_name' => 'el_class',
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    )
                )
            ) );

            vc_map( array(
                'name' => 'WP ' . __( 'Meta', 'wyde-core' ),
                'base' => 'vc_wp_meta',
                'icon' => 'icon-wpb-wp',
                'category' => __( 'WordPress Widgets', 'wyde-core' ),
                'class' => 'wpb_vc_wp_widget',
                'weight' => - 50,
                'description' => __( 'Log in/out, admin, feed and WordPress links', 'wyde-core' ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Widget title', 'wyde-core' ),
                        'param_name' => 'title',
                        'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),
                        'param_name' => 'el_class',
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    )
                )
            ) );

            vc_map( array(
                'name' => 'WP ' . __( 'Recent Comments', 'wyde-core' ),
                'base' => 'vc_wp_recentcomments',
                'icon' => 'icon-wpb-wp',
                'category' => __( 'WordPress Widgets', 'wyde-core' ),
                'class' => 'wpb_vc_wp_widget',
                'weight' => - 50,
                'description' => __( 'The most recent comments', 'wyde-core' ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Widget title', 'wyde-core' ),
                        'param_name' => 'title',
                        'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Number of comments', 'wyde-core' ),
                        'description' => __( 'Enter number of comments to display.', 'wyde-core' ),
                        'param_name' => 'number',
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),
                        'param_name' => 'el_class',
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    )
                )
            ) );

            vc_map( array(
                'name' => 'WP ' . __( 'Calendar', 'wyde-core' ),
                'base' => 'vc_wp_calendar',
                'icon' => 'icon-wpb-wp',
                'category' => __( 'WordPress Widgets', 'wyde-core' ),
                'class' => 'wpb_vc_wp_widget',
                'weight' => - 50,
                'description' => __( 'A calendar of your sites posts', 'wyde-core' ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Widget title', 'wyde-core' ),
                        'param_name' => 'title',
                        'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),
                        'param_name' => 'el_class',
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    )
                )
            ) );

            vc_map( array(
                'name' => 'WP ' . __( 'Pages', 'wyde-core' ),
                'base' => 'vc_wp_pages',
                'icon' => 'icon-wpb-wp',
                'category' => __( 'WordPress Widgets', 'wyde-core' ),
                'class' => 'wpb_vc_wp_widget',
                'weight' => - 50,
                'description' => __( 'Your sites WordPress Pages', 'wyde-core' ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Widget title', 'wyde-core' ),
                        'param_name' => 'title',
                        'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __( 'Order by', 'wyde-core' ),
                        'param_name' => 'sortby',
                        'value' => array(
                            __( 'Page title', 'wyde-core' ) => 'post_title',
                            __( 'Page order', 'wyde-core' ) => 'menu_order',
                            __( 'Page ID', 'wyde-core' ) => 'ID'
                        ),
                        'description' => __( 'Select how to sort pages.', 'wyde-core' ),
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Exclude', 'wyde-core' ),
                        'param_name' => 'exclude',
                        'description' => __( 'Enter page IDs to be excluded (Note: separate values by commas (,)).', 'wyde-core' ),
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),
                        'param_name' => 'el_class',
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    )
                )
            ) );

            $tag_taxonomies = array();
            $taxonomies = get_taxonomies();
            if ( is_array( $taxonomies ) && ! empty( $taxonomies ) ) {
                foreach ( $taxonomies as $taxonomy ) {
                    $tax = get_taxonomy( $taxonomy );
                    if ( ( is_object( $tax ) && ( ! $tax->show_tagcloud || empty( $tax->labels->name ) ) ) || ! is_object( $tax ) ) {
                        continue;
                    }
                    $tag_taxonomies[ $tax->labels->name ] = esc_attr( $taxonomy );
                }
            }
            vc_map( array(
                'name' => 'WP ' . __( 'Tag Cloud', 'wyde-core' ),
                'base' => 'vc_wp_tagcloud',
                'icon' => 'icon-wpb-wp',
                'category' => __( 'WordPress Widgets', 'wyde-core' ),
                'class' => 'wpb_vc_wp_widget',
                'weight' => - 50,
                'description' => __( 'Your most used tags in cloud format', 'wyde-core' ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Widget title', 'wyde-core' ),
                        'param_name' => 'title',
                        'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __( 'Taxonomy', 'wyde-core' ),
                        'param_name' => 'taxonomy',
                        'value' => $tag_taxonomies,
                        'description' => __( 'Select source for tag cloud.', 'wyde-core' ),
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),
                        'param_name' => 'el_class',
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    )
                )
            ) );

            $custom_menus = array();
            $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
            if ( is_array( $menus ) && ! empty( $menus ) ) {
                foreach ( $menus as $single_menu ) {
                    if ( is_object( $single_menu ) && isset( $single_menu->name, $single_menu->term_id ) ) {
                        $custom_menus[ $single_menu->name ] = $single_menu->term_id;
                    }
                }
            }
            vc_map( array(
                'name' => 'WP ' . __( "Custom Menu", 'wyde-core' ),
                'base' => 'vc_wp_custommenu',
                'icon' => 'icon-wpb-wp',
                'category' => __( 'WordPress Widgets', 'wyde-core' ),
                'class' => 'wpb_vc_wp_widget',
                'weight' => - 50,
                'description' => __( 'Use this widget to add one of your custom menus as a widget', 'wyde-core' ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Widget title', 'wyde-core' ),
                        'param_name' => 'title',
                        'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __( 'Menu', 'wyde-core' ),
                        'param_name' => 'nav_menu',
                        'value' => $custom_menus,
                        'description' => empty( $custom_menus ) ? __( 'Custom menus not found. Please visit <b>Appearance > Menus</b> page to create new menu.', 'wyde-core' ) : __( 'Select menu to display.', 'wyde-core' ),
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),
                        'param_name' => 'el_class',
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    )
                )
            ) );

            vc_map( array(
                'name' => 'WP ' . __( 'Text', 'wyde-core' ),
                'base' => 'vc_wp_text',
                'icon' => 'icon-wpb-wp',
                'category' => __( 'WordPress Widgets', 'wyde-core' ),
                'class' => 'wpb_vc_wp_widget',
                'weight' => - 50,
                'description' => __( 'Arbitrary text or HTML', 'wyde-core' ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Widget title', 'wyde-core' ),
                        'param_name' => 'title',
                        'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'textarea_html',
                        'holder' => 'div',
                        'heading' => __( 'Text', 'wyde-core' ),
                        'param_name' => 'content',
                        // 'admin_label' => true
                    ),
                    /*array(
                        'type' => 'checkbox',
                        'heading' => __( 'Automatically add paragraphs', 'wyde-core' ),
                        'param_name' => "filter"
                    ),*/
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),
                        'param_name' => 'el_class',
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    )
                )
            ) );

            vc_map( array(
                'name' => 'WP ' . __( 'Recent Posts', 'wyde-core' ),
                'base' => 'vc_wp_posts',
                'icon' => 'icon-wpb-wp',
                'category' => __( 'WordPress Widgets', 'wyde-core' ),
                'class' => 'wpb_vc_wp_widget',
                'weight' => - 50,
                'description' => __( 'The most recent posts on your site', 'wyde-core' ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Widget title', 'wyde-core' ),
                        'param_name' => 'title',
                        'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Number of posts', 'wyde-core' ),
                        'description' => __( 'Enter number of posts to display.', 'wyde-core' ),
                        'param_name' => 'number',
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __( 'Display post date?', 'wyde-core' ),
                        'param_name' => 'show_date',
                        'value' => array( __( 'Yes', 'wyde-core' ) => true ),
                        'description' => __( 'If checked, date will be displayed.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),
                        'param_name' => 'el_class',
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    )
                )
            ) );

            $link_category = array( __( 'All Links', 'wyde-core' ) => '' );
            $link_cats = get_terms( 'link_category' );
            if ( is_array( $link_cats ) && ! empty( $link_cats ) ) {
                foreach ( $link_cats as $link_cat ) {
                    if ( is_object( $link_cat ) && isset( $link_cat->name, $link_cat->term_id ) ) {
                        $link_category[ $link_cat->name ] = $link_cat->term_id;
                    }
                }
            }
            vc_map( array(
                'name' => 'WP ' . __( 'Links', 'wyde-core' ),
                'base' => 'vc_wp_links',
                'icon' => 'icon-wpb-wp',
                'category' => __( 'WordPress Widgets', 'wyde-core' ),
                'class' => 'wpb_vc_wp_widget',
                'content_element' => (bool) get_option( 'link_manager_enabled' ),
                'weight' => - 50,
                'description' => __( 'Your blogroll', 'wyde-core' ),
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => __( 'Link Category', 'wyde-core' ),
                        'param_name' => 'category',
                        'value' => $link_category,
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __( 'Order by', 'wyde-core' ),
                        'param_name' => 'orderby',
                        'value' => array(
                            __( 'Link title', 'wyde-core' ) => 'name',
                            __( 'Link rating', 'wyde-core' ) => 'rating',
                            __( 'Link ID', 'wyde-core' ) => 'id',
                            __( 'Random', 'wyde-core' ) => 'rand'
                        )
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __( 'Options', 'wyde-core' ),
                        'param_name' => 'options',
                        'value' => array(
                            __( 'Show Link Image', 'wyde-core' ) => 'images',
                            __( 'Show Link Name', 'wyde-core' ) => 'name',
                            __( 'Show Link Description', 'wyde-core' ) => 'description',
                            __( 'Show Link Rating', 'wyde-core' ) => 'rating'
                        )
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Number of links to show', 'wyde-core' ),
                        'param_name' => 'limit'
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),
                        'param_name' => 'el_class',
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    ),
                )
            ) );

            vc_map( array(
                'name' => 'WP ' . __( 'Categories', 'wyde-core' ),
                'base' => 'vc_wp_categories',
                'icon' => 'icon-wpb-wp',
                'category' => __( 'WordPress Widgets', 'wyde-core' ),
                'class' => 'wpb_vc_wp_widget',
                'weight' => - 50,
                'description' => __( 'A list or dropdown of categories', 'wyde-core' ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Widget title', 'wyde-core' ),
                        'param_name' => 'title',
                        'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __( 'Display Options', 'wyde-core' ),
                        'param_name' => 'options',
                        'value' => array(
                            __( 'Dropdown', 'wyde-core' ) => 'dropdown',
                            __( 'Show post counts', 'wyde-core' ) => 'count',
                            __( 'Show hierarchy', 'wyde-core' ) => 'hierarchical'
                        ),
                        'description' => __( 'Select display options for categories.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),
                        'param_name' => 'el_class',
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    ),
                )
            ) );

            vc_map( array(
                'name' => 'WP ' . __( 'Archives', 'wyde-core' ),
                'base' => 'vc_wp_archives',
                'icon' => 'icon-wpb-wp',
                'category' => __( 'WordPress Widgets', 'wyde-core' ),
                'class' => 'wpb_vc_wp_widget',
                'weight' => - 50,
                'description' => __( 'A monthly archive of your sites posts', 'wyde-core' ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Widget title', 'wyde-core' ),
                        'param_name' => 'title',
                        'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __( 'Display Options', 'wyde-core' ),
                        'param_name' => 'options',
                        'value' => array(
                            __( 'Dropdown', 'wyde-core' ) => 'dropdown',
                            __( 'Show post counts', 'wyde-core' ) => 'count'
                        ),
                        'description' => __( 'Select display options for archives.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),
                        'param_name' => 'el_class',
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    ),
                )
            ) );

            vc_map( array(
                'name' => 'WP ' . __( 'RSS', 'wyde-core' ),
                'base' => 'vc_wp_rss',
                'icon' => 'icon-wpb-wp',
                'category' => __( 'WordPress Widgets', 'wyde-core' ),
                'class' => 'wpb_vc_wp_widget',
                'weight' => - 50,
                'description' => __( 'Entries from any RSS or Atom feed', 'wyde-core' ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Widget title', 'wyde-core' ),
                        'param_name' => 'title',
                        'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'RSS feed URL', 'wyde-core' ),
                        'param_name' => 'url',
                        'description' => __( 'Enter the RSS feed URL.', 'wyde-core' ),
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __( 'Items', 'wyde-core' ),
                        'param_name' => 'items',
                        'value' => array(
                            __( '10 - Default', 'wyde-core' ) => '',
                            1,
                            2,
                            3,
                            4,
                            5,
                            6,
                            7,
                            8,
                            9,
                            10,
                            11,
                            12,
                            13,
                            14,
                            15,
                            16,
                            17,
                            18,
                            19,
                            20
                        ),
                        'description' => __( 'Select how many items to display.', 'wyde-core' ),
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __( 'Options', 'wyde-core' ),
                        'param_name' => 'options',
                        'value' => array(
                            __( 'Item content', 'wyde-core' ) => 'show_summary',
                            __( 'Display item author if available?', 'wyde-core' ) => 'show_author',
                            __( 'Display item date?', 'wyde-core' ) => 'show_date'
                        ),
                        'description' => __( 'Select display options for RSS feeds.', 'wyde-core' )
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Extra CSS Class', 'wyde-core' ),
                        'param_name' => 'el_class',
                        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                    ),
                )
            ) );
        
        }

        public function update_plugins_shortcodes(){
            $this->update_revslider_shortcodes();
            $this->update_contact_form7_shortcodes();
            $this->update_woocommerce_shortcodes();
            do_action('wyde_update_plugins_shortcodes');
        }

        public function update_revslider_shortcodes(){
            /***************************************** 
            /* SLIDER REVOLUTION
            /*****************************************/
            if ( class_exists( 'RevSlider' ) ) {
                
                $slider = new RevSlider();
                $arrSliders = $slider->getArrSliders();

                $revsliders = array();
                if ( $arrSliders ) {
                    foreach ( $arrSliders as $slider ) {
                        $revsliders[ $slider->getTitle() ] = $slider->getAlias();
                    }
                } else {
                    $revsliders[ __( 'No sliders found', 'wyde-core' ) ] = 0;
                }


                vc_map( array(
                    'base' => 'rev_slider_vc',
                    'name' => __( 'Revolution Slider', 'wyde-core' ),
                    'icon' => 'icon-wpb-revslider',
                    'category' => __( 'Content', 'wyde-core' ),
                    'weight'    => 1,
                    'description' => __( 'Place Revolution slider', 'wyde-core' ),
                    "params" => array(
                        array(
                            'type' => 'dropdown',
                            'heading' => __( 'Revolution Slider', 'wyde-core' ),
                            'param_name' => 'alias',
                            'admin_label' => true,
                            'value' => $revsliders,
                            'description' => __( 'Select your Revolution Slider.', 'wyde-core' )
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Scroll Button', 'wyde-core'),
                            'param_name' => 'button_style',
                            'value' => array(
                                __('Hide', 'wyde-core' ) => '',
                                __('Mouse Wheel', 'wyde-core' ) => '1',
                                __('Arrow Down', 'wyde-core' ) => '2',
                            ),
                            'description' => __('Select a scroll button at the bottom of slider.', 'wyde-core'),
                        ),
                        array(
                            'type' => 'colorpicker',
                            'heading' => __( 'Button Color', 'wyde-core' ),
                            'param_name' => 'color',
                            'description' => __( 'Select a button color.', 'wyde-core' ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Extra CSS Class', 'wyde-core' ),
                            'param_name' => 'el_class',
                            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
                        )
                    )
                ) );              

                
            }
        }

        public function update_contact_form7_shortcodes(){
            /***************************************** 
            /* CONTACT FORM 7
            /*****************************************/
            /**
             * Add Shortcode To Visual Composer
             */
            $cf7 = get_posts( 'post_type="wpcf7_contact_form"&numberposts=-1' );

            $contact_forms = array();
            if ( $cf7 ) {
                foreach ( $cf7 as $cform ) {
                    $contact_forms[ $cform->post_title ] = $cform->ID;
                }
            } else {
                $contact_forms[ __( 'No contact forms found', 'wyde-core' ) ] = 0;
            }

            vc_map( array(
                'base' => 'contact-form-7',
                'name' => __( 'Contact Form 7', 'wyde-core' ),
                'icon' => 'icon-wpb-contactform7',
                'category' => __( 'Content', 'wyde-core' ),
                'description' => __( 'Place Contact Form7', 'wyde-core' ),
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => __( 'Contact Form', 'wyde-core' ),
                        'param_name' => 'id',
                        'value' => $contact_forms,
                        'admin_label' => true,
                        'save_always' => true,
                        'description' => __( 'Select contact form from the drop down list.', 'wyde-core' ),
                    ),
                ),
            ) );
        }

        public function update_woocommerce_shortcodes(){
            /***************************************** 
            /* WOOCOMMERCE
            /*****************************************/
            if ( class_exists( 'WooCommerce' ) ) {

                /* Add default params for shortcodes */
                vc_map_update( 'woocommerce_cart', array( 'params' => array() ) );
                vc_map_update( 'woocommerce_checkout', array( 'params' => array() ) );
                vc_map_update( 'woocommerce_order_tracking', array( 'params' => array() ) );

                /* Recent products
                ---------------------------------------------------------- */
                vc_remove_param( 'recent_products', 'columns' );
                vc_add_param( 'recent_products', array(
                    'type' => 'dropdown',
                    'heading' => __('Columns', 'wyde-core'),
                    'weight' => 1,
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of columns.', 'wyde-core'),
                ) );

                /* Featured Products
                ---------------------------------------------------------- */
                vc_remove_param( 'featured_products', 'columns' );
                vc_add_param( 'featured_products', array(
                    'type' => 'dropdown',
                    'heading' => __('Columns', 'wyde-core'),
                    'weight' => 1,
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of columns.', 'wyde-core'),
                ) );

                /* Products
                ---------------------------------------------------------- */
                vc_remove_param( 'products', 'columns' );
                vc_add_param( 'products', array(
                    'type' => 'dropdown',
                    'heading' => __('Columns', 'wyde-core'),
                    'weight' => 1,
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of columns.', 'wyde-core'),
                ) );


                /* Product Category
                ---------------------------------------------------------- */
                vc_remove_param( 'product_category', 'columns' );
                vc_add_param( 'product_category', array(
                    'type' => 'dropdown',
                    'heading' => __('Columns', 'wyde-core'),
                    'weight' => 1,
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of columns.', 'wyde-core'),
                ) );


                /* Product Category
                ---------------------------------------------------------- */
                vc_remove_param( 'product_categories', 'columns' );
                vc_add_param( 'product_categories', array(
                    'type' => 'dropdown',
                    'heading' => __('Columns', 'wyde-core'),
                    'weight' => 1,
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of columns.', 'wyde-core'),
                ) );


                /* Sale products
                ---------------------------------------------------------- */
                vc_remove_param( 'sale_products', 'columns' );
                vc_add_param( 'sale_products', array(
                    'type' => 'dropdown',
                    'heading' => __('Columns', 'wyde-core'),
                    'weight' => 1,
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of columns.', 'wyde-core'),
                ) );

                /* Best Selling Products
                ---------------------------------------------------------- */
                vc_remove_param( 'best_selling_products', 'columns' );
                vc_add_param( 'best_selling_products', array(
                    'type' => 'dropdown',
                    'heading' => __('Columns', 'wyde-core'),
                    'weight' => 1,
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of columns.', 'wyde-core'),
                ) );

                /* Top Rated Products
                ---------------------------------------------------------- */
                vc_remove_param( 'top_rated_products', 'columns' );
                vc_add_param( 'top_rated_products', array(
                    'type' => 'dropdown',
                    'heading' => __('Columns', 'wyde-core'),
                    'weight' => 1,
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of columns.', 'wyde-core'),
                ) );

                /* Product Attribute
                ---------------------------------------------------------- */
                vc_remove_param( 'product_attribute', 'columns' );
                vc_add_param( 'product_attribute', array(
                    'type' => 'dropdown',
                    'heading' => __('Columns', 'wyde-core'),
                    'weight' => 1,
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of columns.', 'wyde-core'),
                ) );


                /* Related Products
                ---------------------------------------------------------- */
                vc_remove_param( 'related_products', 'columns' );
                vc_add_param( 'related_products', array(
                    'type' => 'dropdown',
                    'heading' => __('Columns', 'wyde-core'),
                    'weight' => 1,
                    'param_name' => 'columns',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4',
                        '5',
                        '6',
                    ),
                    'std' => '4',
                    'description' => __('Select the number of columns.', 'wyde-core'),
                ) );

            }
        }

        /* Deregister Grid Element post type */
        public function deregister_grid_element(){

            $this->unregister_post_type('vc_grid_item');
            remove_action('vc_menu_page_build', 'vc_gitem_add_submenu_page');

        }

        public function unregister_post_type( $post_type ){
            global $wp_post_types;
	        if ( isset( $wp_post_types[ $post_type ] ) ) {
                unset( $wp_post_types[ $post_type ] );
	        }
        }

        public function vc_metadata(){
            remove_action('wp_head', array(visual_composer(), 'addMetaData'));
            add_action('wp_head', array($this, 'update_vc_metadata'));

            remove_action('wp_head', array(visual_composer(), 'addNoScript'), 1000);            

            remove_filter('body_class', array(visual_composer(), 'bodyClass'));
        }

        public function update_vc_metadata(){
            echo '<meta name="generator" content="Visual Composer '.WPB_VC_VERSION.'"/>' . "\n";
        }

        public function get_iconpicker_options( $options ){
            $options = array(
                array(
                    'param_name' => 'icon_set',
                    'type' => 'dropdown',
                    'heading' => esc_html__( 'Icon Set', 'wyde-core' ),
                    'value' => array(
                        'Font Awesome' => '',
                        'Typicons' => 'typicons',
                        'Linecons' => 'linecons',
                        'Big Mug Line' => 'bigmug_line',
                        'Simple Line' => 'simple_line',
                    ),                  
                    'description' => esc_html__('Select an icon set.', 'wyde-core')
                ),
                array(
                    'param_name' => 'icon',
                    'type' => 'iconpicker',
                    'heading' => esc_html__( 'Icon', 'wyde-core' ),                   
                    'settings' => array(
                        'emptyIcon' => true, 
                        'iconsPerPage' => 4000, 
                    ),
                    'description' => esc_html__('Select an icon.', 'wyde-core'),
                    'dependency' => array(
                        'element' => 'icon_set',
                        'is_empty' => true,
                    ),
                ),
                array(
                    'param_name' => 'icon_typicons',
                    'type' => 'iconpicker',
                    'heading' => esc_html__( 'Icon', 'wyde-core' ),                   
                    'settings' => array(
                        'emptyIcon' => true, 
                        'type' => 'typicons',
                        'iconsPerPage' => 4000, 
                    ),
                    'description' => esc_html__('Select an icon.', 'wyde-core'),
                    'dependency' => array(
                        'element' => 'icon_set',
                        'value' => 'typicons',
                    ),
                ),
                array(
                    'param_name' => 'icon_linecons',
                    'type' => 'iconpicker',
                    'heading' => esc_html__( 'Icon', 'wyde-core' ),                   
                    'settings' => array(
                        'emptyIcon' => true, 
                        'type' => 'linecons',
                        'iconsPerPage' => 4000,
                    ),
                    'description' => esc_html__('Select an icon.', 'wyde-core'),
                    'dependency' => array(
                        'element' => 'icon_set',
                        'value' => 'linecons',
                    ),
                ),
                array(
                    'param_name' => 'icon_bigmug_line',
                    'type' => 'iconpicker',
                    'heading' => esc_html__( 'Icon', 'wyde-core' ),                   
                    'settings' => array(
                        'emptyIcon' => true, 
                        'type' => 'bigmug_line',
                        'iconsPerPage' => 4000,
                    ),
                    'description' => esc_html__('Select an icon.', 'wyde-core'),
                    'dependency' => array(
                        'element' => 'icon_set',
                        'value' => 'bigmug_line',
                    ),
                ),
                array(
                    'param_name' => 'icon_simple_line',
                    'type' => 'iconpicker',
                    'heading' => esc_html__( 'Icon', 'wyde-core' ),                
                    'settings' => array(
                        'emptyIcon' => true, 
                        'type' => 'simple_line',
                        'iconsPerPage' => 4000,
                    ),
                    'description' => esc_html__('Select an icon.', 'wyde-core'),
                    'dependency' => array(
                        'element' => 'icon_set',
                        'value' => 'simple_line',
                    ),
                ),
                        

            );

            return $options;
        }       
        
        /* Add new fonts to Google Fonts field */
        public function get_google_fonts( $fonts ){
            $fonts[] = json_decode('{"font_family":"Alegreya","font_styles":"regular,italic,bold,bold italic,900 italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold:700:normal,700 bold italic:700:italic,900 bold italic:900:italic"}');
            $fonts[] = json_decode('{"font_family":"Dancing Script","font_styles":"regular,bold","font_types":"400 regular:400:normal,700 bold:700:normal"}');
            //$fonts[] = json_decode('{"font_family":"Libre Baskerville","font_styles":"regular,italic,bold","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold:700:normal"}');
            $fonts[] = json_decode('{"font_family":"Lobster Two","font_styles":"regular,italic,bold,bold italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold:700:normal,700 bold italic:700:italic"}');
            //$fonts[] = json_decode('{"font_family":"Lora","font_styles":"regular,italic,bold,bold italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold:700:normal,700 bold italic:700:italic"}');
            $fonts[] = json_decode('{"font_family":"Questrial","font_styles":"regular","font_types":"400 regular:400:normal"}');
            $fonts[] = json_decode('{"font_family":"Source Sans Pro","font_styles":"regular,italic,bold,bold italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold:700:normal,700 bold italic:700:italic"}');
            asort($fonts);
            return $fonts;
        }

        /* Add Google font links into body tag */
        public function body_stylesheets(){ 
            global $wyde_body_stylesheets;           
            $body_stylesheets = apply_filters('wyde_body_stylesheets', $wyde_body_stylesheets);
            /* Add stylesheets inside body tag */
            if( is_array($body_stylesheets) ){
                foreach ($body_stylesheets as $key => $value) {
                    echo sprintf('<link id="%s" href="%s" rel="stylesheet" property="stylesheet" type="text/css" media="all" />', esc_attr($key), esc_url($value) );
                }
            }
        }

        public function get_animations(){

            $animations =  array(
                ''                                      => 'No Animation',
                'bounceIn'                              => 'Bounce In',
                'bounceInUp'                            => 'Bounce In Up',
                'bounceInDown'                          => 'Bounce In Down',
                'bounceInLeft'                          => 'Bounce In Left',
                'bounceInRight'                         => 'Bounce In Right',
                'fadeIn'                                => 'Fade In',
                'fadeInUp'                              => 'Fade In Up',
                'fadeInDown'                            => 'Fade In Down',
                'fadeInLeft'                            => 'Fade In Left',
                'fadeInRight'                           => 'Fade In Right',
                'fadeInUpBig'                           => 'Fade In Up Long',
                'fadeInDownBig'                         => 'Fade In Down Long',
                'fadeInLeftBig'                         => 'Fade In Left Long',
                'fadeInRightBig'                        => 'Fade In Right Long',
                'lightSpeedIn'                          => 'Light Speed In',
                'pulse'                                 => 'Pulse',
                'rollIn'                                => 'Roll In',
                'rotateIn'                              => 'Rotate In',
                'slideInUp'                             => 'Slide In Up',
                'slideInDown'                           => 'Slide In Down',
                'slideInLeft'                           => 'Slide In Left',
                'slideInRight'                          => 'Slide In Right',
                'swing'                                 => 'Swing',
                'zoomIn'                                => 'Zoom In',
            );

            return apply_filters('wyde_get_animations', $animations);
        }

        public function animation_field($settings, $value) {   
            
            $html ='<div class="wyde-animation">';
            $html .='<div class="animation-field">';
            $html .= sprintf('<select name="%1$s" class="wpb_vc_param_value %1$s %2$s_field">', esc_attr( $settings['param_name'] ), esc_attr( $settings['type'] ));

            $animations  = $this->get_animations();

            foreach($animations as $key => $text){
                $html .= sprintf('<option value="%s" %s>%s</option>', esc_attr( $key ), ($value==$key? ' selected':''), esc_html( $text ) );
            }

            $html .= '</select>';
            $html .= '</div>';
            $html .= '<div class="animation-preview"><span>Animation</span></div>';
            $html .= '</div>';

            return $html;

        }

        public function gmaps_field($settings, $value) {

            $html ='<div class="wyde-gmaps">';
            $html .='<div class="wyde-gmaps-settings">';
            $html .= sprintf('<input name="%1$s" class="wpb_vc_param_value %1$s %2$s_field" type="hidden" value="%3$s" />', esc_attr( $settings['param_name'] ), esc_attr( $settings['type'] ), esc_attr( $value ));
            $html .= '</div>';
            $html .= '<div class="vc_column vc_clearfix">';
            $html .= '  <div class="gmaps-canvas" style="height:300px;margin-top:10px;"></div>';
            $html .= '</div>';
            $html .= '</div>';

            return $html;

        }
    
    }

}

new Wyde_Shortcode();    
?>