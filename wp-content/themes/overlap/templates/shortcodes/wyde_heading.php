<?php

    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();

    $classes = array();

    $classes[] = 'w-heading';

    if( !empty($style) ){
        $classes[] = 'heading-'.$style;
    }

    if( !empty($text_align) ){
        $classes[] = 'text-'. $text_align;
    }

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }
        
	$attrs['class'] = implode(' ', $classes);

    if($animation) $attrs['data-animation'] = $animation;
    if($animation_delay) $attrs['data-animation-delay'] = floatval( $animation_delay );

    $heading_attrs = array();
    if( !empty($heading_color) ){
        $heading_attrs['style'] = 'color:'.$heading_color;
    }

    $subheading_attrs = array();
    $subheading_attrs['class'] = 'subheading';
    if( !empty($subheading_color) ){
        $subheading_attrs['style'] = 'color:'.$subheading_color;
    }

?>
<div<?php echo overlap_get_attributes( $attrs );?>>
    <?php if( $style === '3' && !empty($title) ) { ?>
    <div class="w-wrapper">
    <?php } ?>
    <?php if( !empty($subheading) && $style === '1' ) : ?> 
    <h4<?php echo overlap_get_attributes( $subheading_attrs );?>><?php echo wpb_js_remove_wpautop($subheading);?></h4>
    <?php endif; ?>
    <?php if(!empty($title)) : ?> 
    <h2<?php echo overlap_get_attributes( $heading_attrs );?>><?php echo esc_html( $title ); ?></h2>
    <?php endif; ?>
    <?php if( !empty($subheading) && $style != '1' ) : ?> 
    <h4<?php echo overlap_get_attributes( $subheading_attrs );?>><?php echo wpb_js_remove_wpautop($subheading);?></h4>
    <?php endif; ?>
    <?php if( $style === '3' && !empty($title) ) { ?>
    </div>
    <?php } ?>
</div>