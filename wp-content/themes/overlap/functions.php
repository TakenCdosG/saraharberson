<?php

/* Setup Theme */
function overlap_setup_theme() {
    // Make theme available for translation.
    load_theme_textdomain('overlap', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Woocommerce Support
    add_theme_support('woocommerce');    

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support('post-thumbnails' );
    // Square sizes (1:1)
    add_image_size('overlap-medium', 340, 340, true);
    add_image_size('overlap-large', 640, 640, true);
    add_image_size('overlap-xlarge', 960, 960, true);
    // Portrait size (4:3)
    add_image_size('overlap-portrait-medium', 600, 800, true);
    // Landscape sizes (16:9)
    add_image_size('overlap-land-large', 960, 540, true);
    // Masonry size
    add_image_size('overlap-masonry', 640, 9999);    
    // Full Width size 
    add_image_size('overlap-fullwidth', 1280, 9999);

    // Enable support for Post Formats
    add_theme_support('post-formats', array(
        'audio', 'gallery', 'link', 'quote', 'video'
    ));

    // Register navigation locations
	register_nav_menus( array(
		'primary'   => esc_html__('Primary Navigation', 'overlap'),
		'footer' => esc_html__('Footer Navigation', 'overlap')
	));

    // Add html editor css styles
    add_editor_style( array( 'css/icons.css', 'css/editor.css' ) );
    
    // This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );

}
add_action('after_setup_theme', 'overlap_setup_theme');

/* Sets the content width in pixels, based on the theme's design and stylesheet. */
function overlap_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'overlap_content_width', 1170 );
}
add_action( 'after_setup_theme', 'overlap_content_width', 0 );

