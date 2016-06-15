<?php
/**
 * Pagination - Show numbered pagination for catalog pages.
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version		2.2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;

if ( $wp_query->max_num_pages <= 1 )
	return;
?>
<nav class="pagination">
	<?php
		$pagination = overlap_get_option('shop_pagination');
	    if( $pagination != 'hide' ){
	        overlap_pagination($pagination, $wp_query->max_num_pages); 
	    }		
	?>
</nav>