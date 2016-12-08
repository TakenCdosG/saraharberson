<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>        
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="<?php echo esc_attr( overlap_get_viewport() ); ?>" />        
        <?php 
        if ( !( function_exists( 'has_site_icon' ) && has_site_icon() ) ) {
            overlap_custom_favicon();
        } 
        ?>
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
        <?php overlap_page_loader(); ?>
        <?php overlap_nav(); ?>