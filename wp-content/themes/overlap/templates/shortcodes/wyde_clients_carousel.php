<?php
	
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();

    $classes = array();

    $classes[] = 'w-clients-carousel';

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }

	$attrs['class'] = implode(' ', $classes);

    if($animation) $attrs['data-animation'] = $animation;
    if($animation_delay) $attrs['data-animation-delay'] = floatval( $animation_delay );

    $slider_attrs = array();

    $slider_attrs['class'] = 'owl-carousel';

    $slider_attrs['data-items'] =  intval( $visible_items );    
    $slider_attrs['data-navigation'] = ($show_navigation == 'true' ?  'true':'false');
    $slider_attrs['data-pagination'] = ($show_pagination == 'true' ? 'true':'false');
    $slider_attrs['data-loop'] = ($loop == 'true' ? 'true':'false');

    if( $auto_play == 'true' ){
        $slider_attrs['data-auto-play'] = 'true';
        $slider_attrs['data-speed'] = $speed;
    }else{
        $slider_attrs['data-auto-play'] = 'false';
    }

    if( !empty($images) ){
        $images = explode(',', $images);
    }

?>
<div<?php echo overlap_get_attributes( $attrs ); ?>>
    <div<?php echo overlap_get_attributes( $slider_attrs );?>>
    <?php foreach ($images as $image_id ){ ?>
        <div>
        <?php if($image_id) : ?>
            <?php echo wp_get_attachment_image($image_id, $image_size, false); ?>
        <?php endif; ?>
        </div>
    <?php } ?>
    </div>
</div>