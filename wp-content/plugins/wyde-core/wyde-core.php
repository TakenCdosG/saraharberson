<?php
/*
Plugin Name: Wyde Core
Plugin URI: http://www.wydethemes.com
Description: Core Plugin for Wyde Themes
Version: 3.1.0
Author: Wyde
Author URI: http://www.wydethemes.com
*/

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Current Wyde Core plugin version
 */
if ( ! defined( 'WYDE_VERSION' ) ) {
	define( 'WYDE_VERSION', '3.1.0' );
}

if( !class_exists( 'Wyde_Core' ) ) {
	
	class Wyde_Core {

	    /**
	     * Unique identifier for your plugin.
	     *
	     * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	     * match the Text Domain file header in the main plugin file.
	     *
	     * @since    1.0.0
	     *
	     * @var      string
	     */
	    protected $plugin_slug = 'wyde-core';

	    /**
	     * Instance of this class.
	     *
	     * @since    1.0.0
	     *
	     * @var      object
	     */
	    protected static $instance = null;
		
		/**
		 * This method adds other methods to specific hooks within WordPress.
		 *
		 * @since     1.0.0
		 */
		public function __construct() {
			$this->constants();
			$this->init();
		}

		/**
		 * Defines plugin constants
		 *
		 * @since     2.0.0
		 */
		function constants() {        	
        	define('WYDE_PLUGIN_DIR', plugin_dir_path(__FILE__));
        	define('WYDE_PLUGIN_URI', plugin_dir_url(__FILE__));
		}

		/**
		 * Initialize the plugin by setting localization and loading public scripts
		 *
		 * @since     1.0.0
		 */
		function init() {

        	/** Redux Framework **/
			if ( file_exists( WYDE_PLUGIN_DIR .'inc/redux-framework/framework.php' ) ) {
			    require_once( WYDE_PLUGIN_DIR .'inc/redux-framework/framework.php' );
			}

			/** Metaboxes **/
			require_once( WYDE_PLUGIN_DIR .'inc/metaboxes/init.php' );

			/** Wyde Importer **/
			require_once( WYDE_PLUGIN_DIR .'inc/importer/class-wyde-importer.php' );

			/** Wyde Mega Menu class **/
			require_once( WYDE_PLUGIN_DIR .'inc/class-wyde-mega-menu.php' );

			/** Wyde Ajax Search **/
			require_once( WYDE_PLUGIN_DIR .'inc/class-wyde-ajax-search.php' );

			/** Wyde Widgets **/
			require_once( WYDE_PLUGIN_DIR .'widgets/class-wyde-widget.php' );

			/** Wyde Shortcodes **/
			require_once( WYDE_PLUGIN_DIR .'shortcodes/helpers.php' );
			require_once( WYDE_PLUGIN_DIR .'shortcodes/class-wyde-shortcode.php' );


			add_action( 'init', array($this, 'register_post_types') );

			add_action( 'after_setup_theme', array($this, 'after_setup_theme'));

			add_action( 'wp_enqueue_scripts', array($this, 'load_front_scripts') );

			add_action( 'admin_enqueue_scripts', array($this, 'load_admin_scripts') );			

			add_filter( 'enter_title_here', array($this, 'get_post_title_placeholder') );

		}		

	    /**
	     * Return an instance of this class.
	     *
	     * @since     1.0.0
	     *
	     * @return    object    A single instance of this class.
	     */
	    public static function get_instance() {

		    // If the single instance hasn't been set, set it now.
		    if ( null == self::$instance ) {
			    self::$instance = new self;
		    }

		    return self::$instance;
	    }		

		/**
		 * Register scripts/styles
		 *
		 * @since     3.0.0
		 */
		function after_setup_theme(){
			load_plugin_textdomain( 'wyde-core', false, WYDE_PLUGIN_DIR . 'languages' );
			// Editor style
			add_editor_style( WYDE_PLUGIN_URI. 'assets/css/font-awesome.min.css' );	   
		}

		/**
		 * Load admin scripts/styles
		 *
		 * @since     3.0.0
		 */
		function load_admin_scripts(){			
			// FontAwesome
			wp_enqueue_style('wyde-font-awesome', WYDE_PLUGIN_URI. 'assets/css/font-awesome.min.css', null, '4.4.0');
		}

		/**
		 * Load front scripts/styles
		 *
		 * @since     3.0.0
		 */
		function load_front_scripts(){			
			// FontAwesome
			wp_enqueue_style('wyde-font-awesome', WYDE_PLUGIN_URI. 'assets/css/font-awesome.min.css', null, '4.4.0');

			// Modernizr
		    wp_enqueue_script('modernizr', WYDE_PLUGIN_URI . 'assets/js/modernizr.js', array('jquery'), null, false);

			// Wyde core scripts
		    wp_enqueue_script('wyde-core', WYDE_PLUGIN_URI . 'assets/js/wyde.js', array('jquery'), WYDE_VERSION, true);

		}

		/**
		 * Register custom post types
		 *
		 * @since     1.0.0
		 */
		function register_post_types(){
		    
		    global $wp_post_types;

		    $portfolio_slug = apply_filters('wyde_portfolio_slug', 'portfolio-item');

		    //Portfolio post type
		    register_post_type('wyde_portfolio',
				array(
					'labels' => array(
					    'name' 			=> __( 'Portfolios', 'wyde-core' ),
					    'singular_name' => __( 'Portfolio', 'wyde-core' ),
	                    'add_new' => __('Add New', 'wyde-core' ),
	                    'add_new_item' => __('Add New Portfolio', 'wyde-core'),
	                    'edit_item' => __('Edit Portfolio', 'wyde-core'),
	                    'new_item' => __('New Portfolio', 'wyde-core'),
	                    'view_item' => __('View Portfolios', 'wyde-core'),
	                    'menu_name' => __('Portfolios', 'wyde-core')
					),
					'public' => true,
					'has_archive' => false,
					'rewrite' => array(
					    'slug' => $portfolio_slug,
					    'with_front' => false,
					),
					'supports' => array( 'title', 'editor', 'thumbnail'),
					'can_export' => true,
		            'menu_icon' => 'dashicons-portfolio',
				)
			);

		    //Portfolio Category    
		    register_taxonomy('portfolio_category', 'wyde_portfolio', 
		        array(
		            'hierarchical' => true, 
		            'labels' => array(
	                    'name' => __('Portfolio Categories', 'wyde-core'),
	                    'singular_name' => __('Category', 'wyde-core'),
	                    'all_items' => __('All Categories', 'wyde-core' ),
	                    'edit_item' => __('Edit Category', 'wyde-core' ),
	                    'update_item' => __('Update Category', 'wyde-core' ),
	                    'add_new_item' => __('Add New Category', 'wyde-core' ),
	                    'new_item_name' => __('New Category', 'wyde-core' ),
		            ), 
		            'query_var' => true, 
		            'rewrite' => array(
					    'slug' => 'portfolio-category',
					    'with_front' => false,
				    ),
				    'show_admin_column' => true,
				    'sort' => true
		        )
		    );

		    //Portfolio Skill   
			register_taxonomy('portfolio_skill', 'wyde_portfolio', 
		        array(
		            'hierarchical' => true, 
		            'labels' => array(
	                    'name' =>  __('Portfolio Skills', 'wyde-core'),
	                    'singular_name' => __('Skill', 'wyde-core'),
	                    'all_items' => __('All Skills', 'wyde-core'),
	                    'edit_item' => __('Edit Skill', 'wyde-core'),
	                    'update_item' =>  __('Update Skill', 'wyde-core'),
	                    'add_new_item' => __('Add New Skill', 'wyde-core'),
	                    'new_item_name' => __('New Skill', 'wyde-core'),
		            ), 
		            'query_var' => true, 
		            'rewrite' => array(
					    'slug' => 'portfolio-skill',
					    'with_front' => false,
				    )
		        )
		    );
		            
		    //Portfolio Tags   
			register_taxonomy('portfolio_tag', 'wyde_portfolio', 
		        array(
		            'hierarchical' => false, 
		            'labels' => array(
		                'name' => __('Portfolio Tags', 'wyde-core'),
		                'singular_name' => __('Tag', 'wyde-core'),
		                'all_items' => __('All Tags', 'wyde-core'),
		                'edit_item' => __('Edit Tag', 'wyde-core'),
		                'update_item' => __('Update Tag', 'wyde-core'),
		                'add_new_item' => __('Add New Tag', 'wyde-core'),
		                'new_item_name' =>  __('New Tag', 'wyde-core'),
		            ), 
		            'query_var' => true, 
		            'rewrite' => array(
						'slug' => 'portfolio-tag',
						'with_front' => false,
				    )
		        )
		    );

			
		    //Testimonial 	
		    register_post_type('wyde_testimonial',
				array(
					'labels' => array(
						    'name' 			=> __( 'Testimonials', 'wyde-core' ),
						    'singular_name' => __( 'Testimonial', 'wyde-core' ),
		                    'add_new' => __('Add New', 'wyde-core' ),
		                    'add_new_item' => __('Add New Testimonial', 'wyde-core'),
		                    'edit_item' => __('Edit Testimonial', 'wyde-core'),
		                    'new_item' => __('New Testimonial', 'wyde-core'),
		                    'view_item' => __('View Testimonial', 'wyde-core'),
		                    'menu_name' => __('Testimonials', 'wyde-core')
					),
					'public' => true,
					'has_archive' => false,
					'rewrite' => array(
						    'slug' => 'testimonial-item',
						    'with_front' => false,
					),
					'supports' => array( 'title', 'thumbnail'),
					'can_export' => true,
		            'exclude_from_search' => true,
		            'menu_icon' => 'dashicons-testimonial'
				)
			);
		            
		    //Testimonial Category
		    register_taxonomy('testimonial_category', 'wyde_testimonial', 
		        array(
		            'hierarchical' => true, 
		            'labels' => array(
		                    'name' => __('Testimonial Categories', 'wyde-core'),
		                    'singular_name' => __('Category', 'wyde-core'),
		                    'all_items' => __('All Categories', 'wyde-core' ),
		                    'edit_item' => __('Edit Category', 'wyde-core' ),
		                    'update_item' => __('Update Category', 'wyde-core' ),
		                    'add_new_item' => __('Add New Category', 'wyde-core' ),
		                    'new_item_name' => __('New Category', 'wyde-core' ),
		                ),
		            'query_var' => true, 
		            'rewrite' => true,
		            'show_admin_column' => true,
				    'sort' => true
		        )
		    );

		    //Team Member
		    register_post_type('wyde_team_member', 
		        array(
					'labels' => array(
				        'name' 					=> __('Team Members', 'wyde-core' ),
				        'singular_name' 		=> __('Team Member', 'wyde-core' ),
				        'add_new' 				=> __('Add New', 'wyde-core' ),
				        'add_new_item' 			=> __('Add New Team Member', 'wyde-core' ),
				        'edit_item' 			=> __('Edit Team Member', 'wyde-core' ),
				        'new_item' 				=> __('New Team Member', 'wyde-core' ),
				        'all_items' 			=> __('All Team Members', 'wyde-core' ),
				        'view_item' 			=> __('View Team Members', 'wyde-core' ),
				        'search_items' 			=> __('Search Team Members', 'wyde-core' ),
				        'parent_item_colon' 	=> '',
				        'menu_name' 			=> __( 'Team Members', 'wyde-core' )
		            ),
					'public'    => true,
					'has_archive'   => false,
					'supports' => array( 'title', 'thumbnail'),
		            'exclude_from_search'   => true,
					'menu_icon' => 'dashicons-groups',
		            'rewrite'   => array(
					    'slug' => 'team-member',
					    'with_front' => false,
					),
				)
		    );


		    //Team Member Category
		    register_taxonomy('team_member_category', 'wyde_team_member', 
		        array(
		            'hierarchical' => true, 
		            'labels' => array(
	                    'name' => __('Team Member Categories', 'wyde-core'),
	                    'singular_name' => __('Category', 'wyde-core'),
	                    'all_items' => __('All Categories', 'wyde-core' ),
	                    'edit_item' => __('Edit Category', 'wyde-core' ),
	                    'update_item' => __('Update Category', 'wyde-core' ),
	                    'add_new_item' => __('Add New Category', 'wyde-core' ),
	                    'new_item_name' => __('New Category', 'wyde-core' ),
		            ),
		            'query_var' => true, 
		            'rewrite' => true,
		            'show_admin_column' => true,
				    'sort' => true
		        )
		    );
		        
		}


		/* Set custom post type title placeholder */
		function get_post_title_placeholder ( $title ) {

		    $post_type = get_post_type( get_the_ID() );

		    switch( $post_type ){
		        case 'wyde_testimonial':
		        $title = esc_html__( 'Enter the customer\'s name here', 'wyde-core' );
		        break;
		        case 'wyde_team_member':
		        $title = esc_html__( 'Enter the member\'s name here', 'wyde-core' );
		        break;
		    }

			return $title;
		}	

		/** Load Font Icons from css file **/
        public static function get_font_icons_from_css( $css_uri ) {

            $css = '';
            if( !empty($css_uri) ){
                ob_start();
                include_once $css_uri;
                $css = ob_get_clean();
            }
		
	        $icons = array();
	        $hex_codes = array();

	        preg_match_all( '/\.(icon-|fa-)([^,}]*)\s*:before\s*{\s*(content:)\s*"(\\\\[^"]+)"/s', $css, $matches );
	        $icons = $matches[2];
	        $hex_codes = $matches[4];

	        $icons = array_combine( $hex_codes, $icons );

	        asort( $icons );

	        return $icons;

        }

        public static function get_font_icons( $name, $version = 1.0, $css_uri){
            $cache_version = get_transient( $name.'_current_version' );
            $icons = get_transient( $name.'_icons' );
            if($cache_version == false || $cache_version < $version || $icons == false){
	            $icons = Wyde_Core::get_font_icons_from_css( $css_uri );
	            set_transient( $name.'_icons', $icons, 4 * WEEK_IN_SECONDS );
	            set_transient( $name.'_current_version', $version, 4 * WEEK_IN_SECONDS );
            }
            return $icons;
        }

	}

// Instantiate the class
$wyde_core = new Wyde_Core();

}