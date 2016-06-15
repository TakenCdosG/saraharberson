<?php    

$classes = array();
$classes[] = 'footer-v3';
if(overlap_get_option('footer_bar_full')) $classes[] = 'w-full';

?>
<div id="footer-bottom" class="<?php echo esc_attr( implode(' ', $classes) ); ?>">
    <div class="container">
        <div class="col-6">
            <?php if( overlap_get_option('footer_logo') ): ?>
            <?php
                $footer_logo = overlap_get_option('footer_logo_image');
                $footer_logo_retina =  overlap_get_option('footer_logo_retina');
                if( !empty($footer_logo_retina) ) $footer_logo_retina = ' data-retina="'.esc_url( $footer_logo_retina['url'] ).'"';
            ?>
            <div id="footer-logo">
                <a href="<?php echo esc_url( site_url() ); ?>">
                    <?php echo sprintf('<img src="%s"%s alt="%s" />', esc_url( $footer_logo['url'] ), $footer_logo_retina, esc_attr( get_bloginfo('name') ) ); ?>
                </a>
            </div>
            <?php endif; ?>
        </div>            
        <div class="col-6">
            <div id="footer-nav">
                <?php if( overlap_get_option('footer_menu') ): ?>
                <ul class="footer-menu">
                    <?php overlap_menu('footer', 1); ?>
                </ul>
                <?php endif; ?>
                <?php if( overlap_get_option('footer_social') ): ?>
                <?php overlap_social_icons(); ?>
                <?php endif; ?>
            </div>
            <?php if( overlap_get_option('footer_text') ): ?>
            <div id="footer-text">
            <?php echo wp_kses_post( overlap_get_option('footer_text_content') ); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if( overlap_get_option('totop_button') ): ?>
    <div id="toplink-wrapper">
        <a href="#"><i class="ol-up"></i></a>
    </div>
    <?php endif; ?>
</div>