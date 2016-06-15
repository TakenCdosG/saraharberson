<?php
        
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    if( !overlap_get_option('ajax_page') ){
        wp_enqueue_script('googlemaps');
    }

    $attrs = array();

    $classes = array();

    $classes[] = 'w-gmaps';

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }
        
	$attrs['class'] = implode(' ', $classes);

    $icon_id = preg_replace( '/[^\d]/', '', $icon );

    $icon_url = wp_get_attachment_url($icon_id);

    if( empty( $icon_url) ) $icon_url = get_template_directory_uri().'/images/pin.png';


    if(!empty($icon_url)) $attrs['data-icon'] = esc_url( $icon_url );

    $attrs['data-maps'] = $gmaps;

    if ( empty($color) ) $color = overlap_get_color();
        
    $attrs['data-color'] = $color;

    $height = str_replace( array( 'px', ' ' ), array( '', '' ), $height );
    if ( is_numeric( $height ) ){
        $attrs['style'] = 'height:'. absint( $height ).'px';
    } 
        
?>
<div<?php echo overlap_get_attributes( $attrs );?>>
    <div class="w-map-canvas"></div>
</div>