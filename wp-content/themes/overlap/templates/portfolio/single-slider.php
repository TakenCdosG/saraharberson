<?php
    
    $has_cover = has_post_thumbnail();

    $images = get_post_meta(get_the_ID(), '_w_gallery_images', true);
                
    $embed_url = esc_url( get_post_meta(get_the_ID(), '_w_embed_url', true ) );
    
?>
<?php   if( $has_cover || $images || !empty( $embed_url ) ) { ?>
<div class="post-media owl-carousel">
    <?php  
    
    $media_button = false;
    $image_size = 'overlap-large';
    $gallery_name = '';
    if($images){
        $gallery_name = '[gallery-'.get_the_ID().']';
    }

    if($has_cover):
        $cover_id = get_post_thumbnail_id(get_the_ID()); 
        $cover_image = wp_get_attachment_image_src($cover_id, $image_size );         
        $lightbox_url = '';        

        if( !empty( $embed_url ) ){
            $lightbox_url = overlap_get_media_preview( $embed_url );
            $media_button = true;
        }else{
            $full_image = wp_get_attachment_image_src($cover_id, overlap_get_option('portfolio_lightbox_size') );
            if( isset($full_image[0]) ){
                $lightbox_url = $full_image[0]; 
            }            
        }

    ?>
    <div>
        <a href="<?php echo esc_url( $lightbox_url );?>" data-rel="prettyPhoto<?php echo esc_attr($gallery_name);?>">
            <?php echo wp_get_attachment_image($cover_id, $image_size); ?>
            <?php if( $media_button ){ ?>
                <span class="w-media-player"></span>
            <?php } ?>
        </a>
    </div>
    <?php   
    endif; 
    if( is_array($images) ): 
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
    endif; 
    ?>
</div>
<?php    } ?>
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