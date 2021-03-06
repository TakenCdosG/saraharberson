<?php

defined( 'ABSPATH' ) or die( 'You cannot access this script directly' );

if( !class_exists('Overlap_Ajax_Importer') ) {

    class Overlap_Ajax_Importer {

        function __construct(){
            add_action('wp_ajax_overlap_importer', array($this, 'data_importer'));
            add_action('wp_ajax_nopriv_overlap_importer', array($this, 'data_importer'));
        }

        function data_importer() {


            if ( class_exists('Wyde_Importer') && current_user_can( 'manage_options' ) && isset( $_GET['type'] ) && !empty($_GET['type']) ) {
               
                    $demo = isset( $_GET['demo'] )? $_GET['demo'] : '1';
                    $demo_type = isset( $_GET['demo_type'] )? $_GET['demo_type'] : 'multi-pages';
                    $type = isset( $_GET['type'] )? trim($_GET['type']) : 'settings';
                    $importer = new Wyde_Importer();
                   
                try{

                    switch( strtolower($type) ){                        
                        case 'posts':
                            $importer->import_demo_content( get_template_directory() . '/admin/data/shared/posts.xml'); 
                            break;
                        case 'pages':
                            $importer->import_demo_content( get_template_directory() . '/admin/data/'.$demo_type.'/pages.xml'); 
                            break;
                        case 'portfolios':
                            $importer->import_demo_content( get_template_directory() . '/admin/data/shared/portfolios.xml'); 
                            break;
                        case 'testimonials':
                            $importer->import_demo_content( get_template_directory() . '/admin/data/shared/testimonials.xml'); 
                            break;
                        case 'team-members':
                            $importer->import_demo_content( get_template_directory() . '/admin/data/shared/team-members.xml'); 
                            break;
                        case 'contact-forms':
                            $importer->import_demo_content( get_template_directory() . '/admin/data/'.$demo_type.'/contact-forms.xml'); 
                            break;
                        case 'widgets':
                            $importer->import_widgets( get_template_directory_uri() . '/admin/data/shared/widget_data.txt');
                            break;
                        case 'sliders':
                            $importer->import_revsliders( get_template_directory() . '/admin/data/shared/revsliders/');
                            break;
                        case 'menus':
                            $importer->import_demo_content( get_template_directory() . '/admin/data/'.$demo_type.'/menus.xml');  
                            break;    
                        case 'media-1':                           
                            $importer->import_demo_content( get_template_directory() . '/admin/data/'.$demo_type.'/media-1.xml');               
                            break;
                        case 'media-2':                           
                            $importer->import_demo_content( get_template_directory() . '/admin/data/'.$demo_type.'/media-2.xml');               
                            break;
                        case 'media-3':                           
                            $importer->import_demo_content( get_template_directory() . '/admin/data/'.$demo_type.'/media-3.xml');               
                            break;
                        case 'settings':                        
                            $this->default_settings($demo);
                            break;
                    }   

                    echo json_encode( array('code' => '1', 'key' => $type, 'message' => esc_html__('All done', 'overlap') ) );        

                } catch (Exception $e) {
                    echo json_encode( array('code' => '0', 'key' => $type, 'message' => esc_html__('There was an error.', 'overlap') .' '. $e ) );           
                }

            }else{
                echo json_encode( array('code' => '-1', 'message' => esc_html__('Cannot access to Administration Panel options.', 'overlap') ) );           
            }

            exit;
               
        }

        function default_settings( $demo = '1' ){
            

            try{

                $home_name = '';
                $primary_menu = 'Primary';

                switch ($demo) {
                    case '1':
                        $home_name = 'Home - Creative Agency';
                        break;
                    case '2':
                        $home_name = 'Home - Creative Studio';
                        break;   
                    case '3':
                        $home_name = 'Home - Corporate';
                        break;    
                    case '4':
                        $home_name = 'Home - Personal Portfolio';
                        break;    
                    case '5':
                        $home_name = 'Home - Travel Blog';
                        break;  
                    case '6':
                        $home_name = 'Home';
                        $primary_menu = 'Primary - One Page';
                        break; 
                }


                $this->set_menu_locations($primary_menu);               

                // Settings -> Reading 
	            $homepage = get_page_by_title( $home_name );

	            if(isset( $homepage ) && $homepage->ID) {
		            update_option('show_on_front', 'page');
		            update_option('page_on_front', $homepage->ID); // Front Page
	            }

                $posts_page = get_page_by_title( 'Blog' );
	            if(isset( $posts_page ) && $posts_page->ID) {
		            update_option('page_for_posts', $posts_page->ID); // Blog Page
	            }


                $this->set_woocommerce_pages();

                return true;

             } catch (Exception $e) {
                return false;           
            }
        }

        function set_menu_locations( $primary_menu = 'Primary'){
            // Set imported menus to registered theme locations
            $locations = get_theme_mod( 'nav_menu_locations' ); // get registered menu locations in theme
            $menus = wp_get_nav_menus(); // get registered menus

            if( $menus ) {
                foreach($menus as $menu) { // assign menus to theme locations
                    if( strpos($menu->name, $primary_menu)  !== false ) {
                        $locations['primary'] = $menu->term_id;
                    } else if( strpos($menu->name, 'Footer') !== false ) {
                        $locations['footer'] = $menu->term_id;
                    }
                }
            }

            set_theme_mod( 'nav_menu_locations', $locations ); // set menus to locations
        }

        function set_woocommerce_pages(){
            if( class_exists('WooCommerce') ) {
            
                // Set woocommerce pages
			    $woopages = array(
				    'woocommerce_shop_page_id' => 'Shop',
				    'woocommerce_cart_page_id' => 'Cart',
				    'woocommerce_checkout_page_id' => 'Checkout',
				    'woocommerce_pay_page_id' => 'Checkout &#8594; Pay',
				    'woocommerce_thanks_page_id' => 'Order Received',
				    'woocommerce_myaccount_page_id' => 'My Account',
				    'woocommerce_edit_address_page_id' => 'Edit My Address',
				    'woocommerce_view_order_page_id' => 'View Order',
				    'woocommerce_change_password_page_id' => 'Change Password',
				    'woocommerce_logout_page_id' => 'Logout',
				    'woocommerce_lost_password_page_id' => 'Lost Password'
			    );

			    foreach($woopages as $woo_page_name => $woo_page_title) {
				    $woopage = get_page_by_title( $woo_page_title );
				    if(isset( $woopage ) && $woopage->ID) {
					    update_option($woo_page_name, $woopage->ID);
				    }
			    }

                // No longer need to install woocommerce pages
                delete_option( '_wc_needs_pages' );
                delete_transient( '_wc_activation_redirect' );
            }
        }

    }

}

new Overlap_Ajax_Importer();