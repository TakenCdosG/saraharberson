<?php 

    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();

    $classes = array();

    $classes[] = 'w-counter-box';

    if( !empty($style) ){
        $classes[] = 'w-'.$style;
    }

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }
  
	$attrs['class'] = implode(' ', $classes);

    if( !empty($color) ){
        $attrs['style'] = 'color:'.$color.';';
    }

    if($animation) $attrs['data-animation'] = $animation;
    if($animation_delay) $attrs['data-animation-delay'] = floatval( $animation_delay );

    if( !empty($icon_set) ){
        $icon = isset( ${"icon_" . $icon_set} )? ${"icon_" . $icon_set} : '';
    } 
?>
<div<?php echo overlap_get_attributes( $attrs ) ;?>>
    <?php if( !empty( $icon ) ):?>
    <span><i class="<?php echo esc_attr( $icon );?>"></i></span>
    <?php endif; ?>
    <h3 class="counter-value" data-value="<?php echo esc_attr(  intval( $value ) );?>"><?php echo intval( $start );?></h3>
    <?php if( !empty( $title ) ):?>
    <h4 class="counter-title">
    <?php echo esc_html( $title );?>
    </h4>
    <?php endif; ?>
</div>