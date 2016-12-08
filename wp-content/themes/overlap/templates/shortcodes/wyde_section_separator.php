<?php 

 	$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

    extract( $atts );

    $attrs = array();

    $classes = array();

    $classes[] = 'w-section-separator';

    $classes[] = 'w-'.$style;

    $classes[] = 'w-'.$overlap;

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }

    $attrs['class'] = implode(' ', $classes);

?>
<div<?php echo overlap_get_attributes( $attrs );?>>
    <?php
    switch ($style) {        
         case 'mountain':
         case 'mountain-alt':
            ?>
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 25" preserveAspectRatio="none">
              <path d="M0,25 L0,12 L12,0 L75,21 L90,18 L100,24 L100,25 Z" fill="<?php echo esc_attr($background_color) ?>"></path>
            </svg>             
            <?php
            break;         
        default:
            ?>
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 25" preserveAspectRatio="none">
              <path d="M0,25 0,10 c25,15 40,-10 66,-10 20,2 28,15 40,0 L100,25 Z" fill="<?php echo esc_attr($background_color) ?>"></path>
            </svg>
            <?php
            break;
    }
    ?>    
</div>