/* Initialize Widgets */
function overlap_widgets_init(){

    /* Register sidebar locations */
    // Default widget area on blog page
    register_sidebar(array(
        'name' => esc_html__('Blog Sidebar', 'overlap'),
        'id' => 'blog',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
    
    // Widget area for page (1)
    register_sidebar(array(
		'name' => esc_html__('Page Sidebar 1', 'overlap'),
		'id' => 'page1',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

    // Widget area for page (2)
    register_sidebar(array(
		'name' => esc_html__('Page Sidebar 2', 'overlap'),
		'id' => 'page2',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));    

    // Default widget area on shop page
    register_sidebar(array(
		'name' => esc_html__('Shop Sidebar', 'overlap'),
		'id' => 'shop',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

    // Sliding bar widget area in the right hand side
    register_sidebar(array(
		'name' => esc_html__('Sliding Bar', 'overlap'),
		'id' => 'slidingbar',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

}
add_action( 'widgets_init', 'overlap_widgets_init' );

/* Register and enqueue styles */
function overlap_load_styles(){
    
    $theme_info = wp_get_theme();

    $version = $theme_info->get( 'Version' );

    /* Plugins stylesheet */    
    // Deregister CSS from Visual Composer and other plugins
    wp_deregister_style('flexslider');
    wp_deregister_style('prettyphoto');        
    wp_deregister_style('js_composer_front');
    wp_deregister_style('contact-form-7');

    // Deregister font icons from Visual Composer
    wp_deregister_style('font-awesome');
    wp_deregister_style('vc_openiconic');
    wp_deregister_style('vc_typicons');
    wp_deregister_style('vc_entypo');
    wp_deregister_style('vc_linecons');

    // Add theme stylesheet file
    wp_enqueue_style('overlap', get_stylesheet_uri(), null, $version);

    // Add main stylesheet
    wp_enqueue_style('overlap-main', get_template_directory_uri() . '/css/main.css', array('overlap'), $version);
    
    // Register font icons
    wp_enqueue_style('overlap-icons', get_template_directory_uri() . '/css/icons.css', array('overlap-main'), $version);
    do_action( 'overlap_enqueue_icon_font' );

    // Shortcodes
    wp_enqueue_style('overlap-shortcodes', get_template_directory_uri(). '/css/shortcodes.css', null, $version);    
    
    if( class_exists( 'WooCommerce' ) ){
        // Deregister WooCommerce CSS
        wp_deregister_style('woocommerce-layout');
        wp_deregister_style('woocommerce-smallscreen');
        wp_deregister_style('woocommerce-general');
        wp_deregister_style('woocommerce_prettyPhoto_css');
    }

}
add_action('wp_enqueue_scripts', 'overlap_load_styles');

/* Register and enqueue scripts */
function overlap_load_scripts(){
    
    $theme_info = wp_get_theme();

    $version = $theme_info->get( 'Version' );

    // Deregister scripts from Visual Composer and other plugins
    wp_deregister_script( 'bootstrapjs' );
    wp_deregister_script( 'wpb_composer_front_js' );
    wp_deregister_script( 'jcarousellite' );
    wp_deregister_script( 'waypoints' );
    wp_deregister_script( 'isotope' );
    wp_deregister_script( 'flexslider' );
    wp_deregister_script( 'prettyphoto' );    
    wp_deregister_script( 'ChartJS' );
    wp_deregister_script( 'vc_line_chart' );
    wp_deregister_script( 'vc_round_chart' );

    if( class_exists('WooCommerce') ){
        wp_dequeue_script( 'prettyPhoto' );
        wp_dequeue_script( 'prettyPhoto-init' );
    }
    
    // jQuery scripts.
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-effects-core');    

    // jQuery plugins
    wp_enqueue_script('overlap-plugins', get_template_directory_uri() . '/js/plugins.js', array('jquery'), $version, true);

    // Main scripts
    wp_enqueue_script('overlap-main', get_template_directory_uri() . '/js/main.js', array('jquery'), $version, true);

    // Shortcodes scripts
    wp_enqueue_script('overlap-shortcodes', get_template_directory_uri() . '/js/shortcodes.js', array('overlap-main'), $version, true);

    $wyde_page_settings = array();
    $wyde_page_settings['siteURL'] = get_home_url();
    if( overlap_get_option('preload_images') ){
        $wyde_page_settings['isPreload'] = true;
    }

    if( overlap_get_option('mobile_animation') ){
        $wyde_page_settings['mobile_animation'] = true;
    }

    $wyde_page_settings['ajaxURL'] = admin_url( 'admin-ajax.php' );

    // Ajax Search
    if( overlap_get_option('ajax_search') ){
        $wyde_page_settings['ajax_search'] = true;
    }

    // Ajax Page Transition enabled
    if( overlap_get_option('ajax_page') ){

        $ajax_page_settings = array();
        $ajax_page_settings['transition'] =  overlap_get_option('ajax_page_transition');
        if( is_array( overlap_get_option('ajax_page_exclude_urls') ) ){
            $ajax_page_settings['excludeURLs'] = overlap_get_option('ajax_page_exclude_urls');
        }

        $wyde_page_settings['ajax_page'] = true;
        $wyde_page_settings['ajax_page_settings'] = $ajax_page_settings;

        wp_enqueue_script('comment-reply');
        wp_enqueue_script('wp-mediaelement');
        wp_enqueue_script('googlemaps');             

    }

    // Smooth Scroll
    if( overlap_get_option('smooth_scroll') ){
        $wyde_page_settings['smooth_scroll'] = true;
    }

    wp_localize_script('overlap-main', 'wyde_page_settings', $wyde_page_settings);

    wp_enqueue_script('smoothscroll', get_template_directory_uri() .'/js/smoothscroll.js', null, $version, true);   

}
add_action('wp_enqueue_scripts', 'overlap_load_scripts');

/* Register and enqueue admin scripts */
function overlap_load_admin_styles(){   
    
    //Deregister font icons from Visual Composer
    wp_deregister_style('font-awesome');
    wp_deregister_style('vc_openiconic');
    wp_deregister_style('vc_typicons');
    wp_deregister_style('vc_entypo');
    wp_deregister_style('vc_linecons');

    // Register font icons
    wp_register_style('theme-icons', get_template_directory_uri() .'/css/icons.css', null, null);
    wp_enqueue_style('theme-icons');
    do_action( 'overlap_enqueue_icon_font' );

}
add_action( 'admin_enqueue_scripts', 'overlap_load_admin_styles');

/* Theme activation - update WooCommerce image sizes */
function overlap_theme_activation()
{
	global $pagenow;
	if(is_admin() && 'themes.php' == $pagenow && isset($_GET['activated']))
	{	
        //update WooCommerce thumbnail sizes after theme activation
        update_option('shop_thumbnail_image_size', array('width' => 180, 'height' => 180, 'crop'    =>  true));
        update_option('shop_catalog_image_size', array('width' => 340, 'height' => 340, 'crop'  =>    true));
        update_option('shop_single_image_size', array('width' => 640, 'height' => 640, 'crop'   => true));
	}
}
add_action('admin_init','overlap_theme_activation');

/* Register default function when plugin not activated */
function overlap_register_functions() {
    if( !function_exists('is_woocommerce') ) {
        function is_woocommerce() { return false; }
    }
}
add_action('wp_head', 'overlap_register_functions', 5);

/* Update post views */
function overlap_track_post_views ($post_id) {
    if ( !is_single() ){
        return;
    }
    if ( empty ( $post_id) ) {
        global $post;
        $post_id = $post->ID;   
    }
    overlap_set_post_views($post_id);
}
add_action( 'wp_head', 'overlap_track_post_views');

/* Advanced scripts from Theme Options */
function overlap_custom_head_script(){   
     ob_start();
    include_once get_template_directory() .'/css/custom-css.php';
    $head_content = ob_get_clean();
    echo '<style type="text/css" data-name="overlap-color-scheme">' .$head_content.'</style>';

    if( overlap_get_option('head_script') ){
        /**
        *Extra HTML/JavaScript/Stylesheet from theme options > advanced - head content
        */
        echo apply_filters('overlap_head_content', overlap_get_option('head_script') );       
    } 
}
add_action('wp_head', 'overlap_custom_head_script', 200);

/* Get body classes */
function overlap_get_body_class( $classes ){
                        
        if( overlap_get_option('onepage') ) $classes[] = 'onepage';

        $classes[] = overlap_get_nav_layout() .'-nav';
        
        if( !overlap_has_header() ){            
            $classes[] = 'no-header';
        }                 

        if( !overlap_has_title_area() ){
            $classes[] = 'no-title';
        }

        return $classes;
}
add_filter( 'body_class', 'overlap_get_body_class' );

/* Footer scripts/styles */
function overlap_footer_content(){
    if( overlap_get_option('footer_script') ){
        /**
        *Extra HTML/JavaScript/Stylesheet from theme options > advanced - body content
        */        
        echo apply_filters('overlap_footer_content', overlap_get_option('footer_script') );
    }
}
add_action('wp_footer', 'overlap_footer_content');

/* Get viewport meta content */
function overlap_get_viewport(){
    $output = 'width=device-width, initial-scale=1.0';
    if( !overlap_get_option('mobile_zoom') ){
        $output .= ', maximum-scale=1.0, user-scalable=no';
    }   
    return apply_filters('overlap_get_viewport', $output);
}

/* Custom favicon */
function overlap_custom_favicon(){
    $output = '';
    if( overlap_get_option('favicon_image') ) :
        $favicon_image = overlap_get_option('favicon_image');
        $output .= '<link rel="icon" href="'. esc_url( $favicon_image['url'] ) .'" type="image/png" />';
    endif;

    if( overlap_get_option('favicon') ):
        $favicon = overlap_get_option('favicon');
        $output .= '<link rel="shortcut icon" href="'. esc_url( $favicon['url'] ) .'" type="image/x-icon" />';
    endif;

    if( overlap_get_option('favicon_iphone') ):
        $favicon_iphone = overlap_get_option('favicon_iphone');
        $output .= '<link rel="apple-touch-icon-precomposed" href="'. esc_url( $favicon_iphone['url'] ) .'" />';
    endif;

    if( overlap_get_option('favicon_iphone_retina') ):
        $favicon_iphone_retina = overlap_get_option('favicon_iphone_retina');
        $output .= '<link rel="apple-touch-icon-precomposed" sizes="114x114" href="'. esc_url( $favicon_iphone_retina['url'] ) .'" />';
    endif;

    if( overlap_get_option('favicon_ipad') ):
        $favicon_ipad = overlap_get_option('favicon_ipad');
        $output .= '<link rel="apple-touch-icon-precomposed" sizes="72x72" href="'. esc_url( $favicon_ipad['url'] ) .'" />';
    endif;

    if( overlap_get_option('favicon_ipad_retina') ):
        $favicon_ipad_retina = overlap_get_option('favicon_ipad_retina');
        $output .= '<link rel="apple-touch-icon-precomposed" sizes="144x144" href="'. esc_url( $favicon_ipad_retina['url'] ) .'" />';
    endif;
    
    echo apply_filters('overlap_custom_favicon', $output);
}

/* Page loader */
function overlap_page_loader(){
    $output = '';
    if( overlap_get_option('page_loader') && overlap_get_option('page_loader') != 'none' ){
        $output = '<div id="preloader">'. overlap_get_page_loader( overlap_get_option('page_loader') ) .'</div>';
    }
    echo apply_filters('overlap_page_loader', $output);
}

/* Navigation and Live search */
function overlap_nav(){
    get_template_part( 'templates/navigation/side-nav' );
    get_template_part( 'templates/navigation/header-nav' );    
    get_template_part( 'templates/navigation/fullscreen-nav' );    
    get_template_part( 'templates/navigation/slidingbar' ); 
    echo '<div id="page-overlay"></div>';
    get_template_part( 'templates/navigation/live-search' );
}

/* Footer content */
function overlap_footer(){

    $footer_content = overlap_show_footer_content();
    $footer_bar = overlap_show_footer_bar();

    ?>
    <footer id="footer">
    <?php

        $footer_page_id = overlap_get_option('footer_page');

        if( $footer_content && !empty($footer_page_id) ){        
            
            $footer_page = get_post( $footer_page_id );

            if( $footer_page ) :

                $post_custom_css = get_post_meta( $footer_page_id, '_wpb_post_custom_css', true );
                if ( ! empty( $post_custom_css ) ) {
                    echo '<style type="text/css" data-type="vc_custom-css" scoped>'.$post_custom_css.'</style>';
                }

                $shortcodes_custom_css = get_post_meta( $footer_page_id, '_wpb_shortcodes_custom_css', true );
                if ( ! empty( $shortcodes_custom_css ) ) {
                    echo '<style type="text/css" data-type="vc_shortcodes-custom-css" scoped>'.$shortcodes_custom_css.'</style>';
                }        
                    
        ?>
        <div id="footer-content">
            <?php echo do_shortcode($footer_page->post_content); ?>
        </div>
        <?php
            endif;          
        }     

        if( $footer_bar ){
            get_template_part( 'templates/footers/footer', 'v'. intval( overlap_get_option('footer_layout') ) );
        } 
        ?>
    </footer>
    <?php 
    
    if( overlap_get_option('totop_button') ) : ?>
    <a id="toplink-button" href="#">
        <span class="border">
            <i class="ol-up"></i>
        </span>
    </a>
    <?php 
    endif;

}
add_action('wp_footer', 'overlap_footer');

/* After switch theme */
function overlap_after_switch_theme() {
    //flush rewrite rules after switch theme
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'overlap_after_switch_theme' );

/* Walker Nav */
require_once( get_template_directory() .'/inc/class-walker-nav.php' );

/* Custom Functions */
require_once( get_template_directory() .'/inc/custom-functions.php' );

/* Theme Options */
require_once( get_template_directory() .'/admin/class-theme-options.php' );

/* Update portfolio slug */
function overlap_update_portfolio_slug(){
    return overlap_get_option('portfolio_slug');
}
add_filter('wyde_portfolio_slug', 'overlap_update_portfolio_slug');

/* Metaboxes */
require_once( get_template_directory() .'/inc/class-metabox.php' );

/* Shortcodes */
require_once( get_template_directory() .'/inc/class-shortcode.php' );

/* Widgets */
require_once( get_template_directory() .'/inc/class-widget.php' );

if( class_exists('WooCommerce') ){
    /* WooCommerce Template class */
    require_once( get_template_directory() .'/inc/class-woocommerce-template.php' );
}

/* TGM Plugin Activation */
require_once( get_template_directory() .'/inc/class-tgm-plugin-activation.php' );

/* Register the required plugins for this theme. */
function overlap_register_required_plugins() {
    //Bundled plugins.
    $plugins = array(
        array(
            'name'                  => 'Wyde Core', 
            'slug'                  => 'wyde-core', 
            'source'                => get_template_directory() .'/inc/plugins/wyde-core.zip',
            'required'              => true, 
            'version'               => '3.1.0', 
            'force_activation'      => false,
            'force_deactivation'    => false, 
            'external_url'          => '', 
        ),
        array(
            'name'                  => 'WPBakery Visual Composer', 
            'slug'                  => 'js_composer', 
            'source'                => get_template_directory() .'/inc/plugins/js_composer.zip',
            'required'              => false, 
            'version'               => '4.12', 
            'force_activation'      => false,
            'force_deactivation'    => false, 
            'external_url'          => '', 
        ),
        array(
            'name'                  => 'Slider Revolution', 
            'slug'                  => 'revslider', 
            'source'                => get_template_directory() .'/inc/plugins/revslider.zip',
            'required'              => false, 
            'version'               => '5.2.5.3',
            'force_activation'      => false, 
            'force_deactivation'    => false, 
            'external_url'          => '',
        ),
        array(
            'name'                  => 'Contact Form 7',
            'slug'                  => 'contact-form-7',
            'required'              => false,
            'version'               => '4.3.1',
            'force_activation'      => false,
            'force_deactivation'    => false
        )
    );
    
    // Configuration settings.
    $config = array(
        'id'           => 'overlap',               // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'install-bundled-plugins', // Menu slug.
        'parent_slug'  => 'themes.php',            // Parent menu slug.
        'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
               
        
    );

    tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'overlap_register_required_plugins' );