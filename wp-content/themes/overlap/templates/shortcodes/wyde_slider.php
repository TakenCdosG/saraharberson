<?php

    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();
    $classes = array();

    $classes[] = 'w-slider';

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }

    $attrs['class'] = implode(' ', $classes);

    if( $auto_play == 'true' ){
        $slider_attrs['data-auto-play'] = 'true';
        $slider_attrs['data-speed'] = $speed;
    }else{
        $slider_attrs['data-auto-play'] = 'false';
    }
    if( !empty($transition) ) $attrs['data-transition'] = $transition;

    // Extract tab titles
    preg_match_all( '/wyde_slide([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );
    $slides = array();
    if ( isset( $matches[1] ) ) {
	    $slides = $matches[1];
    }
?>
<div<?php echo overlap_get_attributes($attrs);?>>
    <div class="w-slides">
        <?php echo wpb_js_remove_wpautop( $content );?> 
    </div>
    <div class="w-slider-nav">
        <a href="#">
        <?php 
        foreach ( $slides as $slide ) {
            $slide_atts = shortcode_parse_atts($slide[0]);
            if( isset($slide_atts['image']) ){
                $img_id = preg_replace( '/[^\d]/', '', $slide_atts['image'] );
                $image = wp_get_attachment_image_src( $img_id, 'ol-large' );
                if( !empty($image) ){
                    echo '<span style="background-image:url('. esc_attr($image[0]) .');"></span>';
                }                
            }               
        } 
        ?>
        </a>
    </div>
    <div class="w-slider-dots">
        <?php foreach ( $slides as $slide ) { ?>
        <div class="owl-dot"><span></span></div>
        <?php } ?>
    </div>
</div>