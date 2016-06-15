<?php
        
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();

    $classes = array();

    $classes[] = 'w-info-box';

    $classes[] = 'w-'.$icon_size;        
    if( !empty($icon_position) ) $classes[] = 'w-'.$icon_position;        
    if( !empty($icon_style) ) $classes[] = 'w-'.$icon_style;        

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }

    if( !empty($css) ){
        $classes[] = vc_shortcode_custom_css_class( $css, '' );    
    }

	$attrs['class'] = implode(' ', $classes);

    if($animation) $attrs['data-animation'] = $animation;
    if($animation_delay) $attrs['data-animation-delay'] = floatval( $animation_delay );

    if( !empty($icon_set) ){
        $icon = isset( ${"icon_" . $icon_set} )? ${"icon_" . $icon_set} : '';
    } 

    $icon_attrs = array();
    if( !empty($icon) ){
        $icon_attrs['class'] = 'w-icon';
        if( !empty( $color ) ){
            if( $icon_style == 'circle' ){
                $icon_attrs['style'] = 'border-color:'.$color.';color:'. $color;
            }else{
                $icon_attrs['style'] = 'color:'. $color;
            }
        }
    }

    $border_attrs = array();
    $border_attrs['class'] = 'w-border';
    if( !empty($color) ){
        $border_attrs['style'] = 'box-shadow: 3px 3px '.esc_attr($color);
    }

    
    $link = ( $link == '||' ) ? '' : $link;
       
    $link = vc_build_link( $link );

    $link_attrs = array();

    if( !empty( $link['url'] ) ){                

        $link_attrs['href'] = esc_url( $link['url'] ); 

        if( !empty($link['title']) ){
            $link_attrs['title'] = $link['title']; 
        } 

        if( !empty($link['target']) ){
            $link_attrs['target'] = trim( $link['target'] );
        }
        
    }

?>
<div<?php echo overlap_get_attributes( $attrs );?>>
    <?php if( !empty( $icon ) ):?>
    <span<?php echo overlap_get_attributes( $icon_attrs );?>>
        <i class="<?php echo esc_attr( $icon );?>"></i>
        <span<?php echo overlap_get_attributes( $border_attrs );?>></span>
    </span>
    <?php endif;?>
    <div class="w-content">
        <?php if( !empty( $title ) ):?>
        <h4><?php echo esc_html( $title );?></h4>
        <?php endif;?>
        <?php echo wpb_js_remove_wpautop($content, true); ?>
        <?php if( !empty( $link['url'] ) ):?>
        <p class="w-read-more">
            <a<?php echo overlap_get_attributes( $link_attrs );?>><?php echo esc_html__('Read More', 'overlap');?></a>
        </p>
        <?php endif;?>
    </div>
</div>