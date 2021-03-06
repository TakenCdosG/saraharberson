<?php

    $post_format = get_post_format();

    $has_cover = has_post_thumbnail();

    $images = '';
    $embed_url = '';
    $post_link = '';
    switch( $post_format ){
        case 'gallery':
            $images = get_post_meta(get_the_ID(), '_w_gallery_images', true);
        break;
        case 'link':
            $post_link = get_post_meta(get_the_ID(), '_w_post_link', true);
        break;
        case 'audio':
        case 'video':
            $embed_url = esc_url( get_post_meta(get_the_ID(), '_w_embed_url', true ) );
        break;
    }
    
    $attrs = array();
    $attrs['class'] = 'post-media';
    if( is_array($images) && count($images) > 0 ){
        $attrs['class'] .= ' owl-carousel';
        $attrs['data-auto-height'] = 'true';
    } 
    
?>
<div class="post-detail clear">
    <?php if( !overlap_has_title_area() ) overlap_post_title(); ?>
    <?php if( $has_cover || $images || !empty( $embed_url ) ) : ?>
    <div<?php echo overlap_get_attributes($attrs); ?>>
        <?php

        $media_button = false;
        $image_size = overlap_get_option('blog_single_image_size');        
        $gallery_name = '';

        if($images){
            $gallery_name = '[gallery-'.get_the_ID().']';
        }
        
        if( $has_cover && $image_size != 'hide'):

            $cover_id = get_post_thumbnail_id(get_the_ID()); 

            $lightbox_url = '';

            if( !empty( $embed_url ) ){

                $lightbox_url = overlap_get_media_preview( $embed_url );
                $media_button = true;

            }else{

                $full_image = wp_get_attachment_image_src($cover_id, overlap_get_option('blog_single_lightbox_size') );
                if( isset($full_image[0]) ){
                    $lightbox_url = $full_image[0]; 
                }
                
            }
            
        ?>
	    <div class="featured-<?php echo esc_attr( $image_size ); ?>">
            <a href="<?php echo esc_url( $lightbox_url );?>" data-rel="prettyPhoto<?php echo esc_attr($gallery_name);?>">
                <?php if( $cover_id ):?>
                <?php echo wp_get_attachment_image($cover_id, $image_size); ?>
                <?php endif; ?>
                <?php if( $media_button ){ ?>
                    <span class="w-media-player"></span>
                <?php } ?>
            </a>
	    </div>
        <?php endif; ?>
        <?php if(!$media_button && !empty($embed_url) ){ ?>
        <div class="video-wrapper">
        <?php 
            echo wp_oembed_get($embed_url, array(
                    'width'     => '1170',
                    'height'    => '658'
            ));
        ?>
        </div>
        <?php } ?>
        <?php
        if( is_array($images) ){            
            foreach( $images as $image_id => $image_url ){ 
                $lightbox_url =  wp_get_attachment_image_src($image_id, overlap_get_option('blog_single_lightbox_size') );
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
            }
        }
        ?>       
    </div>
    <?php endif; ?>
    <div class="post-meta">
        <?php if( overlap_get_option('blog_meta_date') ): ?>
        <span class="meta-date">
            <a href="<?php echo esc_url( get_day_link( get_the_date('Y'), get_the_date('m'), get_the_date('d') ) );?>">
                 <?php echo get_the_date(); ?>
            </a>
        </span>
        <?php endif;	?>
        <?php if( overlap_get_option('blog_meta_author') ) : ?>
        <span class="meta-author">
            <strong><?php echo esc_html__('By', 'overlap');?></strong><?php echo the_author_posts_link();?>
        </span>
        <?php endif; ?>  
        <?php if( overlap_get_option('blog_meta_category') ) : ?>
        <span class="meta-category">
            <strong><?php echo esc_html__('In', 'overlap');?></strong><?php echo overlap_get_multiple_categories(); ?>
        </span>  
       <?php endif; ?>         
        <?php if ( overlap_get_option('blog_meta_comment') && ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
        <span class="meta-comment"><?php comments_popup_link( '<i class="w-comment-empty"></i>'.esc_html__('Add Comment', 'overlap'), '<i class="w-comment"></i>1 '.esc_html__('Comment', 'overlap'), '<i class="w-comment"></i>% '.esc_html__('Comments', 'overlap')); ?></span>
        <?php endif; ?>   
        <?php edit_post_link('', '<span class="meta-edit">', '</span>' ); ?>      
    </div>
    <?php 
    if( !empty($post_link) ):
        $urls = parse_url($post_link);        
    ?>
    <p class="post-external-link"><a href="<?php echo esc_url($post_link); ?>" target="_blank"><i class="ol-export"></i> <?php echo esc_url( $urls['host'] ); ?></a></p>
    <?php endif; ?>
    <div class="post-content">
    <?php the_content();  ?>
    </div>
    <?php wp_link_pages(array( 'before' => '<div class="page-links clear">', 'after' => '</div>', 'link_before' => '<span>', 'link_after'  => '</span>' )); ?>
    <?php 
    if( overlap_get_option('blog_single_tags') ) {
        the_tags('<div class="post-tags"><i class="ol-tags"></i>', ', ', '</div>' );   
    } 
    ?>
    <?php 
    if( overlap_get_option('blog_meta_share') ){            
        overlap_blog_meta_share_icons();
    }
    ?> 
</div>
<?php   
    $author_box = get_post_meta(get_the_ID(), '_w_post_author', true);
    if( empty($author_box) ){
        $author_box = overlap_get_option('blog_single_author');
    }

    if( $author_box && $author_box !== 'hide' ){
        overlap_post_author();   
    }
?>
<?php   
    if( overlap_get_option('blog_single_nav') ){
        overlap_post_nav();   
    } 
?>
<?php
    $related = get_post_meta(get_the_ID(), '_w_post_related', true);
    if( empty($related) ){
        $related = overlap_get_option('blog_single_related');
    }

    if( $related && $related !== 'hide' ){
       
        overlap_related_posts();
    }

	if ( overlap_get_option('blog_single_comment') && (comments_open() || get_comments_number()) ) {
		comments_template();
	}
?>