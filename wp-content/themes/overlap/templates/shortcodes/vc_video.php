<?php

    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    if ( $media_url == '' ) {
        return;
    }

    $classes = array();

    $classes[] = 'w-video video-wrapper';
   
    global $wp_embed;

    $video_width =  isset( $GLOBALS['content_width'] ) ? intval( $GLOBALS['content_width'] ) : 1170;
    $video_height = absint( $video_width / 1.61 );

    $embed_html = '';

    if ( is_object( $wp_embed ) ) {

        $embed_html = $wp_embed->run_shortcode( '[embed width="' . $video_width . '" height="' . $video_height . '"]' . $media_url . '[/embed]' );

    }else{

        $embed_html = wp_oembed_get($media_url, array(
            'width'     => $video_width,
            'height'    => $video_height
        ));

    }

    if( strpos ( $embed_html, '[video' ) !== false ){
        $embed_html = do_shortcode( $embed_html );
        $classes[] = 'wp-default-video';
    }    

    $attrs['class'] = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode(' ', $classes), $this->settings['base'], $atts ) );

?>
<div<?php echo overlap_get_attributes( $attrs );?>>
    <?php echo wpb_js_remove_wpautop( $embed_html ); ?>
</div>