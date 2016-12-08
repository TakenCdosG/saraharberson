<?php
       
    $attrs = array();
    
    $attrs['id'] = 'header';
    
    $classes = array();    

    $classes[] = 'w-'. overlap_get_header_style();    

    $header_sticky = overlap_get_option('header_sticky');

    $page_id = overlap_get_current_page();
        
    $text_style = get_post_meta( $page_id, '_w_header_text_style', true );
    
    if( $header_sticky ){
        $classes[] = 'w-sticky';
    }
     
    if( overlap_get_option('header_fullwidth') ){
        $classes[] = 'w-full';
    }
       
    if( overlap_header_overlay() ){
        $classes[] = 'w-transparent';
    }
    
    if( empty( $text_style ) ){
        if( overlap_get_header_style() == 'dark' ){
            $text_style = 'light';
        }else{
            $text_style = 'dark';
        }
    }

    $classes[] = 'w-text-'.$text_style;    
    
    $attrs['class'] = implode(' ', $classes);
    
?>
<header <?php echo overlap_get_attributes( $attrs );?>> 
    <div class="container">       
        <span class="mobile-nav-icon">
            <i class="menu-icon"></i>
        </span>
        <?php overlap_logo(); ?>
        <nav id="top-nav" class="dropdown-nav">
            <ul class="top-menu">
                <?php 
                if( overlap_get_nav_layout() != 'fullscreen' ){
                    overlap_primary_menu();
                }
                if( overlap_get_nav_layout() != 'center' ){
                    overlap_extra_menu();
                }
                ?>    
            </ul>
            <?php if( overlap_get_nav_layout() == 'center' ):?>
            <ul class="ex-menu">
                <?php overlap_extra_menu(); ?>
            </ul>
            <?php endif;?>
        </nav>
        <?php if( overlap_get_nav_layout() == 'fullscreen' ):?>
        <div class="full-nav-button">
            <span class="full-nav-icon">
                <i class="menu-icon"></i>
            </span>
        </div>
        <?php endif; ?>
    </div>
</header>