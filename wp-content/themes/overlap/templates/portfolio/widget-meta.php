<?php
   
    $tags = get_the_terms( get_the_ID(), 'portfolio_tag' );
    $project_url = get_post_meta(get_the_ID(), '_w_project_url', true );
    $post_date = overlap_get_option('portfolio_date');
    if( $tags || $project_url || $post_date ):

?>
<div class="portfolio-meta-widget widget">
    <?php if( is_array($tags) && count($tags) > 0 ): ?>
    <p class="portfolio-tags">
        <?php $tag_links = array(); ?>
        <?php 
        foreach ( $tags as $item ){
            $tag_links[] = sprintf('<a href="%s">%s</a>', esc_url( get_term_link($item) ), esc_html( $item->name ));
        } 
        echo '<span><i class="ol-tags"></i>'.implode(', ', $tag_links).'</span>';
        ?>  
    </p>
    <?php endif; ?>  
    <?php if( !empty( $project_url )): ?>
    <p><i class="ol-link"></i> <a href="<?php echo esc_url( $project_url );?>" title="Visit Site" class="launch-project"><?php echo esc_html__('Visit Site', 'overlap');?></a></p>
    <?php endif; ?>
    <?php if( $post_date ): ?>
    <p><i class="ol-calendar"></i><?php echo esc_html__('Published', 'overlap').': '. get_the_date();?></p>
    <?php endif; ?>
    <?php if( overlap_get_option('portfolio_single_share') ): ?>
    <p class="portfolio-share">
    <?php
    
    $share_links = array(
        'ol-facebook' => 'http://www.facebook.com/sharer/sharer.php?u='. urlencode( get_permalink() ),
        'ol-twitter'    => 'https://twitter.com/intent/tweet?source=webclient&amp;url='. urlencode( get_permalink() ).'&amp;text='. urlencode( get_the_title() ),
        'ol-google-plus' => 'https://plus.google.com/share?url='. urlencode( get_permalink() ),
    );

    $share_links = apply_filters('overlap_portfolio_share_links', $share_links);

    foreach ($share_links as $icon => $link) {
        echo sprintf('<a href="%s" target="_blank"><i class="%s"></i></a>', $link, $icon);
    }
    ?>  
    </p>
    <?php endif; ?>
</div>
<?php endif; ?>  