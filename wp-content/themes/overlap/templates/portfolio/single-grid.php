<?php

    $has_cover = has_post_thumbnail();

    $images = get_post_meta(get_the_ID(), '_w_gallery_images', true);
    
    $embed_url = esc_url( get_post_meta(get_the_ID(), '_w_embed_url', true ) );
        
?>
<?php
if( $has_cover || $images || !empty( $embed_url ) ): 
?>
<div class="post-media container">
    <ul class="w-grid clear">
        <?php       

        $image_size = 'overlap-fullwidth';
        $gallery_name = '';
        if($images){
            $gallery_name = '[gallery-'.get_the_ID().']';
        }
        
        $cover_id = get_post_thumbnail_id(get_the_ID()); 
        $cover_image = wp_get_attachment_image_src($cover_id, $image_size ); 
        
        $lightbox_url = '';

        if( !empty( $embed_url ) ){
            $lightbox_url = overlap_get_media_preview( $embed_url );
        ?>
        <li class="w-featured">
            <?php if( isset($cover_image[0]) ):?>
            <div class="cover-image" style="background-image: url('<?php echo esc_url( $cover_image[0] );?>');"></div>
            <?php endif; ?>
            <a href="<?php echo esc_url($lightbox_url);?>" data-rel="prettyPhoto[portfolio]"><span class="w-media-player"></span></a>
        </li>
        <?php }elseif( $has_cover ){ 
            $image_size = 'overlap-large';
            $lightbox_url =  wp_get_attachment_image_src($cover_id, overlap_get_option('portfolio_lightbox_size') );
            if( is_array($lightbox_url) ){
                $lightbox_url = $lightbox_url[0];
            }       
        ?>
        <li class="col-4">
            <a href="<?php echo esc_url( $lightbox_url );?>" data-rel="prettyPhoto<?php echo esc_attr($gallery_name);?>">
                <?php echo wp_get_attachment_image($cover_id, $image_size); ?>
            </a>
        </li>
        <?php } ?>
        <?php if( is_array($images) ):
        $image_size = 'overlap-large';
        foreach( $images as $image_id => $image_url ):                               
            $lightbox_url =  wp_get_attachment_image_src($image_id, overlap_get_option('portfolio_lightbox_size') );
            if( is_array($lightbox_url) ){
                $lightbox_url = $lightbox_url[0];
            }
        ?>
        <?php if( $lightbox_url ):?>
        <li class="col-4">
            <a href="<?php echo esc_url( $lightbox_url );?>" data-rel="prettyPhoto<?php echo esc_attr($gallery_name);?>">
                <?php echo wp_get_attachment_image($image_id, $image_size); ?>
            </a>
        </li>
        <?php endif; ?>
        <?php   
            endforeach; 
        endif;
        ?>
    </ul>
</div>
<?php 
endif; 
?>
<div class="page-content container">
    <div class="row">
        <div class="w-main col col-9">
            <div class="col-inner">
            <?php if( !overlap_has_title_area() ) the_title('<h2 class="post-title">', '</h2>'); ?>
            <div class="post-content">
                <?php the_content(); ?>   
                </div>         
            </div>
        </div>
        <div class="w-sidebar col col-3 post-description">
            <div class="col-inner">
            <?php overlap_portfolio_sidebar(); ?>
            </div>
        </div>
    </div>
    <?php if(overlap_get_option('portfolio_nav')) overlap_portfolio_nav(); ?>
    <?php
    $related = get_post_meta(get_the_ID(), '_w_post_related', true);
    if( empty($related) ){
        $related = overlap_get_option('portfolio_related');
    }
    if( $related && $related !== 'hide' ){
        overlap_portfolio_related();
    }
    ?>
</div>