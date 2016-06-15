<?php
    
if( ! defined( 'ABSPATH' ) ) {
    die;
}

if( ! class_exists( 'Overlap_WooCommerce_Template' ) ) {

    class Overlap_WooCommerce_Template {

    	function __construct() {

            add_filter( 'woocommerce_show_page_title', array( $this, 'shop_title'), 10 );

            add_filter( 'woocommerce_breadcrumb_home_url', array( $this, 'shop_page_url') );
            
            remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
            add_action( 'woocommerce_before_main_content', array( $this, 'shop_breadcrumb'), 20 );

            add_action( 'woocommerce_before_cart', array( $this, 'shop_breadcrumb'), 1 );
            add_action( 'woocommerce_before_checkout_form', array( $this, 'shop_breadcrumb'), 1 );
            
            remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
    		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
    		add_action( 'woocommerce_before_main_content', array( $this, 'before_container' ), 10 );
    		add_action( 'woocommerce_after_main_content', array( $this, 'after_container' ), 10 );

            // Before shop loop    
            remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
            remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
            add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'before_shop_loop_item_title' ), 1 );
            add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
            add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 50 );

            // After shop loop
            remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
            remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
            remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
           
            

            remove_action( 'woocommerce_sidebar' , 'woocommerce_get_sidebar', 10 );
            add_action( 'woocommerce_sidebar', array($this, 'add_sidebar'), 10);

            remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
            add_action( 'woocommerce_after_single_product_summary', array($this, 'upsell_display'), 15 );

            remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
            //add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );
            
            add_filter( 'loop_shop_per_page', array($this, 'loop_shop_per_page'), 20 );

            add_filter( 'loop_shop_columns', array($this, 'loop_shop_columns') );

            add_filter( 'woocommerce_output_related_products_args', array($this, 'output_related_products_args') );

            add_filter( 'woocommerce_thankyou_order_received_text', array($this, 'order_received_text'));

            remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

            add_action( 'woocommerce_before_shop_loop_item_title', array($this, 'product_stock_status'), 10 );
        
        }        

        public function product_stock_status(){
            global $product;
            if ( !$product->is_in_stock() ) {
                echo '<div class="w-outofstock"><span>' . esc_html__( 'Out of stock', 'overlap' ) . '</span></div>';
            } 
        }

        // Change number or products per page
        public function loop_shop_per_page(){
            return intval( overlap_get_option('shop_product_items') );
        }

        // Change number or products per row
        public function loop_shop_columns() {
            return intval( overlap_get_option('shop_product_columns') );
        }
        
        // Related Products
        public function output_related_products_args( $args ) {
            $args['posts_per_page'] = intval( overlap_get_option('related_product_items') ); 
            $args['columns'] =  intval( overlap_get_option('related_product_columns') ); 
            return $args;
        }  

        function shop_title() {
			return false;
		}

        function shop_page_url(){
            $shop_id = woocommerce_get_page_id( 'shop' );
            if( $shop_id == -1 ){
                if( get_option('show_on_front')  == 'page' ){
                    $shop_id = get_option('page_on_front');
                }
            } 
            return get_permalink( $shop_id );
        }

        function shop_breadcrumb( $args = array() ){
            if(is_shop() && !is_product_category()) return;

            $args = wp_parse_args( $args, apply_filters( 'woocommerce_breadcrumb_defaults', array(
			    'delimiter'   => ' / ',
			    'wrap_before' => '<nav class="woocommerce-breadcrumb" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>',
			    'wrap_after'  => '</nav>',
			    'before'      => '',
			    'after'       => '',
			    'home'        => esc_html__( 'Shop', 'overlap' )
		    ) ) );

		    $breadcrumbs = new WC_Breadcrumb();

		    if ( $args['home'] && (is_product_category() || is_single() || is_cart() || is_checkout() || is_account_page() )) {
			    $breadcrumbs->add_crumb( $args['home'], apply_filters( 'woocommerce_breadcrumb_home_url', home_url() ) );
		    }

		    $args['breadcrumb'] = $breadcrumbs->generate();

		    wc_get_template( 'global/breadcrumb.php', $args );
        }
	
        function before_container() {
            ?>
            <div id="content">
                <?php 
                overlap_page_title(); 
                $page_layout = overlap_get_page_layout();
                $sidebar_position = overlap_get_sidebar_position();
                ?>
                <div class="<?php echo esc_attr( overlap_get_layout_class($page_layout, $sidebar_position) ); ?>">
                    <?php overlap_page_background(); ?>
                    <div class="page-content container">
                        <?php 
                        if( $sidebar_position == '2' ){
                            overlap_sidebar('shop', '2');
                        }
                        ?>
                        <div class="<?php echo esc_attr( overlap_get_main_class($sidebar_position) ); ?>">
                            <div class="col-inner">
                    <?php                                             
		}

        function after_container() {             
            echo '</div></div>';    
        }

        function add_sidebar(){
            if( overlap_get_sidebar_position() == '3' ){
                overlap_sidebar('shop', '3');
            }
            echo '</div></div></div>';
            
        }

        function woocommerce_get_product_thumbnail( $size = 'shop_catalog' ) {
            global $product;

            $attrs = array();
            $attrs['href'] = get_permalink();

            $attachment_ids = $product->get_gallery_attachment_ids();
            if( is_array($attachment_ids) && count($attachment_ids) > 0 ){
                $attrs['class'] = 'w-fadeslider';
            }
            ?>
            <div class="cover-image">   
                <a<?php echo overlap_get_attributes( $attrs );?>>
                <?php 
                    if ( has_post_thumbnail() ) {
                        echo get_the_post_thumbnail( get_the_ID(), $size );
                    } elseif ( wc_placeholder_img_src() ) {
                        echo wc_placeholder_img( $size );
                    }
                ?>
                <?php                
                foreach ( $attachment_ids as $attachment_id ) {
                    echo wp_get_attachment_image( $attachment_id, $size );
                }
                ?>
                </a>
            </div>
            <?php
	    }

        function before_shop_loop_item_title(){
            $this->woocommerce_get_product_thumbnail();  
        }

        function upsell_display(){
           woocommerce_upsell_display(intval( overlap_get_option('related_product_items') ), intval( overlap_get_option('related_product_columns') ));
        }

        function order_received_text($message){
            return '<span class="order-received-text">'.$message.'</span>';
        }
    }

}

