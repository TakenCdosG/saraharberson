<?php

    $attrs = array();
    $attrs['id'] = 'side-nav';

    $menu_text_style = overlap_get_option('side_nav_text_style');
    if( !empty($menu_text_style) ){
        $attrs['class'] = 'w-text-'.$menu_text_style;
    }
?>
<aside<?php echo overlap_get_attributes( $attrs );?>>
    <?php 
    if( overlap_get_option('side_nav_overlay_color') ):
        $opacity = '';
        if( overlap_get_option('side_nav_overlay_opacity') ){
            $opacity = 'opacity:'. overlap_get_option('side_nav_overlay_opacity') .';';
        }
    ?>
    <div class="bg-overlay" style="background-color: <?php echo esc_attr( overlap_get_option('side_nav_overlay_color') ); ?>;<?php echo esc_attr( $opacity ); ?>"></div>
    <?php endif; ?>
    <div class="side-nav-wrapper">          
        <nav id="vertical-nav">
            <ul class="vertical-menu">
            <?php overlap_vertical_menu(); ?>
            </ul>
        </nav>
        <ul id="side-menu">
            <?php if( overlap_get_option('menu_shop_cart') && function_exists('overlap_woocommerce_menu') ){ ?>
            <?php echo overlap_woocommerce_menu();   ?>
            <?php } ?>
            <?php if( overlap_get_option('menu_search_icon') ){ ?>
            <li class="menu-item-search">
                <a class="live-search-button" href="#"><i class="ol-search"></i><?php echo esc_html__('Search', 'overlap');?></a>
            </li>
            <?php } ?>
        </ul>
        <?php 
        if( overlap_get_option('menu_contact') ) {
            overlap_contact_info(); 
        }
        if( overlap_get_option('menu_social_icon') ) {
            overlap_social_icons(); 
        }
        ?>
    </div>
</aside>