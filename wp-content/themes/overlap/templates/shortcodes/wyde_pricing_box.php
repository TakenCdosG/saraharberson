<?php

    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();

    $classes = array();

    $classes[] = 'w-pricing-box';

    if($featured == 'true') $classes[] = 'w-featured';

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }

    $attrs['class'] = implode(' ', $classes);

    $styles = array();

    if( !empty($color) ){
        $attrs['style'] = 'color:'.$color.';';
    } 


    if($animation) $attrs['data-animation'] = $animation;
    if($animation_delay) $attrs['data-animation-delay'] = floatval( $animation_delay );

    $header_attrs = array();
    $header_attrs['class'] = 'box-header clear';

    $img_id = preg_replace( '/[^\d]/', '', $image );    
    if( !empty($img_id) ){

        $image_url = wp_get_attachment_image_src( $img_id, 'full' );

        if( is_array($image_url) ){
            $image_url = $image_url[0];
        }

        if( !empty($image_url) ){
            $header_attrs['class'] .= ' with-bg-image';
            $header_attrs['style'] = 'background-image:url('.$image_url.');';
        }
        
    } 

    $bg_color = $color;
    if( empty($bg_color) ){
        $bg_color = '#000';
    }


    $button_attrs = array();    

    $link = ( $link == '||' ) ? '' : $link;
       
    $link = vc_build_link( $link );

    $button_attrs['href'] = empty( $link['url'] ) ? '#' : esc_url( $link['url'] ); 

    if( !empty($link['title']) ){
        $button_attrs['title'] = $link['title']; 
    } 

    if( !empty($link['target']) ){
        $button_attrs['target'] = trim( $link['target'] );
    } 

    if( empty($button_color) ){
        $button_color = $bg_color;
    }
    //$button_attrs['style'] = 'color:'.$button_color;

?>
<div<?php echo overlap_get_attributes( $attrs );?>>
    <?php if( !empty($heading) ): ?>      
    <div<?php echo overlap_get_attributes( $header_attrs );?>>
        <div class="w-header">   
            <div class="w-background" style="background:<?php echo esc_attr($bg_color);?>"></div>
            <h3><?php echo esc_html( $heading );?></h3>
            <h4><?php echo esc_html( $sub_heading );?></h4>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 20" preserveAspectRatio="none">
          <polygon points="0,20 50,0 100,20" />
        </svg>
    </div>
    <?php endif; ?>
    <?php if( !empty($price) ): ?> 
    <div class="box-price">
        <h4><sup><?php echo esc_html( $price_unit ); ?></sup><?php echo esc_html( $price ); ?></h4>
        <span><?php echo esc_html( $price_term ); ?></span>
    </div>
    <?php endif; ?>
    <div class="box-content">
    <?php if( !empty($content) ): ?>
    <?php echo wpb_js_remove_wpautop($content, true); ?>
    <?php endif;?>
    </div>
    <?php if( !empty($button_text) ): ?> 
    <div class="box-button" style="background:<?php echo esc_attr($button_color); ?>;">
    <a<?php echo overlap_get_attributes( $button_attrs ); ?>><?php echo esc_html( $button_text ); ?></a>
    </div>
    <?php endif;?>
</div>