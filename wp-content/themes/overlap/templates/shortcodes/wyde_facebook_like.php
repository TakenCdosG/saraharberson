<?php

	$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

    extract( $atts );
            
    $attrs = array();

    $classes = array();

    $styles = array();

    $classes[] = 'w-facebook-box';    

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }   

    $attrs['class'] = implode(' ', $classes);

    if( !empty( $page_url )):

    $show_facepile = ($show_facepile == 'true' ? 'true':'false');
    $small_header = ($small_header == 'true' ? 'true':'false');

    if( !empty($width) ){
        $width = absint($width);
        $attrs['data-width'] = $width;
        $styles[]= "width:{$width}px";
    }

    if( empty($height) ){
        $height = 500;
    }

    $height = absint($height);
    $attrs['data-height'] = $height;
    $styles[]= "height:{$height}px";

    if( !empty($show_facepile) ) $attrs['data-show_facepile'] = $show_facepile;
    if( !empty($small_header) ) $attrs['data-small_header'] = $small_header;
    if( !empty($page_url) ) $attrs['data-page_url'] = esc_url( $page_url );
    if( !empty($tabs) ) $attrs['data-tabs'] = $tabs;

    $attrs['style'] = implode(';', $styles);
	
?>
<div<?php echo overlap_get_attributes( $attrs ); ?>></div>
<?php endif; ?>