<?php

    $has_cover = has_post_thumbnail();

    $images = get_post_meta(get_the_ID(), '_w_gallery_images', true);
                
    $embed_url = esc_url( get_post_meta(get_the_ID(), '_w_embed_url', true ) );

    $media_button = false;
    $image_size = 'overlap-fullwidth';
    $gallery_name = '';
    if($images){
        $gallery_name = '[gallery-'.get_the_ID().']';
    }
    
?>
<div class="row">
    <div class="col col-7 no-padding">
        <?php if( $has_cover || !empty( $embed_url ) ) : ?>
        <div class="post-media">
            <?php
            if($has_cover): 

                $cover_id = get_post_thumbnail_id(get_the_ID()); 
                $cover_image = wp_get_attachment_image_src($cover_id, $image_size ); 
           
                $lightbox_url = '';                    

                if( !empty( $embed_url ) ){

                    $lightbox_url = overlap_get_media_preview( $embed_url );

                    $media_button = true;

                }else{
                    if( overlap_get_option('portfolio_lightbox_size') == $image_size ) {
                        $lightbox_url = $cover_image[0];
                    }else{
                        $full_image = wp_get_attachment_image_src($cover_id, overlap_get_option('portfolio_lightbox_size') );
                        if( isset($full_image[0]) ){
                            $lightbox_url = $full_image[0]; 
                        }
                    }
                }
            ?>
            <?php if($cover_image[0]):?>
            <div class="cover-image" style="background-image:url('<?php echo esc_url( $cover_image[0] ); ?>');">            
                <a href="<?php echo esc_url( $lightbox_url );?>" data-rel="prettyPhoto<?php echo esc_attr($gallery_name);?>">                
                    <?php if( $media_button ){ ?>
                        <span class="w-media-player"></span>
                    <?php } ?>
                </a>
            </div>
            <?php endif; ?>
            <?php 
            endif;                 
            ?>
        </div>
        <?php endif; ?>            
    </div>
    <div class="col col-3 portfolio-meta-wrapper">
        <div class="col-inner">      
            <?php overlap_portfolio_widget('meta'); ?>
        </div>
    </div>
</div>
<div class="page-content">
    <div class="post-description container">
        <div class="row">
            <div class="w-main col col-8">  
                <div class="col-inner">     
                    <div class="post-content">                          
                        <?php the_title('<h2 class="post-title">', '</h2>'); ?>                                               
                        <?php the_content(); ?>      
                        <?php overlap_portfolio_widget('meta'); ?>
                        <?php overlap_portfolio_widget('clients'); ?>     
                        <?php overlap_portfolio_widget('categories'); ?>
                        <?php overlap_portfolio_widget('skills'); ?>                                         
                    </div>
                </div>
            </div>
            <div class="w-sidebar col col-4">
                <div class="post-extra-widgets col-inner">                        
                    <?php overlap_portfolio_widget('fields'); ?>
                </div>
            </div>                
        </div>        
    </div>
    <?php if($images && is_array($images) ): ?>
    <div class="post-slider owl-carousel" data-items="4">
    <?php      
    $image_size = 'overlap-large';
    foreach( $images as $image_id => $image_url ):            
            $lightbox_url =  wp_get_attachment_image_src($image_id, overlap_get_option('portfolio_lightbox_size') );
            if( is_array($lightbox_url) ){
                $lightbox_url = $lightbox_url[0];
            }           
    ?>
    <div>
        <a href="<?php echo esc_url( $lightbox_url );?>" data-rel="prettyPhoto<?php echo esc_attr($gallery_name);?>">
            <?php echo wp_get_attachment_image($image_id, $image_size); ?>
        </a>
    </div>
    <?php       
        endforeach; 
    ?>
    </div>
    <?php endif; ?>
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