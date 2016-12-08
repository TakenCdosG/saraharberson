<?php
    
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();

    $classes = array();

    $classes[] = 'w-link-button';

    if( !empty( $size ) ){
        $classes[] = $size;
    } 

    if( !empty( $style ) ){
        $classes[] = $style;
    } 

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }

    if( !empty($css) ){
        $classes[] = vc_shortcode_custom_css_class( $css, '' );    
    }

    if( !empty($icon_set) ){
        $icon = isset( ${"icon_" . $icon_set} )? ${"icon_" . $icon_set} : '';
    } 

    if( !empty($icon) ){
        $classes[] = 'w-with-icon';
    }

    $attrs['class'] = implode(' ', $classes);	

    $button_styles = array();
    $border_attrs = array();
    $border_attrs['class'] = 'w-border';

    if( !empty( $color ) ){
        $button_styles[] = 'color:'. $color;
    }

    if( strpos($style, 'outline') !== false){
        if( !empty( $color ) ){
            $button_styles[] = 'border-color:'. $color;
            $border_attrs['style'] = 'background-color:'. $color;
        }
        if( !empty( $hover_color ) ){
            $attrs['data-hover-color'] = $hover_color;
        }
    }else{        
        if( !empty( $bg_color )){
            $border_attrs['style'] = 'background-color:'. $bg_color;
            $button_styles[] = 'border-color:'. $bg_color;
            $attrs['data-hover-color'] = $bg_color;
        }
    }

    if( count( $button_styles ) > 0 ){
        $attrs['style'] = implode(';', $button_styles);
    }

    if($animation) $attrs['data-animation'] = $animation;
    if($animation_delay) $attrs['data-animation-delay'] = floatval( $animation_delay );


    $link = ( $link == '||' ) ? '' : $link;
       
    $link = vc_build_link( $link );

    $attrs['href'] = empty( $link['url'] ) ? '#' : esc_url( $link['url'] ); 

    if( !empty($link['title']) ){
        $attrs['title'] = $link['title']; 
    } 

    if( !empty($link['target']) ){
        $attrs['target'] = trim( $link['target'] );
    } 

?>
<a<?php echo overlap_get_attributes( $attrs );?>>   
    <span<?php echo overlap_get_attributes( $border_attrs );?>></span>
    <?php if( !empty($icon) ):?>
    <span class="w-button-icon"><i class="<?php echo esc_attr( $icon );?>"></i></span>
    <?php endif; ?>
    <?php if( !empty($title) ):?>
    <span class="w-button-text">
        <?php echo trim(esc_html( $title ));?>
    </span>
    <?php endif; ?>
</a>