new Overlap_WooCommerce_Template();

function overlap_woocommerce_dropdown_menu(){
    global $woocommerce;

    $menu_content= sprintf('<li class="menu-item-shop align-right"><a href="%1$s"><span class="cart-items%2$s">%3$s</span></a><ul class="menu-cart">
        <li class="menu-cart-total"><span class="total-text">%4$s</span> %5$s</li>
        <li class="menu-view-cart"><span><a href="%1$s" class="w-link-button w-with-icon"><span class="w-border"></span><span class="w-button-icon"><i class="ol-cart"></i></span><span class="w-button-text">%6$s</span></a></span></li>
        </ul></li>', 
        esc_url( $woocommerce->cart->get_cart_url() ), 
        ($woocommerce->cart->cart_contents_count > 0 )? '':' empty',
        number_format($woocommerce->cart->cart_contents_count, 0, '.', ','),
        esc_html__('Subtotal', 'overlap'),
        $woocommerce->cart->get_cart_total(),
        esc_html__('View Cart', 'overlap')
    );

    return $menu_content;
}

function overlap_dropdown_add_to_cart_fragment( $fragments ) {
	$fragments['.menu-item-shop'] = overlap_woocommerce_dropdown_menu();
	return $fragments;
}
add_filter('add_to_cart_fragments', 'overlap_dropdown_add_to_cart_fragment');

function overlap_woocommerce_menu(){
    global $woocommerce;
    $menu_content= sprintf('<li class="menu-item-cart"><a href="%1$s">%2$s<span class="cart-items%3$s">%4$s</span></a></li>', 
        esc_url( $woocommerce->cart->get_cart_url() ), 
        esc_html__('My Cart', 'overlap'), 
        $woocommerce->cart->cart_contents_count > 0 ? '':' empty', 
        $woocommerce->cart->cart_contents_count 
    );
    return $menu_content;
}

function overlap_add_to_cart_fragment( $fragments ) {
	$fragments['.menu-item-cart'] = overlap_woocommerce_menu();
	return $fragments;
}
add_filter('add_to_cart_fragments', 'overlap_add_to_cart_fragment');

