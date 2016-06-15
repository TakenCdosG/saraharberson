<?php
    
/** Wyde AJAX Importer **/
require_once( get_template_directory() . '/admin/class-ajax-importer.php' );

/** Theme Options **/
if (!class_exists('Overlap_Theme_Options')) {

    class Overlap_Theme_Options {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if ( !class_exists('ReduxFramework') ) {
                return;
            }

            if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                $this->initSettings();
                add_action( "redux/options/{$this->args['opt_name']}/saved", array($this, 'settings_saved'), 10, 2 );
                add_action('init', array($this, 'update_slug'), 10);
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

		    if ( is_admin() && isset($_GET['page']) && $_GET['page'] == 'theme-options' ) {
                add_action( 'admin_enqueue_scripts', array($this, 'load_scripts') );
            }

        }

        function load_scripts(){
            
            wp_enqueue_style('overlap-theme-options-style', get_template_directory_uri(). '/admin/css/theme-options.css', null, '1.0.1');
    
            wp_enqueue_script('overlap-ajax-importer-script', get_template_directory_uri(). '/admin/js/ajax-importer.js', null, '1.0.1', true);
            
            wp_localize_script('overlap-ajax-importer-script', 'overlap_ajax_importer_settings', 
            array(
                'import_url' => admin_url( 'admin-ajax.php' ),
                'data_dir' => get_template_directory_uri() . '/admin/data/', 
                'messages' => array( 
                        'loading' => esc_html__('Importing...', 'overlap'),
                        'settings' => esc_html__('Settings', 'overlap'),
                        'confirm_import_demo_content' => esc_html__('Are you sure you want to import demo content?', 'overlap'),
                        'confirm_import_settings' => esc_html__('Are you sure you want to import settings of this site?', 'overlap')
                    )
                )
            );

        }

        public function initSettings() {

            $this->theme = wp_get_theme();

            $this->setArguments();

            $this->setSections();

            if (!isset($this->args['opt_name'])) {
                return;
            }

            add_action( 'redux/loaded', array( $this, 'remove_demo' ) );

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        public function settings_saved($options, $changes){
            if( array_key_exists('portfolio_slug', $changes) ){
                echo '<script type="text/javascript">window.location.href=window.location.pathname+"?page=theme-options&slug-updated=true";</script>';
            }
        }

        public function update_slug(){
            global $pagenow;
            $slug_updated = isset( $_GET['slug-updated'] )? $_GET['slug-updated']:'';
            if( $slug_updated == 'true' ){
                flush_rewrite_rules();
                wp_redirect( admin_url( $pagenow.'?page=theme-options&settings-updated=true' ) );
            }
        }

        function remove_demo() {
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }
        
        public function setSections() {

            $allowed_html = array(
                'a' => array(
                    'href' => array(),
                    'title' => array(),
                    'target' => array()
                ),
                'br' => array(),
                'em' => array(),
                'strong' => array(),
            );;

            /***************************** 
            * Home
            ******************************/
            $import_fields = array(
                    array(
                        'id'        => 'section_import_content',
                        'type'      => 'section',
                        'title'     => esc_html__('Import Demo Content', 'overlap'),
                        'subtitle'  => esc_html__('Please make sure you have required plugins installed and activated to receive that portion of the content. This is recommended to do on fresh installs.', 'overlap'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'notice-warning',
                        'type'      => 'info',
                        'notice'    => true,
                        'style'     => 'warning',
                        'icon'      => 'el el-warning-sign',
                        'title'     => esc_html__('WARNING:', 'overlap'),
                        'desc'      => wp_kses( 
                            __('1. The Importing will append your current <strong>Pages, Posts and other content types</strong>, you should import them only once.
                                <br />2. You can use <a href="https://wordpress.org/plugins/wordpress-reset/" target="_blank">WordPress Reset</a> plugin to remove all existing data before importing.', 'overlap'), $allowed_html )
                    ),
                    array(
                        'id'        => 'raw_import_content',
                        'type'      => 'raw',
                        'content'   => 
                        '<div class="import-wrapper">'
                        .'  <h4>Select content</h4>'
                        .'  <div class="content-options">'                        
                        .'      <p><label for="task-posts"><input type="checkbox" id="task-posts" value="posts" checked="checked" class="noUpdate">'.esc_html__('Posts', 'overlap').'</label></p>'
                        .'      <p><label for="task-pages"><input type="checkbox" id="task-pages" value="pages" checked="checked" class="noUpdate">'.esc_html__('Pages', 'overlap').'</label></p>'
                        .'      <p><label for="task-contact-forms"><input type="checkbox" id="task-contact-forms" value="contact forms" checked="checked" class="noUpdate">'.esc_html__('Contact Forms', 'overlap').'</label></p>'
                        .'      <p><label for="task-portfolios"><input type="checkbox" id="task-portfolios" value="portfolios" checked="checked" class="noUpdate">'.esc_html__('Portfolios', 'overlap').'</label></p>'
                        .'      <p><label for="task-team-members"><input type="checkbox" id="task-team-members" value="team members" checked="checked" class="noUpdate">'.esc_html__('Team Members', 'overlap').'</label></p>'
                        .'      <p><label for="task-testimonials"><input type="checkbox" id="task-testimonials" value="testimonials" checked="checked" class="noUpdate">'.esc_html__('Testimonials', 'overlap').'</label></p>'                                             
                        .'      <p><label for="task-sliders"><input type="checkbox" id="task-sliders" value="sliders" checked="checked" class="noUpdate">'.esc_html__('Sliders', 'overlap').'</label></p>'
                        .'      <p><label for="task-menus"><input type="checkbox" id="task-menus" value="menus" checked="checked" class="noUpdate">'.esc_html__('Menus', 'overlap').'</label></p>'                        
                        .'      <p><label for="task-widgets"><input type="checkbox" id="task-widgets" value="widgets" checked="checked" class="noUpdate">'.esc_html__('Widgets', 'overlap').'</label></p>' 
                        .'  </div>' 
                        .'  <h4>Select demo type</h4>'                        
                        .'  <div class="import-buttons">'
                        .'      <select id="demo-type" class="noUpdate">'
                        .'          <option value="multi-pages">'. esc_html__('Multi Pages', 'overlap') .'</option>'
                        .'          <option value="one-page">'. esc_html__('One Page', 'overlap') .'</option>'
                        .'      </select>'
                        .'      <button id="btn-import" class="button button-primary noUpdate">'. esc_html__('Import Content', 'overlap') .'</button>'
                        .'  </div>'
                        .'</div>',
                    ),
                    array(
                        'id'        => 'section_import_settings',
                        'type'      => 'section',
                        'title'     => esc_html__('Update Site Settings', 'overlap'),
                        'subtitle'  => esc_html__('The Importing will replace your current settings in Theme Options, you can update Settings as many times as you\'d like to change the site settings to another demo.', 'overlap'),
                        'indent'    => false
                    ),
                    array(
                        'id'        => 'raw_import_settings',
                        'type'      => 'raw',
                        'content'   => 
                        '<div class="import-wrapper">'
                            .'<h4>Multi Pages</h4>'
                            .'<div class="demo-content-list">'
                            .'<a id="demo-content-1" href="#" class="demo-item"><img src="'. get_template_directory_uri() .'/admin/images/1.jpg" alt="'. esc_html__('Creative Agency', 'overlap') .'"/><strong>'. esc_html__('Creative Agency', 'overlap') .'</strong></a>'
                            .'<a id="demo-content-2" href="#" class="demo-item"><img src="'. get_template_directory_uri() .'/admin/images/2.jpg" alt="'. esc_html__('Creative Studio', 'overlap') .'"/><strong>'. esc_html__('Creative Studio', 'overlap') .'</strong></a>'
                            .'<a id="demo-content-3" href="#" class="demo-item"><img src="'. get_template_directory_uri() .'/admin/images/3.jpg" alt="'. esc_html__('Corporate', 'overlap') .'"/><strong>'. esc_html__('Corporate', 'overlap') .'</strong></a>'
                            .'<a id="demo-content-4" href="#" class="demo-item"><img src="'. get_template_directory_uri() .'/admin/images/4.jpg" alt="'. esc_html__('Personal Portfolio', 'overlap') .'"/><strong>'. esc_html__('Personal Portfolio', 'overlap') .'</strong></a>'
                            .'<a id="demo-content-5" href="#" class="demo-item"><img src="'. get_template_directory_uri() .'/admin/images/5.jpg" alt="'. esc_html__('Travel Blog', 'overlap') .'"/><strong>'. esc_html__('Travel Blog', 'overlap') .'</strong></a>'
                            .'</div>'
                            .'<h4>One Page</h4>'
                            .'<div class="demo-content-list">'
                            .'<a id="demo-content-6" href="#" class="demo-item"><img src="'. get_template_directory_uri() .'/admin/images/6.jpg" alt="'. esc_html__('Food Stylist', 'overlap') .'"/><strong>'. esc_html__('Food Stylist', 'overlap') .'</strong></a>'
                            .'</div>'                       
                        .'</div>',
                    ),
            );

            $imported = isset( $_GET['imported'] )? $_GET['imported']:'';
            
            if($imported == 'success' ){
                
                 array_unshift($import_fields, array(
                        'id'        => 'notice-success',
                        'type'      => 'info',
                        'notice'    => true,
                        'style'     => 'success',
                        'icon'      => 'el el-info-circle',
                        'title'     => esc_html__('Success!', 'overlap'),
                        'desc'      => esc_html__('The demo content has been successfully imported.', 'overlap')
                ));

            }else if($imported == 'error' ){
                
                array_unshift($import_fields, array(
                        'id'        => 'notice-fail',
                        'type'      => 'info',
                        'notice'    => true,
                        'style'     => 'critical',
                        'icon'      => 'el el-info-circle',
                        'title'     => esc_html__('ERROR!', 'overlap'),
                        'desc'      => esc_html__('An error occurred while importing demo data, please try again later.', 'overlap')
                ));

            }
            
            $this->sections['home'] = array(
                'title'     => esc_html__('Home', 'overlap'),
                'heading'   => false,
                'icon'      => 'el-icon-home',
                'fields'    => $import_fields
            );



            /***************************** 
            * General
            ******************************/
            $predefined_colors = array();
            for($i = 1; $i <= 10; $i ++){
                $predefined_colors[strval($i)] = array('alt' => '',  'img' => get_template_directory_uri() . '/images/colors/'.$i.'.png');
            }

            $this->sections['general'] = array(
                'icon'      => 'el el-adjust-alt',
                'title'     => esc_html__('General', 'overlap'),
                'heading'   => false,
                'fields'    => array(
                   array(
                        'id'        => 'predefined_color',
                        'type'      => 'image_select',
                        'title'     => esc_html__('Color Scheme', 'overlap'),
                        'subtitle'  => esc_html__('Select your website color scheme from predefined colors.', 'overlap'),
                        'options'   => $predefined_colors,
                        'default'   => '1'
                   ),
                   array(
                        'id'        => 'custom_color',
                        'type'      => 'switch',
                        'title'     => esc_html__('Custom Color Scheme', 'overlap'),
                        'subtitle'  => esc_html__('Use custom color from color picker.', 'overlap'),
                        'default'   => false
                   ),
                   array(
                        'id'        => 'color_scheme',
                        'type'      => 'color',
                        'title'     => esc_html__('Color Scheme', 'overlap'),
                        'subtitle'  => esc_html__('Choose your own color scheme.', 'overlap'),
                        'required'  => array('custom_color', '=', true),
                        'transparent'   => false,
                        'default'   => '#8accff'
                    ),
                    array(
                        'id'        => 'smooth_scroll',
                        'type'      => 'switch',
                        'title'     => esc_html__('Smooth Scrolling', 'overlap'),
                        'subtitle'  => esc_html__('Enable the smooth scrolling.', 'overlap'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'totop_button',
                        'type'      => 'switch',
                        'title'     => esc_html__('Back To Top Button', 'overlap'),
                        'subtitle'  => esc_html__('Enable a back to top button.', 'overlap'),
                        'default'   => true,
                    ),
                    array(
                        'id'        => 'preload_images',
                        'type'      => 'switch',
                        'title'     => esc_html__('Preload Images', 'overlap'),
                        'subtitle'  => esc_html__('Preloading images definitely helps users enjoy a better experience when viewing your content.', 'overlap'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'page_loader',
                        'type'      => 'image_select',
                        'title'     => esc_html__('Loader', 'overlap'),
                        'subtitle'  => esc_html__('Select a loader animation.', 'overlap'),
                        'options'   => array(
                            'none' => array('alt' => '', 'img' => get_template_directory_uri() . '/images/loaders/0.jpg'),
                            '1' => array('alt' => '', 'img' => get_template_directory_uri() . '/images/loaders/1.jpg'),
                            '2' => array('alt' => '',  'img' => get_template_directory_uri() . '/images/loaders/2.jpg'),
                            '3' => array('alt' => '', 'img' => get_template_directory_uri() . '/images/loaders/3.jpg'),
                        ),
                        'default'   => '1',
                    ),
                    array(
                        'id'        => 'page_loader_image',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => esc_html__('Loader Icon', 'overlap'),
                        'required'  => array('page_loader', '=', '3'),
                        'height'    => '45px',
                        'readonly'  => false,
                        'subtitle'  => esc_html__('Loader image to display at the center of the loader animation.', 'overlap'),
                        'desc'      => esc_html__('Maximum height: 70px.', 'overlap'),
                        'default'   => array(        
                                    'url'=> get_template_directory_uri() .'/images/favicon.png'
                        )
                    ),
                    array(
                        'id'        => 'mobile_animation',
                        'type'      => 'switch',
                        'title'     => esc_html__('Animation on Mobile', 'overlap'),
                        'subtitle'  => esc_html__('Enable animated elements on mobile devices.', 'overlap'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'mobile_zoom',
                        'type'      => 'switch',
                        'title'     => esc_html__('Page Zooming on Mobile', 'overlap'),
                        'subtitle'  => esc_html__('Allow users to zoom in and out on website.', 'overlap'),
                        'default'   => false,
                    ),
                 )
            );

            /***************************** 
            * Favicon
            ******************************/
            // If it is older than WordPress 4.3
            if ( ! ( function_exists( 'wp_site_icon' ) ) ) {

                $this->sections['favicon'] = array(
                    'icon'      => 'el-icon-star',
                    'title'     => esc_html__('Favicon', 'overlap'),
                    'heading'   => false,
                    'fields'    => array(
                        array(
                            'id'        => 'section_favicon',
                            'type'      => 'section',
                            'title'     => esc_html__('Favicon', 'overlap'),
                            'subtitle'  => esc_html__('Customize a favicon for your site.', 'overlap'),
                            'indent'    => true
                        ),
                        array(
                            'id'        => 'favicon_image',
                            'type'      => 'media',
                            'url'       => true,
                            'title'     => esc_html__('Favicon Image (.PNG)', 'overlap'),
                            'readonly'  => false,
                            'subtitle'  => esc_html__('Upload a favicon image for your site, or you can specify an image URL directly.', 'overlap'),
                            'desc'      => esc_html__('Icon dimension:', 'overlap').' 16px * 16px or 32px * 32px',
                            'default'   => array(        
                                                'url'=> get_template_directory_uri() .'/images/favicon.png'
                            ),
                        ),
                        array(
                            'id'        => 'favicon',
                            'type'      => 'media',
                            'url'       => true,
                            'title'     => esc_html__('Favicon (.ICO)', 'overlap'),
                            'readonly'  => false,
                            'subtitle'  => esc_html__('Upload a favicon for your site, or you can specify an icon URL directly.', 'overlap'),
                            'desc'      => esc_html__('Icon dimension:', 'overlap').' 16px * 16px',

                        ),
                        array(
                            'id'        => 'favicon_iphone',
                            'type'      => 'media',
                            'url'       => true,
                            'title'     => esc_html__('Apple iPhone Icon', 'overlap'),
                            'height'    => '57px',
                            'readonly'  => false,
                            'subtitle'  => esc_html__('Favicon for Apple iPhone.', 'overlap'),
                            'desc'      => esc_html__('Icon dimension:', 'overlap').' 57px * 57px',
                        ),
                        array(
                            'id'        => 'favicon_iphone_retina',
                            'type'      => 'media',
                            'url'       => true,
                            'title'     => esc_html__('Apple iPhone Icon (Retina Version)', 'overlap'),
                            'height'    => '57px',
                            'readonly'  => false,
                            'subtitle'  => esc_html__('Favicon for Apple iPhone Retina Version.', 'overlap'),
                            'desc'      => esc_html__('Icon dimension:', 'overlap').' 114px  * 114px',
                        ),
                        array(
                            'id'        => 'favicon_ipad',
                            'type'      => 'media',
                            'url'       => true,
                            'title'     => esc_html__('Apple iPad Icon', 'overlap'),
                            'height'    => '72px',
                            'readonly'  => false,
                            'subtitle'  => esc_html__('Favicon for Apple iPad.', 'overlap'),
                            'desc'      => esc_html__('Icon dimension:', 'overlap').' 72px * 72px',
                        ),
                        array(
                            'id'        => 'favicon_ipad_retina',
                            'type'      => 'media',
                            'url'       => true,
                            'title'     => esc_html__('Apple iPad Icon (Retina Version)', 'overlap'),
                            'height'    => '57px',
                            'readonly'  => false,
                            'subtitle'  => esc_html__('Favicon for Apple iPad Retina Version.', 'overlap'),
                            'desc'      => esc_html__('Icon dimension:', 'overlap').' 144px  * 144px',
                        )
                ));
            }

            /***************************** 
            * Navigation
            ******************************/
            $this->sections['nav'] = array(
                'icon'      => 'el-icon-lines',
                'title'     => esc_html__('Navigation', 'overlap'),
                'heading'   => false,
                'fields'    => array(
                    array(
                        'id'        => 'section_nav',
                        'type'      => 'section',
                        'title'     => esc_html__('Navigation', 'overlap'),
                        'subtitle'  => esc_html__('Customize the primary navigation.', 'overlap'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'nav_layout',
                        'type'      => 'select',
                        'title'     => esc_html__('Layout', 'overlap'),
                        'subtitle'  => esc_html__('Select a navigation layout.', 'overlap'),
                        'options'   => array(
                            'classic'   => esc_html__('Classic', 'overlap'),
                            'center'      => esc_html__('Center', 'overlap'),
                            'fullscreen'      => esc_html__('Full Screen', 'overlap'),
                        ),
                        'default'   => 'classic'
                    ),
                    array(
                        'id'        => 'menu_shop_cart',
                        'type'      => 'switch',
                        'title'     => esc_html__('Shopping Cart Icon', 'overlap'),
                        'subtitle'  => esc_html__('Turn on to display the shopping cart icon.', 'overlap'),
                        'default'   => false
                    ),
                    array(
                        'id'        => 'menu_search_icon',
                        'type'      => 'switch',
                        'title'     => esc_html__('Search Icon', 'overlap'),
                        'subtitle'  => esc_html__('Turn on to display the search icon.', 'overlap'),
                        'default'   => false
                    ),
                    array(
                        'id'        => 'slidingbar',
                        'type'      => 'switch',
                        'required'  => array('nav_layout', '!=', '3'),
                        'title'     => esc_html__('Sliding Bar', 'overlap'),
                        'subtitle'  => esc_html__('Turn on to display a sliding widget area.', 'overlap'),
                        'default'   => false
                    ),
                    array(
                        'id'        => 'menu_social_icon',
                        'type'      => 'switch',
                        'title'     => esc_html__('Social Icons', 'overlap'),
                        'subtitle'  => esc_html__('Turn on to display the social media icons in mobile navigation and slidingbar.', 'overlap'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'menu_contact',
                        'type'      => 'switch',
                        'title'     => esc_html__('Contact Info', 'overlap'),
                        'subtitle'  => esc_html__('Turn on to display the contact info in mobile navigation and slidingbar.', 'overlap'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'menu_contact_items',
                        'type'      => 'multi_text',
                        'required'  => array('menu_contact', '=', 1),
                        'title'     => esc_html__('Contact Info Items', 'overlap'),
                        'subtitle'  => esc_html__('The contact items to display in the left menu and sliding bar.', 'overlap'),
                        'add_text'  => esc_html__('Add New', 'overlap'),
                        'default'   => array(
                            '<i class="ol-phone"></i> +1 111-888-000',
                            '<i class="ol-mail"></i> email@domain.com',
                            '<i class="ol-location"></i> 1234, Your Address, 12345',
                        ),
                    ),
                    array(
                        'id'        => 'section_header',
                        'type'      => 'section',
                        'required'  => array('nav_layout', '!=', '3'),
                        'title'     => esc_html__('Top Navigation', 'overlap'),
                        'subtitle'  => esc_html__('Customize the page header and top menu.', 'overlap'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'header_sticky',
                        'type'      => 'switch',
                        'title'     => esc_html__('Sticky Header', 'overlap'),
                        'subtitle'  => esc_html__('Enable sticky header.', 'overlap'),
                        'default'   => true,
                    ),
                    array(
                        'id'        => 'header_fullwidth',
                        'type'      => 'switch',
                        'title'     => esc_html__('Full Width', 'overlap'),
                        'subtitle'  => esc_html__('Turn on to use full width header or off to use fixed header width.', 'overlap'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'header_style',
                        'type'      => 'select',
                        'title'     => esc_html__('Header Background', 'overlap'),
                        'subtitle'  => esc_html__('Select a header navigation background style.', 'overlap'),
                        'options'   => array(
                            'light' => esc_html__('Light', 'overlap'),
                            'dark'  => esc_html__('Dark', 'overlap'),
                        ),
                        'default'   => 'light'
                    ),
                    array(
                        'id'        => 'header_logo',
                        'type'      => 'media',
                        'title'     => esc_html__('Dark Logo', 'overlap'),
                        'height'    => '45px',
                        'subtitle'  => esc_html__('Dark color of logo for light header.', 'overlap'),
                        'desc'      => esc_html__('Recommended height: 70px or larger.', 'overlap'),
                        'default'   => array(        
                                    'url'=> get_template_directory_uri() .'/images/logo/logo-dark@2x.png'
                        )
                    ),
                    array(
                        'id'        => 'header_logo_sticky',
                        'type'      => 'media',
                        'title'     => esc_html__('Dark Sticky Logo', 'overlap'),
                        'height'    => '45px',
                        'subtitle'  => esc_html__('Dark color of sticky header logo for light header.', 'overlap'),
                        'desc'      => esc_html__('Recommended height: 50px or larger.', 'overlap'),
                        'default'   => array(        
                                    'url'=> get_template_directory_uri() .'/images/logo/logo-sticky@2x.png'
                        ),
                    ),
                    array(
                        'id'        => 'header_logo_light',
                        'type'      => 'media',
                        'title'     => esc_html__('Light Logo', 'overlap'),
                        'height'    => '45px',
                        'subtitle'  => esc_html__('Light color of logo for dark header.', 'overlap'),
                        'desc'      => esc_html__('Recommended height: 70px or larger.', 'overlap'),
                        'default'   => array(        
                                    'url'=> get_template_directory_uri() .'/images/logo/logo-light@2x.png'
                        ),
                    ),
                    array(
                        'id'        => 'header_logo_light_sticky',
                        'type'      => 'media',
                        'title'     => esc_html__('Light Sticky Logo', 'overlap'),
                        'height'    => '45px',
                        'subtitle'  => esc_html__('Light color of sticky logo for dark header.', 'overlap'),
                        'desc'      => esc_html__('Recommended height: 50px or larger.', 'overlap'),
                        'default'   => array(        
                                    'url'=> get_template_directory_uri() .'/images/logo/logo-sticky@2x.png'
                        ),
                    ),
                    array(
                        'id'        => 'section_sidenav',
                        'type'      => 'section',
                        'title'     => esc_html__('Mobile Navigation', 'overlap'),
                        'subtitle'  => esc_html__('Customize the mobile navigation.', 'overlap'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'side_nav_text_style',
                        'type'      => 'select',
                        'title'     => esc_html__('Text Style', 'overlap'),
                        'subtitle'  => esc_html__('Select navigation text style.', 'overlap'),
                        'options'   => array(
                            'dark'  => esc_html__('Dark', 'overlap'),
                            'light' => esc_html__('Light', 'overlap'),
                            'custom'=> esc_html__('Custom', 'overlap'),
                        ),
                        'default'   => 'light'
                    ),
                    array(
                        'id'        => 'side_nav_color',
                        'type'      => 'color',
                        'required'  => array('side_nav_text_style', '=', 'custom'),
                        'title'     => esc_html__('Text Color', 'overlap'),
                        'subtitle'  => esc_html__('Set navigation text color.', 'overlap'),
                        'transparent' => false,
                        'output'    => array('#side-nav'),
                        'default'   => '#fff',
                    ),
                    array(
                        'id'        => 'side_nav_background',
                        'type'      => 'background',
                        'title'     => esc_html__('Background', 'overlap'),
                        'subtitle'  => esc_html__('Set a side nav background.', 'overlap'),
                        'output'    => array('#side-nav'),
                        'background-repeat' => false,
                        'background-attachment' =>false,
                        'default'   => array(
                            'background-color'   => '#211F1E',
                            'background-size'   => 'cover',
                            'background-position'   => 'center bottom'
                        ),
                    ),
                    array(
                        'id'        => 'side_nav_overlay_color',
                        'type'      => 'color',
                        'title'     => esc_html__('Background Overlay Color', 'overlap'),
                        'subtitle'  => esc_html__('Select background overlay color.', 'overlap'),
                        'default'  => '',
                        'validate' => 'color',
                    ),
                    array(
                        'id'        => 'side_nav_overlay_opacity',
                        'type'      => 'select',
                        'title'     => esc_html__('Background Overlay Opacity', 'overlap'),
                        'subtitle'  => esc_html__('Select background overlay opacity.', 'overlap'),
                        'options'   => array(
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
                        'default'  => '0.8',
                    )
                 )
            );


            /***************************** 
            * Footer
            ******************************/
            $this->sections['footer'] = array(
                'icon'      => 'el-icon-th-large',
                'title'     => esc_html__('Footer', 'overlap'),
                'heading'   => false,
                'fields'    => array(
                    array(
                        'id'        => 'section_footer_content',
                        'type'      => 'section',
                        'title'     => esc_html__('Footer Content', 'overlap'),
                        'subtitle'  => esc_html__('Customize the footer content.', 'overlap'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'footer_content',
                        'type'      => 'switch',
                        'title'     => esc_html__('Footer Content', 'overlap'),
                        'subtitle'  => esc_html__('Display footer content area.', 'overlap'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'footer_page',
                        'type'      => 'select',
                        'required'  => array('footer_content', '=', 1),
                        'title'     => esc_html__('Page', 'overlap'),
                        'subtitle'  => esc_html__('Select a page to display in the footer content area.', 'overlap'),          
                        'data'      => 'pages',
                    ),
                    array(
                        'id'        => 'section_footer_bottom',
                        'type'      => 'section',
                        'title'     => esc_html__('Footer Bar', 'overlap'),
                        'subtitle'  => esc_html__('Customize the footer bar.', 'overlap'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'footer_bar',
                        'type'      => 'switch',
                        'title'     => esc_html__('Footer Bar', 'overlap'),
                        'subtitle'  => esc_html__('Display a footer bar at the bottom of the page.', 'overlap'),
                        'default'   => true,
                    ),
                    array(
                        'id'        => 'footer_bar_full',
                        'type'      => 'switch',
                        'required'  => array('footer_bar', '=', 1),
                        'title'     => esc_html__('Full Width', 'overlap'),
                        'subtitle'  => esc_html__('Display a footer bar as full width.', 'overlap'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'footer_bar_color',
                        'type'      => 'color',
                        'required'  => array('footer_bar', '=', 1),
                        'title'     => esc_html__('Text Color', 'overlap'),
                        'subtitle'  => esc_html__('Set a footer bar text color.', 'overlap'),
                        'transparent' => false,
                        'output'    => array('#footer-bottom'),
                        'default'   => '',
                    ),
                    array(
                        'id'        => 'footer_bar_background',
                        'type'      => 'background',
                        'required'  => array('footer_bar', '=', 1), 
                        'title'     => esc_html__('Background', 'overlap'),
                        'subtitle'  => esc_html__('Set a footer bar background.', 'overlap'),
                        'output'    => array('#footer-bottom'),
                        'background-repeat' => false,
                        'background-attachment' =>false,
                        'default'   => array(
                            'background-color'  => '#000',
                            'background-size'   => 'cover',
                            'background-position'   => 'center bottom'
                        ),
                    ),
                    array(
                        'id'        => 'footer_layout',
                        'type'      => 'image_select',
                        'required'  => array('footer_bar', '=', 1),
                        'title'     => esc_html__('Layout', 'overlap'),
                        'subtitle'  => esc_html__('Select footer bar layout.', 'overlap'),
                        'options'   => array(
                            '1' => array('alt' => 'Center', 'img' => get_template_directory_uri() . '/images/footers/1.jpg'),
                            '2' => array('alt' => 'Small', 'img' => get_template_directory_uri() . '/images/footers/2.jpg'),
                            '3' => array('alt' => 'Medium', 'img' => get_template_directory_uri() . '/images/footers/3.jpg'),
                        ),
                        'default'   => '2'
                    ),
                    array(
                        'id'        => 'footer_logo',
                        'type'      => 'switch',
                        'required'  => array('footer_bar', '=', 1),
                        'title'     => esc_html__('Footer Logo', 'overlap'),
                        'subtitle'  => esc_html__('Display footer logo.', 'overlap'),
                        'default'   => true,
                    ),
                    array(
                        'id'        => 'footer_logo_image',
                        'type'      => 'media',
                        'required'  => array('footer_logo', '=', 1),
                        'url'       => true,
                        'title'     => esc_html__('Footer Logo Image', 'overlap'),
                        'height'    => '45px',
                        'readonly'  => false,
                        'subtitle'  => esc_html__('Upload a footer logo image, or you can specify an image URL directly.', 'overlap'),
                        'default'   => array(        
                                            'url'=> get_template_directory_uri() .'/images/logo/logo-footer.png'
                        ),
                    ),
                    array(
                        'id'        => 'footer_logo_retina',
                        'type'      => 'media',
                        'required'  => array('footer_logo', '=', 1),
                        'url'       => true,
                        'title'     => esc_html__('Footer Logo (Retina Version)', 'overlap'),
                        'height'    => '90px',
                        'readonly'  => false,
                        'subtitle'  => esc_html__('Upload a retina logo image, or you can specify an image URL directly.', 'overlap'),
                        'desc'      => esc_html__('It should be exactly 2x the size of normal logo.', 'overlap'),
                        'default'   => array(        
                                            'url'=> get_template_directory_uri() .'/images/logo/logo-footer@2x.png'
                        ),
                    ),
                    array(
                        'id'        => 'footer_menu',
                        'type'      => 'switch',
                        'required'  => array('footer_bar', '=', 1),
                        'title'     => esc_html__('Footer Menu', 'overlap'),
                        'subtitle'  => esc_html__('Display footer menu.', 'overlap'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'footer_social',
                        'type'      => 'switch',
                        'required'  => array('footer_bar', '=', 1),
                        'title'     => esc_html__('Social Icons', 'overlap'),
                        'subtitle'  => esc_html__('Display social icons.', 'overlap'),
                        'default'   => true,
                    ),
                    array(
                        'id'        => 'footer_text',
                        'type'      => 'switch',
                        'required'  => array('footer_bar', '=', 1),
                        'title'     => esc_html__('Footer Text', 'overlap'),
                        'subtitle'  => esc_html__('Display footer text.', 'overlap'),
                        'default'   => true,
                    ),
                    array(
                        'id'        => 'footer_text_content',
                        'type'      => 'editor',
                        'required'  => array('footer_text', '=', 1),
                        'args'   => array(
                            'teeny'            => false,
                            'textarea_rows'    => 3
                        ),
                        'default'   => '&copy;'. date('Y') .' Overlap - Premium WordPress Theme. Powered by <a href="https://wordpress.org/" target="_blank">WordPress</a>.',
                    )
                )
            );

            
            /***************************** 
            * Title Area
            ******************************/
            $this->sections['title_area'] = array(
                'icon'      => 'el-icon-photo',
                'title'     => esc_html__('Title Area', 'overlap'),
                'heading'     => false,
                'fields'    => array(
                    array(
                        'id'        => 'section_page_title',
                        'type'      => 'section',
                        'title'     => esc_html__('Title Area', 'overlap'),
                        'subtitle'  => esc_html__('Default settings for title area.', 'overlap'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'title_size',
                        'type'      => 'select',
                        'title'     => esc_html__('Size', 'overlap'),
                        'subtitle'  => esc_html__('Select the title size.', 'overlap'),
                        'options'   => array(
                            's' => esc_html__('Small', 'overlap'),
                            'm' => esc_html__('Medium', 'overlap'),
                            'l'=> esc_html__('Large', 'overlap'),
                            'full'=> esc_html__('Full Screen', 'overlap')
                        ),
                        'default'   => 's',
                    ),
                    array(
                        'id'        => 'title_scroll_effect',
                        'type'      => 'select',
                        'title'     => esc_html__('Scrolling Effect', 'overlap'),
                        'subtitle'  => esc_html__('Select a scrolling animation for title text and subtitle.', 'overlap'),
                        'options'   => array(
                            'none' => esc_html__('None', 'overlap'), 
                            'split' => esc_html__('Split', 'overlap'),
                            'fadeOut' => esc_html__('Fade Out', 'overlap'), 
                            'fadeOutUp' => esc_html__('Fade Out Up', 'overlap'), 
                            'fadeOutDown' => esc_html__('Fade Out Down', 'overlap'), 
                            'zoomIn' => esc_html__('Zoom In', 'overlap'), 
                            'zoomInUp' => esc_html__('Zoom In Up', 'overlap'), 
                            'zoomInDown' => esc_html__('Zoom In Down', 'overlap'), 
                            'zoomOut' => esc_html__('Zoom Out', 'overlap'), 
                            'zoomOutUp' => esc_html__('Zoom Out Up', 'overlap'), 
                            'zoomOutDown' => esc_html__('Zoom Out Down', 'overlap'), 
                        ),
                        'default' => 'fadeOut',
                    ),
                    array(
                        'id'       => 'title_color',
                        'type'     => 'color',
                        'title'    => esc_html__('Text Color', 'overlap'), 
                        'subtitle' => esc_html__( 'Select the title text color.', 'overlap' ),
                        'transparent'   => false,
                        'default'  => '',
                        'validate' => 'color',
                    ),
                    array(
                        'id'        => 'title_align',
                        'type'      => 'select',
                        'title'     => esc_html__('Alignment', 'overlap'),
                        'subtitle'  => esc_html__('Select the title alignment.', 'overlap'),
                        'options'   => array(
                            'none'  => esc_html__('Not Set', 'overlap'),
                            'left'  => esc_html__('Left', 'overlap'),
                            'center' => esc_html__('Center', 'overlap'),
                            'right' => esc_html__('Right', 'overlap')
                        ),
                        'default'   => 'none',
                    ),
                    array(
                        'id'        => 'title_background_mode',
                        'type'      => 'select',
                        'title'     => esc_html__('Background', 'overlap'),
                        'subtitle'  => esc_html__('Select background type.', 'overlap'),
                        'options'   => array(
                            'none' => esc_html__('None', 'overlap'),
                            'color' => esc_html__('Color', 'overlap'),
                            'image' => esc_html__('Image', 'overlap'),
                            'video'=> esc_html__('Video', 'overlap')
                        ),
                        'default'   => 'color',
                    ),
                    array(
                        'id'        => 'title_background_image',
                        'type'      => 'background',
                        'required'  =>  array(
                                            array('title_background_mode', '!=', 'none'),
                                            array('title_background_mode', '!=', 'color')
                                    ),
                        'title'     => esc_html__('Background Image', 'overlap'),
                        'background-color' => false,
                        'background-attachment' => false,
                        'background-repeat' => false,
                        'background-position' => false,
                        'subtitle'  => esc_html__('Customize background image.', 'overlap'),
                        'default'   => array(
                            'background-size' => 'cover',
                        )
                    ),
                    array(
                        'id'        => 'title_background_video',
                        'type'      => 'media',
                        'required'  => array('title_background_mode', '=', 'video'),
                        'title'     => esc_html__('Background Video', 'overlap'),
                        'subtitle'  => esc_html__('Select an MP4 video to display as title background.', 'overlap'),
                        'url'       => true,
                        'mode'      => false,
                        'readonly'  => false
                    ),
                    array(
                        'id'        => 'title_background_color',
                        'type'      => 'color',
                        'required'  => array('title_background_mode', '!=', 'none'),
                        'title'     => esc_html__('Background Color', 'overlap'),
                        'subtitle'  => esc_html__('Select a background color.', 'overlap'),
                        'default'   => '',
                    ),
                    array(
                        'id'       => 'title_overlay_color',
                        'type'     => 'color',
                        'required'  =>  array(
                                            array('title_background_mode', '!=', 'none'),
                                            array('title_background_mode', '!=', 'color')
                                    ),
                        'title'    => esc_html__('Background Overlay Color', 'overlap'), 
                        'subtitle' => esc_html__( 'Select background overlay color.', 'overlap' ),
                        'default'  => '#211F1E',
                        'validate' => 'color',
                    ),
                    array(
                        'id'        => 'title_overlay_opacity',
                        'type'      => 'select',
                        'required'  =>  array(
                                            array('title_background_mode', '!=', 'none'),
                                            array('title_background_mode', '!=', 'color')
                                    ),
                        'title'     => esc_html__('Background Overlay Opacity', 'overlap'),
                        'subtitle'  => esc_html__('Select background overlay opacity.', 'overlap'),
                        'options'   => array(
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
                        'default' => '0.8',
                    ),
                    array(
                        'id'        => 'title_background_effect',
                        'type'      => 'select',
                        'required'  =>  array(
                                            array('title_background_mode', '!=', 'none'),
                                            array('title_background_mode', '!=', 'color')
                                    ),
                        'title'     => esc_html__('Background Effect', 'overlap'),
                        'subtitle'  => esc_html__('Select background scrolling effect.', 'overlap'),
                        'options'   => array(
                            'none' => esc_html__('None', 'overlap'),                             
                            'fadeOut' => esc_html__('Fade Out', 'overlap'), 
                            'gradient' => esc_html__('Gradient', 'overlap'),          
                            'parallax' => esc_html__('Parallax', 'overlap'),                   
                        ),
                        'default' => 'parallax',
                    ),

            ) );

            /***************************** 
            * Page
            ******************************/
            $this->sections['page'] = array(
                'icon'      => 'el-icon-website',
                'title'     => esc_html__('Page', 'overlap'),
                'heading'     => false,
                'fields'    => array(
                    array(
                        'id'        => 'section_page_options',
                        'type'      => 'section',
                        'title'     => esc_html__('Page Options', 'overlap'),
                        'subtitle'  => esc_html__('Choose default options for page.', 'overlap'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'onepage',
                        'type'      => 'switch',
                        'title'     => esc_html__('One Page Website', 'overlap'),
                        'subtitle'  => esc_html__('Create One Page website, your frontpage will retrieve page content from primary menu automatically.', 'overlap'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'page_comments',
                        'type'      => 'switch',
                        'title'     => esc_html__('Comments', 'overlap'),
                        'subtitle'  => esc_html__('Allow comments on Regular WordPress pages (Boxed Layout).', 'overlap'),
                        'default'   => true,
                    )

                )
            );


            /***************************** 
            * Blog
            ******************************/
            $this->sections['blog'] = array(
                'icon'      => 'el-icon-edit',
                'title'     => esc_html__('Blog', 'overlap'),
                'heading'   => false,
                'fields'    => array(
                    array(
                        'id'        => 'section_blog',
                        'type'      => 'section',
                        'title'     => esc_html__('Blog', 'overlap'),
                        'subtitle'  => esc_html__('Customize Posts page that displays your latest posts.', 'overlap'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'blog_page_layout',
                        'type'      => 'select',
                        'title'     => esc_html__('Page Layout', 'overlap'),
                        'subtitle'  => esc_html__('Select a page layout, choose \'Boxed\' to create a Regular WordPress page, Wide for creating a Full Width page.', 'overlap'),
                        'options'   => array(                            
                            'boxed' => esc_html__('Boxed', 'overlap'),
                            'wide' => esc_html__('Wide', 'overlap'),
                        ),
                        'default'   => 'boxed'

                    ),
                    array(
                        'id'        => 'blog_sidebar',
                        'type'      => 'image_select',
                        'title'     => esc_html__('Sidebar', 'overlap'),
                        'subtitle'  => esc_html__('Select sidebar position.', 'overlap'),
                        'options'   => array(
                            '1' => array('alt' => 'No Sidebar', 'img' => get_template_directory_uri() . '/images/columns/1.png'),
                            '2' => array('alt' => 'One Left', 'img' => get_template_directory_uri() . '/images/columns/2.png'),
                            '3' => array('alt' => 'One Right', 'img' => get_template_directory_uri() . '/images/columns/3.png'),
                        ),
                        'default'   => '3'
                    ),
                    array(
                        'id'        => 'blog_sidebar_style',
                        'type'      => 'select',
                        'required'  => array('blog_sidebar', '!=', '1'),
                        'title'     => esc_html__('Sidebar Style', 'overlap'),
                        'subtitle'  => esc_html__('Select a sidebar background style.', 'overlap'),
                        'options'   => array(                            
                            'dark' => esc_html__('Dark', 'overlap'),
                            'light' => esc_html__('Light', 'overlap'),
                        ),
                        'default'   => 'dark'

                    ),
                    array(
                        'id'        => 'blog_layout',
                        'type'      => 'image_select',
                        'title'     => esc_html__('Layout', 'overlap'),
                        'subtitle'  => esc_html__('Select blog posts view.', 'overlap'),
                        'options'   => array(
                            '' => array('alt' => 'Default', 'img' => get_template_directory_uri() . '/images/blog/standard.jpg'),
                            'masonry' => array('alt' => 'Masonry', 'img' => get_template_directory_uri() . '/images/blog/masonry.jpg'),
                            'overlap' => array('alt' => 'Overlap',  'img' => get_template_directory_uri() . '/images/blog/overlap.jpg'),
                        ),
                        'default'   => ''
                    ),
                    array(
                        'id'        => 'blog_excerpt',
                        'type'      => 'select',
                        'title'     => esc_html__('Excerpt', 'overlap'),
                        'subtitle'  => esc_html__('Choose to display an excerpt or full content on blog posts view.', 'overlap'),
                        'options'   => array(                            
                            0 => esc_html__('Full Content', 'overlap'),
                            1 => esc_html__('Excerpt', 'overlap'),
                        ),
                        'default'   => 0

                    ),
                    array(
                        'id'        => 'blog_excerpt_base',
                        'type'      => 'select',
                        'required'  => array('blog_excerpt', '=', '1'),
                        'title'     => esc_html__('Limit By', 'overlap'),
                        'subtitle'  => esc_html__('Limit the post excerpt length by using number of words or characters.', 'overlap'),
                        'options'   => array(                            
                            0 => esc_html__('Words', 'overlap'),
                            1 => esc_html__('Characters', 'overlap'),
                        ),
                        'default'   => 0

                    ),    
                    array(
                        'id'        => 'blog_excerpt_length',
                        'required'  => array('blog_excerpt', '=', '1'),
                        'type'      => 'text',
                        'title'     => esc_html__('Excerpt Length', 'overlap'),
                        'subtitle'  => esc_html__('Enter the limit of post excerpt length.', 'overlap'),
                        'default'   => '55'
                    ),
                    array(
                        'id'        => 'blog_excerpt_more',
                        'type'      => 'select',
                        'required'  => array('blog_excerpt', '=', '1'),
                        'title'     => esc_html__('Read More', 'overlap'),
                        'subtitle'  => esc_html__('Select read more style to display after the excerpt.', 'overlap'),
                        'options'   => array(                            
                            0 => esc_html__('[...]', 'overlap'),
                            1 => esc_html__('Link to Full Post', 'overlap'),
                        ),
                        'default'   => 0

                    ),
                    array(
                        'id'        => 'blog_pagination',
                        'type'      => 'select',
                        'title'     => esc_html__('Pagination Type', 'overlap'),
                        'subtitle'  => esc_html__('Select the pagination type for blog page.', 'overlap'),
                        'options'   => array(
                            '1' => esc_html__('Numeric Pagination', 'overlap'),
                            '2' => esc_html__('Infinite Scroll', 'overlap'),
                            '3' => esc_html__('Next and Previous', 'overlap'),
                        ),
                        'default'   => '3'
                    ),
                    array(
                        'id'        => 'blog_placeholder_image',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => esc_html__('Placeholder Image', 'overlap'),
                        'height'     => '540px',
                        'readonly'  => false,
                        'subtitle'  => esc_html__('Select a cover image placeholder.', 'overlap'),
                        'desc'      => esc_html__('Recommended size: 960x540 px or larger.', 'overlap'),
                        'default'  => array(        
                                'url'=> get_template_directory_uri() .'/images/blog/placeholder.jpg',
                                'width' => '960',
                                'height' => '540px',
                        )
                    ),
                    array(
                        'id'        => 'section_blog_single',
                        'type'      => 'section',
                        'title'     => esc_html__('Blog Single Post', 'overlap'),
                        'subtitle'  => esc_html__('Customize blog single post.', 'overlap'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'blog_single_sidebar',
                        'type'      => 'image_select',
                        'title'     => esc_html__('Sidebar', 'overlap'),
                        'subtitle'  => esc_html__('Select sidebar position.', 'overlap'),
                        'options'   => array(
                            '1' => array('alt' => 'No Sidebar', 'img' => get_template_directory_uri() . '/images/columns/1.png'),
                            '2' => array('alt' => 'One Left', 'img' => get_template_directory_uri() . '/images/columns/2.png'),
                            '3' => array('alt' => 'One Right', 'img' => get_template_directory_uri() . '/images/columns/3.png'),
                        ),
                        'default'   => '1'
                    ),    
                    array(
                        'id'        => 'blog_single_image_size',
                        'type'      => 'select',
                        'title'     => esc_html__('Featured Image Size', 'overlap'),
                        'subtitle'  => esc_html__('Select blog single post featured image size.', 'overlap'),
                        'options'   => array(
                            'hide' => esc_html__('Hide Featured Image', 'overlap'),
                            'overlap-land-large' => esc_html__('Large (960x540)', 'overlap'),
                            'overlap-fullwidth' => esc_html__('Full Width', 'overlap'),
                            'full' => esc_html__('Original', 'overlap'),
                        ),
                        'default'   => 'full'
                    ),    
                    array(
                        'id'        => 'blog_single_lightbox_size',
                        'type'      => 'select',
                        'title'     => esc_html__('Lightbox Image Size', 'overlap'),
                        'subtitle'  => esc_html__('Select lightbox image size.', 'overlap'),
                        'options'   => array(
                            'overlap-land-large' => esc_html__('Large (960x540)', 'overlap'),
                            'overlap-fullwidth' => esc_html__('Full Width', 'overlap'),
                            'full' => esc_html__('Original', 'overlap'),
                        ),
                        'default'   => 'full'
                    ),
                    array(
                        'id'        => 'blog_single_tags',
                        'type'      => 'switch',
                        'title'     => esc_html__('Post Tags', 'overlap'),
                        'subtitle'  => esc_html__('Display post tags.', 'overlap'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'blog_single_author',
                        'type'      => 'switch',
                        'title'     => esc_html__('Author Box', 'overlap'),
                        'subtitle'  => esc_html__('Display author description box.', 'overlap'),
                        'default'   => false
                    ),
                    array(
                        'id'        => 'blog_single_nav',
                        'type'      => 'switch',
                        'title'     => esc_html__('Post Navigation', 'overlap'),
                        'subtitle'  => esc_html__('Display next and previous posts.', 'overlap'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'blog_home',
                        'type'      => 'switch',
                        'required'  => array('blog_single_nav', '=', 1),
                        'title'     => esc_html__('Home Button', 'overlap'),
                        'subtitle'  => esc_html__('Display a "Home" button on blog single post.', 'overlap'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'blog_home_page',
                        'type'      => 'select',
                        'required'  => array('blog_home', '=', 1),
                        'title'     => esc_html__('Blog Home Page', 'overlap'),
                        'subtitle'  => esc_html__('Select a blog home page.', 'overlap'),
                        'options'   => array(
                            'default' => esc_html__('Default - Assigned Posts Page', 'overlap'),
                            'custom' => esc_html__('Custom', 'overlap'),                           
                        ),
                        'default'   => 'default'
                    ),
                    array(
                        'id'        => 'blog_home_url',
                        'type'      => 'text',
                        'required'  => array('blog_home_page', '=', 'custom'),
                        'title'     => esc_html__('Blog Home Page URL', 'overlap'),
                        'subtitle'  => esc_html__('Home page URL for the "Home" button on blog single post.', 'overlap'),
                        'default'   =>  esc_url( home_url() ). '/blog',
                    ),
                    array(
                        'id'        => 'blog_single_comment',
                        'type'      => 'switch',
                        'title'     => esc_html__('Comments', 'overlap'),
                        'subtitle'  => esc_html__('Display comments box.', 'overlap'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'blog_single_related',
                        'type'      => 'switch',
                        'title'     => esc_html__('Related Posts', 'overlap'),
                        'subtitle'  => esc_html__('Display related posts.', 'overlap'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'blog_single_related_title',
                        'type'      => 'text',
                        'required'  => array('blog_single_related', '=', 1),
                        'title'     => esc_html__('Related Posts Title', 'overlap'),
                        'subtitle'  => esc_html__('The title of related posts box.', 'overlap'),
                        'default'   => 'Related Posts'
                    ),
                    array(
                        'id'        => 'blog_single_related_posts',
                        'type'      => 'select',
                        'required'  => array('blog_single_related', '=', 1),
                        'title'     => esc_html__('Number of related posts', 'overlap'),
                        'subtitle'  => esc_html__('Select the number of posts to show in related posts.', 'overlap'),
                        'options'   => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                            '7' => '7',
                            '8' => '8',
                            '9' => '9',
                            '10' => '10',
                        ),
                        'default'   => '5'

                    ),
                    array(
                        'id'        => 'section_blog_meta',
                        'type'      => 'section',
                        'title'     => esc_html__('Blog Meta', 'overlap'),
                        'subtitle'  => esc_html__('Customize blog meta data options.', 'overlap'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'blog_meta_date',
                        'type'      => 'switch',
                        'title'     => esc_html__('Post Date', 'overlap'),
                        'subtitle'  => esc_html__('Display blog post date.', 'overlap'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'blog_meta_author',
                        'type'      => 'switch',
                        'title'     => esc_html__('Author Name', 'overlap'),
                        'subtitle'  => esc_html__('Display blog author meta data.', 'overlap'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'blog_meta_category',
                        'type'      => 'switch',
                        'title'     => esc_html__('Category', 'overlap'),
                        'subtitle'  => esc_html__('Display blog meta category.', 'overlap'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'blog_meta_comment',
                        'type'      => 'switch',
                        'title'     => esc_html__('Comment', 'overlap'),
                        'subtitle'  => esc_html__('Display blog meta comment.', 'overlap'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'blog_meta_share',
                        'type'      => 'switch',
                        'title'     => esc_html__('Social Sharing Icons', 'overlap'),
                        'subtitle'  => esc_html__('Display blog social sharing icons.', 'overlap'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'section_blog_archive',
                        'type'      => 'section',
                        'title'     => esc_html__('Blog Archive/Category', 'overlap'),
                        'subtitle'  => esc_html__('Customize blog archive/category page and author page.', 'overlap'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'blog_archive_page_title',
                        'type'      => 'switch',
                        'title'     => esc_html__('Title Area', 'overlap'),
                        'subtitle'  => esc_html__('Turn on to show the page title area on archive page.', 'overlap'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'blog_archive_page_layout',
                        'type'      => 'select',
                        'title'     => esc_html__('Page Layout', 'overlap'),
                        'subtitle'  => esc_html__('Select a page layout, choose \'Boxed\' to create a Regular WordPress page, Wide for creating a Full Width page.', 'overlap'),
                        'options'   => array(                            
                            'boxed' => esc_html__('Boxed', 'overlap'),
                            'wide' => esc_html__('Wide', 'overlap'),
                        ),
                        'default'   => 'boxed'

                    ),
                    array(
                        'id'        => 'blog_archive_sidebar',
                        'type'      => 'image_select',
                        'title'     => esc_html__('Sidebar', 'overlap'),
                        'subtitle'  => esc_html__('Select sidebar position.', 'overlap'),
                        'options'   => array(
                            '1' => array('alt' => 'No Sidebar', 'img' => get_template_directory_uri() . '/images/columns/1.png'),
                            '2' => array('alt' => 'One Left', 'img' => get_template_directory_uri() . '/images/columns/2.png'),
                            '3' => array('alt' => 'One Right', 'img' => get_template_directory_uri() . '/images/columns/3.png'),
                        ),
                        'default'   => '3'
                    ),
                    array(
                        'id'        => 'blog_archive_sidebar_style',
                        'type'      => 'select',
                        'required'  => array('blog_archive_sidebar', '!=', '1'),
                        'title'     => esc_html__('Sidebar Style', 'overlap'),
                        'subtitle'  => esc_html__('Select a sidebar background style.', 'overlap'),
                        'options'   => array(                            
                            'dark' => esc_html__('Dark', 'overlap'),
                            'light' => esc_html__('Light', 'overlap'),
                        ),
                        'default'   => 'dark'

                    ),
                    array(
                        'id'        => 'blog_archive_background',
                        'type'      => 'background',
                        'title'     => esc_html__('Page Background', 'overlap'),
                        'subtitle'  => esc_html__('Set blog archive page background.', 'overlap'),
                        'output'    => array('.archive.category .main-content, .archive.author .main-content, .archive.date .main-content'),
                        'background-repeat' => false,
                        'background-attachment' =>false,
                        'default'   => array(
                            'background-size'   => 'cover',
                            'background-position'   => 'center center'
                        ),
                    ),
                    array(
                        'id'        => 'blog_archive_layout',
                        'type'      => 'image_select',
                        'title'     => esc_html__('Layout', 'overlap'),
                        'subtitle'  => esc_html__('Select blog posts view.', 'overlap'),
                        'options'   => array(
                            '' => array('alt' => 'Default', 'img' => get_template_directory_uri() . '/images/blog/standard.jpg'),
                            'masonry' => array('alt' => 'Masonry', 'img' => get_template_directory_uri() . '/images/blog/masonry.jpg'),
                            'overlap' => array('alt' => 'Overlap',  'img' => get_template_directory_uri() . '/images/blog/overlap.jpg'),
                        ),
                        'default'   => ''
                    ),
                    array(
                        'id'        => 'blog_archive_pagination',
                        'type'      => 'select',
                        'title'     => esc_html__('Pagination Type', 'overlap'),
                        'subtitle'  => esc_html__('Select the pagination type for blog page.', 'overlap'),
                        'options'   => array(
                            '1' => esc_html__('Numeric Pagination', 'overlap'),
                            '2' => esc_html__('Infinite Scroll', 'overlap'),
                            '3' => esc_html__('Next and Previous', 'overlap'),
                        ),
                        'default'   => '1'
                    )


                )
            );

            /***************************** 
            * Portfolio
            ******************************/
            $this->sections['portfolio'] = array(
                'icon'      => 'el el-folder-open',
                'title'     => esc_html__('Portfolio', 'overlap'),
                'heading'   => false,
                'fields'    => array(
                    array(
                        'id'        => 'section_portfolio',
                        'type'      => 'section',
                        'title'     => esc_html__('Portfolio Options', 'overlap'),
                        'subtitle'  => esc_html__('Customize the portfolio options.', 'overlap'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'portfolio_placeholder_image',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => esc_html__('Placeholder Image', 'overlap'),
                        'readonly'  => false,
                        'subtitle'  => esc_html__('Select a cover image placeholder.', 'overlap'),
                        'desc'      => esc_html__('Recommended size: 640x640 px or larger.', 'overlap'),
                        'default'  => array(        
                                'url' => get_template_directory_uri() .'/images/portfolio/placeholder.jpg',
                                'width' => '640px',
                                'height' => '640px',
                        )
                    ),
                    array(
                        'id'        => 'section_portfolio_single',
                        'type'      => 'section',
                        'title'     => esc_html__('Portfolio Single Post', 'overlap'),
                        'subtitle'  => esc_html__('Customize the portfolio single post.', 'overlap'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'portfolio_slug',
                        'type'      => 'text',
                        'title'     => esc_html__('Portfolio Slug', 'overlap'),
                        'subtitle'  => esc_html__('Change/Rewrite the permalink when you use the permalink type as %postname%.', 'overlap'),
                        'default'   => 'portfolio-item'
                    ),
                    array(
                        'id'        => 'portfolio_lightbox_size',
                        'type'      => 'select',
                        'title'     => esc_html__('Lightbox Image Size', 'overlap'),
                        'subtitle'  => esc_html__('Select portfolio lightbox image size.', 'overlap'),
                        'options'   => array(
                            'overlap-land-large' => esc_html__('Large (960x540)', 'overlap'),
                            'overlap-fullwidth' => esc_html__('Full Width', 'overlap'),
                            'full' => esc_html__('Original', 'overlap'),
                        ),
                        'default'   => 'full'
                    ),
                    array(
                        'id'        => 'portfolio_date',
                        'type'      => 'switch',
                        'title'     => esc_html__('Publish Date', 'overlap'),
                        'subtitle'  => esc_html__('Display portfolio publish date.', 'overlap'),                        
                        'default'   => true
                    ),
                    array(
                        'id'        => 'portfolio_nav',
                        'type'      => 'switch',
                        'title'     => esc_html__('Post Navigation', 'overlap'),
                        'subtitle'  => esc_html__('Display next and previous posts.', 'overlap'),                        
                        'default'   => true
                    ),
                    array(
                        'id'        => 'portfolio_home',
                        'type'      => 'switch',
                        'required'  => array('portfolio_nav', '=', 1),
                        'title'     => esc_html__('Home Button', 'overlap'),
                        'subtitle'  => esc_html__('Display a "Home" button on portfolio single post.', 'overlap'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'portfolio_home_url',
                        'type'      => 'text',
                        'required'  => array('portfolio_home', '=', 1),
                        'title'     => esc_html__('Portfolio Home Page', 'overlap'),
                        'subtitle'  => esc_html__('Home page URL for the "Home" button on portfolio single post.', 'overlap'),
                        'default'   =>  esc_url( home_url() ). '/portfolio',
                    ),
                    array(
                        'id'        => 'portfolio_related',
                        'type'      => 'switch',
                        'title'     => esc_html__('Related Posts', 'overlap'),
                        'subtitle'  => esc_html__('Display related posts.', 'overlap'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'portfolio_related_title',
                        'type'      => 'text',
                        'required'  => array('portfolio_related', '=', 1),
                        'title'     => esc_html__('Related Posts Title', 'overlap'),
                        'subtitle'  => esc_html__('The title of related posts box.', 'overlap'),
                        'default'   => 'Related Projects'
                    ),
                    array(
                        'id'        => 'portfolio_related_posts',
                        'type'      => 'text',
                        'required'  => array('portfolio_related', '=', 1),
                        'title'     => esc_html__('Number of related posts', 'overlap'),
                        'subtitle'  => esc_html__('Select the number of posts to show in related posts.', 'overlap'),
                        'default'   => '6'

                    ),
                    array(
                        'id'        => 'section_portfolio_archive',
                        'type'      => 'section',
                        'title'     => esc_html__('Portfolio Archive', 'overlap'),
                        'subtitle'  => esc_html__('Customize the portfolio archive pages (Category, Skill and Tag).', 'overlap'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'portfolio_archive_page_title',
                        'type'      => 'switch',
                        'title'     => esc_html__('Title Area', 'overlap'),
                        'subtitle'  => esc_html__('Turn on to show the page title area on archive page.', 'overlap'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'portfolio_archive_page_layout',
                        'type'      => 'select',
                        'title'     => esc_html__('Page Layout', 'overlap'),
                        'subtitle'  => esc_html__('Select a page layout, choose \'Boxed\' to create a Regular WordPress page, Wide for creating a Full Width page.', 'overlap'),
                        'options'   => array(                            
                            'boxed' => esc_html__('Boxed', 'overlap'),
                            'wide' => esc_html__('Wide', 'overlap'),
                        ),
                        'default'   => 'boxed'

                    ),
                    array(
                        'id'        => 'portfolio_archive_background',
                        'type'      => 'background',
                        'title'     => esc_html__('Page Background', 'overlap'),
                        'subtitle'  => esc_html__('Set portfolio archive page background.', 'overlap'),
                        'output'    => array('.archive.tax-portfolio_category .main-content, .archive.tax-portfolio_skill .main-content, .archive.tax-portfolio_tag .main-content'),
                        'background-repeat' => false,
                        'background-attachment' =>false,
                        'default'   => array(
                        ),
                    ),
                    array(
                        'id'        => 'portfolio_archive_layout',
                        'type'      => 'select',
                        'title'     => esc_html__('Layout', 'overlap'),
                        'subtitle'  => esc_html__('Select a portfolio layout for archive pages.', 'overlap'),
                        'options'   => array(
                            'grid'  =>  esc_html__('Grid (Without Space)', 'overlap'), 
                            'grid-space' => esc_html__('Grid (With Space)', 'overlap'),
                            'photoset' => esc_html__('Photoset', 'overlap'),
                            'masonry'   => esc_html__('Masonry', 'overlap'),
                            'overlap' => esc_html__('Overlap', 'overlap'),
                        ),
                        'default'   => 'photoset'
                    ),
                    array(
                        'id'        => 'portfolio_archive_grid_columns',
                        'type'      => 'select',
                        'required'  => array(
                                        array('portfolio_archive_layout', '!=', 'masonry'),
                                        array('portfolio_archive_layout', '!=', 'overlap')
                                    ),
                        'title'     => esc_html__('Columns', 'overlap'),
                        'subtitle'  => esc_html__('Select the number of grid columns.', 'overlap'),
                        'options'   => array(
                            '2' => esc_html__('2 Columns', 'overlap'),
                            '3' => esc_html__('3 Columns', 'overlap'),
                            '4' => esc_html__('4 Columns', 'overlap')
                        ),
                        'default'   => '3'

                    ),
                    array(
                        'id'        => 'portfolio_archive_pagination',
                        'type'      => 'select',
                        'title'     => esc_html__('Pagination Type', 'overlap'),
                        'subtitle'  => esc_html__('Select the pagination type for portfolio archive pages.', 'overlap'),
                        'options'   => array(
                            '1' => esc_html__('Infinite Scroll', 'overlap'),
                            '2' => esc_html__('Show More Button', 'overlap'),
                            'hide' => esc_html__('Hide', 'overlap'),
                        ),
                        'default'   => '1'
                    )
                )
            );

            /***************************** 
            * WooCommerce
            ******************************/
            $this->sections['woocommerce'] = array(
                'icon'      => 'el-icon-shopping-cart',
                'title'     => esc_html__('WooCommerce', 'overlap'),
                'heading'   => false,
                'fields'    => array(
                    array(
                        'id'        => 'section_shop',
                        'type'      => 'section',
                        'title'     => esc_html__('Shop Page', 'overlap'),
                        'subtitle'  => esc_html__('Customize shop page.', 'overlap'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'shop_product_items',
                        'type'      => 'text',
                        'title'     => esc_html__('Number of Products per Page', 'overlap'),
                        'subtitle'  => esc_html__('Enter the number of products per page.', 'overlap'),
                        'validate'  => 'numeric',
                        'default'   => 12
                        
                    ),
                    array(
                        'id'        => 'shop_product_columns',
                        'type'      => 'select',
                        'title'     => esc_html__('Number of Columns', 'overlap'),
                        'subtitle'  => esc_html__('Select the number of columns.', 'overlap'),
                        'options'   => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                        ),
                        'default'   => '4'
                    ),                    
                    array(
                        'id'        => 'shop_pagination',
                        'type'      => 'select',
                        'title'     => esc_html__('Pagination Type', 'overlap'),
                        'subtitle'  => esc_html__('Select the pagination type for shop page.', 'overlap'),
                        'options'   => array(
                            '1' => esc_html__('Numeric Pagination', 'overlap'),
                            '2' => esc_html__('Infinite Scroll', 'overlap'),
                            '3' => esc_html__('Next and Previous', 'overlap'),
                        ),
                        'default'   => '1'
                    ),
                    array(
                        'id'        => 'section_shop_single',
                        'type'      => 'section',
                        'title'     => esc_html__('Single Product', 'overlap'),
                        'subtitle'  => esc_html__('Customize shop single product.', 'overlap'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'shop_single_sidebar',
                        'type'      => 'image_select',
                        'title'     => esc_html__('Shop Single Sidebar', 'overlap'),
                        'subtitle'  => esc_html__('Select shop single product sidebar position.', 'overlap'),
                        'options'   => array(
                            '1' => array('alt' => 'No Sidebar', 'img' => get_template_directory_uri() . '/images/columns/1.png'),
                            '2' => array('alt' => 'One Left', 'img' => get_template_directory_uri() . '/images/columns/2.png'),
                            '3' => array('alt' => 'One Right', 'img' => get_template_directory_uri() . '/images/columns/3.png'),
                        ),
                        'default'   => '1'
                    ),
                    array(
                        'id'        => 'related_product_items',
                        'type'      => 'select',
                        'title'     => esc_html__('Number of Related Products', 'overlap'),
                        'subtitle'  => esc_html__('Select the number of related products.', 'overlap'),
                        'options'   => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                            '7' => '7',
                            '8' => '8',
                            '9' => '9',
                            '10' => '10',
                        ),
                        'default'   => '4'
                    ),
                    array(
                        'id'        => 'related_product_columns',
                        'type'      => 'select',
                        'title'     => esc_html__('Number of Columns', 'overlap'),
                        'subtitle'  => esc_html__('Select the number of columns.', 'overlap'),
                        'options'   => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ),
                        'default'   => '4'
                    )
                  )
            );

            /***************************** 
            * Search
            ******************************/
            $this->sections['search'] = array(
                'icon'      => 'el-icon-search',
                'title'     => esc_html__('Search', 'overlap'),
                'heading'   => false,
                'fields'    => array(
                    array(
                        'id'        => 'search_page_layout',
                        'type'      => 'select',
                        'title'     => esc_html__('Page Layout', 'overlap'),
                        'subtitle'  => esc_html__('Select a page layout, choose \'Boxed\' to create a Regular WordPress page, Wide for creating a Full Width page.', 'overlap'),
                        'options'   => array(                            
                            'boxed' => esc_html__('Boxed', 'overlap'),
                            'wide' => esc_html__('Wide', 'overlap'),
                        ),
                        'default'   => 'boxed'

                    ),
                    array(
                        'id'        => 'search_sidebar',
                        'type'      => 'image_select',
                        'title'     => esc_html__('Sidebar', 'overlap'),
                        'subtitle'  => esc_html__('Select sidebar position.', 'overlap'),
                        'options'   => array(
                            '1' => array('alt' => 'No Sidebar', 'img' => get_template_directory_uri() . '/images/columns/1.png'),
                            '2' => array('alt' => 'One Left', 'img' => get_template_directory_uri() . '/images/columns/2.png'),
                            '3' => array('alt' => 'One Right', 'img' => get_template_directory_uri() . '/images/columns/3.png'),
                        ),
                        'default'   => '1'
                    ),
                    array(
                        'id'        => 'search_sidebar_style',
                        'type'      => 'select',
                        'required'  => array('search_sidebar', '!=', '1'),
                        'title'     => esc_html__('Sidebar Style', 'overlap'),
                        'subtitle'  => esc_html__('Select a sidebar background style.', 'overlap'),
                        'options'   => array(                            
                            'dark' => esc_html__('Dark', 'overlap'),
                            'light' => esc_html__('Light', 'overlap'),
                        ),
                        'default'   => 'dark'

                    ),
                    array(
                        'id'        => 'search_pagination',
                        'type'      => 'select',
                        'title'     => esc_html__('Pagination Type', 'overlap'),
                        'subtitle'  => esc_html__('Select the pagination type for blog page.', 'overlap'),
                        'options'   => array(
                            '1' => esc_html__('Numeric Pagination', 'overlap'),
                            '2' => esc_html__('Infinite Scroll', 'overlap'),
                            '3' => esc_html__('Next and Previous', 'overlap'),
                        ),
                        'default'   => '1'
                    ),
                    array(
                        'id'        => 'search_show_image',
                        'type'      => 'switch',
                        'title'     => esc_html__('Show Featured Image', 'overlap'),
                        'subtitle'  => esc_html__('Display featured images in search results.', 'overlap'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'search_show_date',
                        'type'      => 'switch',
                        'title'     => esc_html__('Show Post Date', 'overlap'),
                        'subtitle'  => esc_html__('Display post date in search results.', 'overlap'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'search_show_author',
                        'type'      => 'switch',
                        'title'     => esc_html__('Show Author', 'overlap'),
                        'subtitle'  => esc_html__('Display post author in search results.', 'overlap'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'ajax_search',
                        'type'      => 'switch',
                        'title'     => esc_html__('Ajax Search', 'overlap'),
                        'subtitle'  => esc_html__('Enable ajax auto suggest search.', 'overlap'),
                        'default'   => true
                    ),
                    array(
                        'id'        => 'section_ajax_search',
                        'type'      => 'section',
                        'required'  => array('ajax_search', '=', 1),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'search_post_types',
                        'type'      => 'checkbox',
                        'title'     => esc_html__('Post Types', 'overlap'),
                        'subtitle'  => esc_html__('Select post types for ajax auto suggest search.', 'overlap'),
                        'data'  => 'post_types',
                        'default'   => array(
                            'page' => true,
                            'post' => true,
                            'wyde_portfolio' => true,
                            'product'   => true
                        )
                    ),
                    array(
                        'id'        => 'search_suggestion_items',
                        'type'      => 'select',
                        'title'     => esc_html__('Number of Suggestion Items.', 'overlap'),
                        'subtitle'  => esc_html__('Select number of search suggestion items per post type.', 'overlap'),
                        'options'  => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                            '7' => '7',
                            '8' => '8',
                            '9' => '9',
                            '10' => '10',
                        ),
                        'default'   => '5'
                        
                    )
                  )
            );

            /***************************** 
            * AJAX Page Options
            ******************************/
            $this->sections['ajax_page'] = array(
                'icon'      => 'el el-hourglass',
                'title'     => esc_html__('AJAX Page', 'overlap'),
                'heading'     => false,
                'fields'    => array(
                    array(
                        'id'        => 'section_ajax_options',
                        'type'      => 'section',
                        'title'     => esc_html__('AJAX Options', 'overlap'),
                        'subtitle'  => esc_html__('Turn on or off the AJAX page features.', 'overlap'),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'ajax_page',
                        'type'      => 'switch',
                        'title'     => esc_html__('Ajax Page', 'overlap'),
                        'subtitle'  => esc_html__('Enable ajax page transitions.', 'overlap'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'ajax_page_transition',
                        'type'      => 'select',
                        'required'  => array('ajax_page', '=', 1),
                        'title'     => esc_html__('Transitions', 'overlap'),
                        'subtitle'  => esc_html__('Select a page transition effect.', 'overlap'),
                        'options'   => array(
                            'fade' => esc_html__('Fade', 'overlap'),
                            'slideToggle' => esc_html__('Slide Toggle', 'overlap'),
                            'slideLeft' => esc_html__('Slide to Left', 'overlap'),
                            'slideRight'=> esc_html__('Slide to Right', 'overlap'),
                            'slideUp'=> esc_html__('Slide Up', 'overlap'),
                            'slideDown'=> esc_html__('Slide Down', 'overlap'),
                        ),
                        'default'   => 'fade',
                    ),
                    array(
                        'id'        => 'ajax_page_exclude_urls',
                        'type'      => 'multi_text',
                        'required'  => array('ajax_page', '=', 1),
                        'title'     => esc_html__('Exclude URLs', 'overlap'),
                        'subtitle'  => esc_html__('Excludes the specific links from AJAX Page Loader. E.g. /shop/, /cart/, /checkout/, etc.', 'overlap'),
                        'add_text'  => esc_html__('Add New', 'overlap'),
                        'default'   => array(
                            '/shop/',
                            '/product/',
                            '/product-category/',
                            '/cart/',
                            '/checkout/',
                            '/my-account/',
                        ),
                    )
                )
            );

            /***************************** 
            * Social Media
            ******************************/           
            $social_fields = array();

            $social_icons = overlap_get_social_icons(); // get social icons from inc/custom-functions.php

            foreach($social_icons as $key => $value){
               $social_fields[] = array(
                        'id'        => 'social_'. overlap_string_to_underscore_name($value),
                        'type'      => 'text',
                        'title'     => $value,
               ); 
            }

            $this->sections['social'] = array(
                'icon'      => 'el-icon-group',
                'title'     => esc_html__('Social Media', 'overlap'),
                'fields'    => $social_fields
            );


            /***************************** 
            * Typography
            ******************************/
            $this->sections['typography'] = array(
                'icon'      => 'el-icon-font',
                'title'     => esc_html__('Typography', 'overlap'),
                'desc'     => esc_html__('Customize font options for your site.', 'overlap'),
                'fields'    => array(
                    array(
                        'id'            => 'font_body',
                        'type'          => 'typography',
                        'title'         => esc_html__('Body', 'overlap'),
                        'subtitle'      => esc_html__('Font options for main body text.', 'overlap'),
                        'google'        => true,    
                        'font-style'    => false, 
                        'line-height'   => false,
                        'all_styles'    => true,  
                        'letter-spacing'=> true,
                        'font-backup'   => true,
                        'units'         => 'px',
                        'output'        => array('body'),
                        'default'       => array(
                            'google'        => true,
                            'font-family'   => 'Lato',
                            'font-size'     => '15px',
                            'font-backup'   => "Arial, Helvetica, sans-serif"
                        ),
                        'preview' => array('text' => 'Body Text <br /> 1234567890 <br /> ABCDEFGHIJKLMNOPQRSTUVWXYZ <br /> abcdefghijklmnopqrstuvwxyz'),
                    ),                    
                    array(
                        'id'            => 'font_menu',
                        'type'          => 'typography',
                        'title'         => esc_html__('Primary Menu', 'overlap'),
                        'subtitle'      => esc_html__('Font options for primary navigation.', 'overlap'),
                        'google'        => true,    
                        'font-style'    => false, 
                        'color'         => false, 
                        'font-size'     => true, 
                        'text-align'    => false,
                        'all_styles'    => true,
                        'letter-spacing'=> true,
                        'font-backup'   => true,
                        'line-height'   => false,   
                        'units'         => 'px', 
                        'output'        => array('#top-nav .top-menu > li > a, #full-nav, .live-search-form input'),
                        'default'       => array(
                            'google'        => true,
                            'font-family'   => 'Montserrat',
                            'font-weight'     => '400',
                            'font-backup'   => "Arial, Helvetica, sans-serif"
                        ),
                        'preview' => array('text' => 'Main Menu <br /> 1234567890 <br /> ABCDEFGHIJKLMNOPQRSTUVWXYZ <br /> abcdefghijklmnopqrstuvwxyz'),
                    ),
                    array(
                        'id'            => 'font_buttons',
                        'type'          => 'typography',
                        'title'         => esc_html__('Buttons and Link Buttons', 'overlap'),
                        'subtitle'      => esc_html__('Font options for buttons and link buttons.', 'overlap'),
                        'google'        => true,    
                        'font-style'    => false, 
                        'color'         => false, 
                        'font-size'     => false, 
                        'text-align'    => false,
                        'all_styles'    => true,
                        'letter-spacing'=> true,
                        'font-backup'   => true,
                        'line-height'   => false,   
                        'units'         => 'px', 
                        'output'        => array('.w-button, .w-link-button, .w-ghost-button, a.button, button, input[type="submit"], input[type="button"], input[type="reset"]'),
                        'default'       => array(
                            'google'        => true,
                            'font-family'   => 'Montserrat',
                            'letter-spacing'    => '0.5px',
                            'font-backup'   => "Arial, Helvetica, sans-serif"
                        ),
                        'preview' => array('text' => 'Buttons <br /> 1234567890 <br /> ABCDEFGHIJKLMNOPQRSTUVWXYZ <br /> abcdefghijklmnopqrstuvwxyz'),
                    ),                     
                    array(
                        'id'            => 'font_h1',
                        'type'          => 'typography',
                        'title'         => esc_html__('H1', 'overlap'),
                        'subtitle'      => esc_html__('Font options for heading 1.', 'overlap'),
                        'google'        => true,    
                        'font-style'    => false, 
                        'line-height'   => false,   
                        'all_styles'    => true,
                        'letter-spacing'=> true,
                        'font-backup'   => true,
                        'units'         => 'px', 
                        'output'        => array('h1'),
                        'default'       => array(
                            'google'        => true,
                            'font-family'   => 'Playfair Display',
                            'font-size'     => '48px',
                            'font-weight'     => '700',
                            'font-backup'   => "Arial, Helvetica, sans-serif"
                        ),
                        'preview' => array('text' => 'Heading 1 <br /> 1234567890 <br /> ABCDEFGHIJKLMNOPQRSTUVWXYZ <br /> abcdefghijklmnopqrstuvwxyz'),
                    ),                     
                    array(
                        'id'            => 'font_h2',
                        'type'          => 'typography',
                        'title'         => esc_html__('H2', 'overlap'),
                        'subtitle'      => esc_html__('Font options for heading 2.', 'overlap'),
                        'google'        => true,    
                        'font-style'    => false, 
                        'line-height'   => false,   
                        'all_styles'    => true,
                        'letter-spacing'=> true,
                        'font-backup'   => true,
                        'units'         => 'px', 
                        'output'        => array('h2', '.w-masonry .item-0 .post-title'),
                        'default'       => array(
                            'google'        => true,
                            'font-family'   => 'Playfair Display',
                            'font-size'     => '28px',
                            'font-weight'     => '700',
                            'font-backup'   => "Arial, Helvetica, sans-serif"
                         ),
                        'preview' => array('text' => 'Heading 2 <br /> 1234567890 <br /> ABCDEFGHIJKLMNOPQRSTUVWXYZ <br /> abcdefghijklmnopqrstuvwxyz'),
                    ),                     
                    array(
                        'id'            => 'font_h3',
                        'type'          => 'typography',
                        'title'         => esc_html__('H3', 'overlap'),
                        'subtitle'      => esc_html__('Font options for heading 3.', 'overlap'),
                        'google'        => true,    
                        'font-style'    => false, 
                        'line-height'   => false,   
                        'all_styles'    => true,
                        'letter-spacing'=> true,
                        'font-backup'   => true,
                        'units'         => 'px', 
                        'output'        => array('h3'),
                        'default'       => array(
                            'google'        => true,
                            'font-family'   => 'Playfair Display',
                            'font-size'     => '22px',
                            'font-weight'     => '700',
                            'font-backup'   => "Arial, Helvetica, sans-serif"
                         ),
                        'preview' => array('text' => 'Heading 3 <br /> 1234567890 <br /> ABCDEFGHIJKLMNOPQRSTUVWXYZ <br /> abcdefghijklmnopqrstuvwxyz'),
                    ),                     
                    array(
                        'id'            => 'font_h4',
                        'type'          => 'typography',
                        'title'         => esc_html__('H4', 'overlap'),
                        'subtitle'      => esc_html__('Font options for heading 4.', 'overlap'),
                        'google'        => true,    
                        'font-style'    => true, 
                        'font-size'     => false, 
                        'line-height'   => false, 
                        'color'         => false, 
                        'text-align'    => false, 
                        'subsets'       => false, 
                        'all_styles'    => false,
                        'letter-spacing'=> false,
                        'font-backup'   => true,
                        'units'         => 'px', 
                        'output'        => array('h4'),
                        'default'       => array(
                            'google'        => true,
                            'font-family'   => 'Montserrat',
                            'font-weight'     => '700',
                            'font-backup'   => "Arial, Helvetica, sans-serif"
                         ),
                        'preview' => array('text' => 'Heading 4 <br /> 1234567890 <br /> ABCDEFGHIJKLMNOPQRSTUVWXYZ <br /> abcdefghijklmnopqrstuvwxyz'),
                    ),                     
                    array(
                        'id'            => 'font_h5',
                        'type'          => 'typography',
                        'title'         => esc_html__('H5', 'overlap'),
                        'subtitle'      => esc_html__('Font options for heading 5.', 'overlap'),
                        'google'        => true,    
                        'font-style'    => true, 
                        'font-size'     => false, 
                        'line-height'   => false, 
                        'color'         => false, 
                        'text-align'    => false, 
                        'subsets'       => false, 
                        'all_styles'    => false,
                        'letter-spacing'=> false,
                        'font-backup'   => true,
                        'units'         => 'px', 
                        'output'        => array('h5', '.post-date strong'),
                        'default'       => array(
                            'google'        => true,
                            'font-family'   => 'Montserrat',
                            'font-weight'   => '400',                           
                            'font-backup'   => "Arial, Helvetica, sans-serif"
                         ),
                        'preview' => array('text' => 'Heading 5 <br /> 1234567890 <br /> ABCDEFGHIJKLMNOPQRSTUVWXYZ <br /> abcdefghijklmnopqrstuvwxyz'),
                    ),                     
                    array(
                        'id'            => 'font_h6',
                        'type'          => 'typography',
                        'title'         => esc_html__('H6', 'overlap'),
                        'subtitle'      => esc_html__('Font options for heading 6 and blockquote.', 'overlap'),
                        'google'        => true,    
                        'font-style'    => true, 
                        'font-size'     => false, 
                        'line-height'   => false, 
                        'color'         => false, 
                        'text-align'    => false, 
                        'subsets'       => false, 
                        'all_styles'    => false,
                        'letter-spacing'=> false,
                        'font-backup'   => true,
                        'units'         => 'px', 
                        'output'        => array('h6', 'blockquote'),
                        'default'       => array(
                            'google'        => true,
                            'font-family'   => 'Playfair Display',
                            'font-weight'    => '400', 
                            'font-backup'   => "Arial, Helvetica, sans-serif"
                         ),
                        'preview' => array('text' => 'Heading 6 <br /> 1234567890 <br /> ABCDEFGHIJKLMNOPQRSTUVWXYZ <br /> abcdefghijklmnopqrstuvwxyz'),
                    ),                
                )
            );


            /***************************** 
            * Google API Options
            ******************************/
            $this->sections['google_api'] = array(
                'icon'      => 'el el-map-marker',
                'title'     => esc_html__('Google Maps', 'overlap'),
                'heading'     => false,
                'fields'    => array(
                    array(
                        'id'        => 'section_maps_section',
                        'type'      => 'section',
                        'title'     => esc_html__('Google Maps Options', 'overlap'),
                        'subtitle'  => wp_kses( __('This is now an optional but will be required in the future as you can see in <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">Google Maps Documentation</a> (All Google Maps JavaScript API applications require authentication).', 'overlap'), $allowed_html ),
                        'indent'    => true
                    ),
                    array(
                        'id'        => 'google_maps_api_key',
                        'type'      => 'text',
                        'title'     => esc_html__('Google Maps API Key', 'overlap'),
                        'subtitle'  => wp_kses( __('Enter your Google Maps API key, <a href="https://developers.google.com/maps/documentation/javascript/get-api-key#key" target="_blank">Get an API key</a>', 'overlap'), $allowed_html ),
                        'default'   => ''
                    )
                )
            );

            /***************************** 
            * Advanced
            ******************************/
            $this->sections['advanced'] = array(
                'icon'      => 'el-icon-wrench',
                'title'     => esc_html__('Advanced', 'overlap'),
                'heading'   => false,
                'fields'    => array(
                    array(
                        'id'        => 'head_script',
                        'type'      => 'ace_editor',
                        'title'     => esc_html__('Head Content', 'overlap'),
                        'subtitle'  => esc_html__('Enter HTML/JavaScript/StyleSheet. The content will be added into the head tag. You can add Google Verification code and custom Meta HTTP Content here.', 'overlap'),
                        'mode'  => 'html'
                        
                    ),
                    array(
                        'id'        => 'footer_script',
                        'type'      => 'ace_editor',
                        'title'     => esc_html__('Body Content', 'overlap'),
                        'subtitle'  => esc_html__('Enter HTML/JavaScript/StyleSheet. The content will be added into the footer of all pages. You can add Google Analytics code and custom JavaScript here.', 'overlap'),
                        'mode'  => 'html'
                        
                    ),
                  )
            );
                       
            /***************************** 
            * Import / Export
            ******************************/
            $this->sections['import_export'] = array(
                'title'     => esc_html__('Import / Export', 'overlap'),
                'heading' => false,
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => esc_html__('Import and Export', 'overlap'),
                        'subtitle'      => esc_html__('Import and Export your Theme options.', 'overlap'),
                        'full_width'    => false,
                    ),
                ),
            );                     

            /***************************** 
            * Theme Information
            ******************************/
            $this->theme    = wp_get_theme();
            $item_name      = $this->theme->get('Name');
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';

            $customize_title = esc_html__('Customize', 'overlap') . ' '. $this->theme->display('Name');

            ob_start();
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')): ?>
                        <a href="<?php echo esc_url( wp_customize_url() ); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php echo esc_attr($item_name);?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php echo esc_attr($item_name);?>" />
                <?php endif; ?>
                <h4><?php echo esc_html( $this->theme->display('Name') ); ?></h4>
                <div>
                    <p><?php echo esc_html__('By', 'overlap') . ' '. $this->theme->display('Author') ; ?></p>
                    <p><?php echo esc_html__('Version', 'overlap') . ' '. esc_html( $this->theme->display('Version') ); ?></p>
                    <p><?php echo '<strong>' . esc_html__('Tags', 'overlap') . ':</strong> '; ?><?php echo esc_html( $this->theme->display('Tags') ); ?></p>
                    <p class="theme-description"><?php echo esc_html( $this->theme->display('Description') ); ?></p>
            <?php
            if ($this->theme->parent()) {
               echo '<p class="howto">' . esc_html__('This child theme requires its parent theme', 'overlap') . ', '. esc_html( $this->theme->parent()->display('Name') ). '</p>';
            }
            ?>
                </div>
            </div>
            <?php
            $item_info = ob_get_clean();

            $this->sections[] = array(
                'type' => 'divide',
            );

            $this->sections['theme_info'] = array(
                'icon'      => 'el el-info-circle',
                'title'     => esc_html__('Theme Information', 'overlap'),
                'desc'      => '<p class="description">' . esc_html__('For more information and features about this theme, please visit', 'overlap') . ' <a href="'. esc_url( $this->theme->display('AuthorURI') ) .'" target="_blank">'. esc_url( $this->theme->display('AuthorURI') ) . '</a>.</p>',
                'fields'    => array(
                    array(
                        'id'        => 'raw-theme-info',
                        'type'      => 'raw',
                        'content'   => $item_info,
                    )
                ),
            );

        }

        /** Set framework arguments **/
        public function setArguments() {

           $this->args = array(
                'opt_name' => 'overlap_options',
                'display_name' => $this->theme->get('Name'),
                'display_version' =>  $this->theme->get('Version'),                
                'page_slug' => 'theme-options',
                'page_title' =>  esc_html__('Theme Options', 'overlap'),
                'menu_type' => 'menu',
                'menu_title' => esc_html__('Theme Options', 'overlap'),
                'page_parent'  => 'themes.php',
                'allow_sub_menu' => false,
                'customizer' => true,
                'update_notice' => false,
                'dev_mode'  => false,
                'admin_bar' => true,
                'admin_bar_icon'    => 'dashicons-admin-generic',
                'hints' => 
                        array(
                          'icon' => 'el-icon-question-sign',
                          'icon_position' => 'right',
                          'icon_size' => 'normal',
                          'tip_style' => 
                          array(
                            'color' => 'light',
                          ),
                          'tip_position' => 
                          array(
                            'my' => 'top left',
                            'at' => 'bottom right',
                          ),
                          'tip_effect' => 
                          array(
                            'show' => 
                            array(
                              'duration' => '500',
                              'event' => 'mouseover',
                            ),
                            'hide' => 
                            array(
                              'duration' => '500',
                              'event' => 'mouseleave unfocus',
                            ),
                          ),
                ),
                'output' => true,
                'compiler'  => true,
                'output_tag' => true,
                'menu_icon' => '',
                'page_icon' => 'icon-themes',
                'page_permissions' => 'manage_options',
                'save_defaults' => true,
                'show_import_export' => true,
                'transient_time' => 60 * MINUTE_IN_SECONDS,
                'network_sites' => true,
                'allow_tracking' => false,
                'google_api_key'   => '',//AIzaSyBss9ufj66aGyREW-VQdhuDSJ4opKsD-4U',//Replace with your Google API KEY
                'async_typography' => false,
                'intro_text' => '',
                'footer_text' => '',
                'footer_credit' => sprintf('%s Theme Options panel version %s.', $this->theme->get('Name'), $this->theme->get('Version'))
              );

            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'http://themeforest.net/user/Wyde',
                'title' => 'Help',
                'icon'  => 'el-icon-question-sign'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://themeforest.net/user/Wyde/follow',
                'title' => 'Follow us on ThemeForest',
                'icon'  => 'el-icon-heart'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://themeforest.net/downloads',
                'title' => 'Give me a rating on ThemeForest',
                'icon'  => 'el-icon-star'
            );
            

        }

    }    
    
    new Overlap_Theme_Options();
}