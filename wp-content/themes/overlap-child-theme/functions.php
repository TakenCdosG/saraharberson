<?php

function overlap_child_after_setup_theme() {
    load_child_theme_textdomain( 'overlap', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'overlap_child_after_setup_theme' );

function overlap_child_theme_enqueue_styles() {
    $theme_info = wp_get_theme('overlap');
    $version = $theme_info->get( 'Version' );
    wp_enqueue_style( 'overlap', get_template_directory_uri() . '/style.css', null, $version );
    wp_enqueue_style( 'overlap-child', get_stylesheet_directory_uri() . '/style.css', array( 'overlap-main', 'overlap-icons' ), $version );
}
add_action( 'wp_enqueue_scripts', 'overlap_child_theme_enqueue_styles' );

/* 
 *Add your custom icons to Icon Picker, this method will append your icons into Simple Line icon set.
 *Please don't forget to upload your font files to the host and add your icon CSS class to style.css.
 */
/*
function overlap_add_simple_line_icons( $icons ){

    $custom_icons =  array(
		array( "custom-1" => "Custom 1" ),
		array( "custom-2" => "Custom 2" ),
		array( "custom-2" => "Custom 3" ), 
    );        
      
    return array_merge_recursive( $icons, $custom_icons );

}
add_filter( 'vc_iconpicker-type-simple_line', 'overlap_add_simple_line_icons' );
*/


/* 
 *Add social icons to Social Media section in Theme Options.
 */
/*
function overlap_get_social_media_icons( $icons ){

    $icons['fa fa-delicious'] =  'Delicious';
    $icons['fa fa-foursquare'] =  'Foursquare';
      
    return $icons;

}
add_filter( 'overlap_social_media_icons', 'overlap_get_social_media_icons' );
*/


/* 
 *Add social sharing icons to shareing icons option in blog posts.
 */
/*
function overlap_get_blog_share_links( $links ){

    $links['fa fa-linkedin-square'] =  'https://www.linkedin.com/shareArticle?mini=true&url='.urlencode( get_permalink() ).'&title='.urlencode( get_the_title() );    
      
    return $links;

}
add_filter( 'overlap_blog_share_links', 'overlap_get_blog_share_links' );
*/