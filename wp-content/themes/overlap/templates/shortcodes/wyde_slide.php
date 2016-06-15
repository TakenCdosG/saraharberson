<?php

    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();
    $classes = array();
    $classes[] = 'w-slide';

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }

    if( !empty($css) ){
        $classes[] = vc_shortcode_custom_css_class( $css, '' );    
    }

    $attrs['class'] = implode(' ', $classes);

    $img_id = preg_replace( '/[^\d]/', '', $image );    
    if( !empty($img_id) ){
        $image_url = wp_get_attachment_image_src( $img_id, $image_size );
        if( is_array($image_url) ){
            $image_url = $image_url[0];
        }
    } 
    
?>
<div<?php echo overlap_get_attributes( $attrs );?>>
	<?php if( !empty($image_url) ){ ?>
    <img src="<?php echo esc_attr($image_url);?>" alt="<?php echo esc_attr($title);?>" />
    <?php }	?>
	<?php echo do_shortcode(sprintf('[wyde_heading title="%s" subheading="%s" style="3" text_align="left"]', esc_attr($title), esc_attr($subtitle) ));?>
	<div class="w-slider-content">
    	<?php echo wpb_js_remove_wpautop($content);?>
	</div>
</div>