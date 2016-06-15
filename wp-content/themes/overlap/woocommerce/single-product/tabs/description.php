<?php
/**
 * Description tab
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version	 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $post;

$heading = apply_filters( 'woocommerce_product_description_heading', esc_html__( 'Product Description', 'woocommerce' ) );
?>

<div class="post-content">
	<?php if ( $heading ): ?>
	<h2><?php echo esc_html( $heading ); ?></h2>
	<?php endif; ?>
	<?php the_content(); ?>
</div>