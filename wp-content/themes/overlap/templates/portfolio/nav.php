<?php
    // Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
 
    $home_button_url = '';
    if( overlap_get_option('portfolio_home') && overlap_get_option('portfolio_home_url') ){
        $home_button_url = overlap_get_option('portfolio_home_url');
    } 

?>
<nav class="post-nav clear">
    <div class="prev-post">
    <?php
		if($previous){
            $prev_thumbnail = overlap_get_portfolio_thumbnail($previous->ID);
            previous_post_link('%link', '');
            echo '<div class="post-link clear">';
            previous_post_link('<span>%link</span>', $prev_thumbnail);
            previous_post_link('<h4>%link</h4>');
            echo '</div>';
		} 
    ?>
    </div>
    <div class="next-post">
    <?php
		if($next){
            $next_thumbnail = overlap_get_portfolio_thumbnail($next->ID);
            next_post_link('%link', '');
            echo '<div class="post-link clear">';
            next_post_link('<h4>%link</h4>');
            next_post_link('<span>%link</span>', $next_thumbnail);
            echo '</div>';
		} 
    ?>
    </div>
    <?php if( $home_button_url ):?>
    <div class="nav-home">
        <a href="<?php echo esc_url( $home_button_url );?>"><i class="ol-th"></i></a>
    </div>
    <?php endif; ?>
</nav>