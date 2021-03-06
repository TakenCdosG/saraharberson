<?php

	$prefix = '_w_';

    /***************************** 
    * Single Post Options 
    ******************************/
    $post_options = new_cmb2_box( array(
		'id'         => 'post_options',
		'title'      => esc_html__( 'Single Post', 'overlap' ),
        'icon'         => 'dashicons dashicons-media-document',
		'object_types'      => array('post'),
	) );

    /** Embeds Options **/
    $post_options->add_field( array(
        'id'       => $prefix . 'media_info',
		'name'     => esc_html__( 'Media Options', 'overlap' ),
        'desc'     => wp_kses( __( 'You can insert media URL from any major video and audio hosting service (Youtube, Vimeo, etc.). Supports services listed at <a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">Codex page</a>', 'overlap'),
            array(
                'a' => array(
                    'href' => array(),
                    'title' => array(),
                    'target' => array()
                ),
                'br' => array(),
                'em' => array(),
                'strong' => array(),
            )
        ),
        'type'     => 'title',
	));

    $post_options->add_field( array(
        'id'   => $prefix . 'embed_url',
		'name' => esc_html__( 'Media URL', 'overlap' ),
		'desc' => esc_html__( 'Insert a media URL.', 'overlap' ),
		'type' => 'oembed'
	));

    
    /** Gallery Options **/
    $post_options->add_field( array(
        'id'       => $prefix . 'gallery_info',
		'name'     => esc_html__( 'Gallery Options', 'overlap' ),
		'type'     => 'title',
	));

    $post_options->add_field( array(
        'id'   => $prefix . 'gallery_images',
		'name' => esc_html__( 'Images', 'overlap' ),
		'desc' => esc_html__( 'Upload or select images from media library. Recommended size: 960x540px or larger.', 'overlap' ),
		'type' => 'file_list',
        'preview_size' => 'thumbnail', 
	));


    /** Post Format Link Options **/
    $post_options->add_field( array(
        'id'       => $prefix . 'link_info',
		'name'     => esc_html__( 'Link Options.', 'overlap' ),
        'desc' => esc_html__( 'Extra options for Post Format Link.', 'overlap' ),
		'type'     => 'title',
	));

    $post_options->add_field( array(
        'id'   => $prefix . 'post_link',
		'name' => esc_html__( 'Post URL', 'overlap' ),
		'desc' => esc_html__( 'Insert a post URL.', 'overlap' ),
		'type' => 'text_url'
	));
      

    /** Quote Options **/
    $post_options->add_field( array(
        'id'       => $prefix . 'quote_info',
		'name'     => esc_html__( 'Quote Options', 'overlap' ),
        'desc' => esc_html__( 'Extra options for Post Format Quote.', 'overlap' ),
		'type'     => 'title',
	));

    $post_options->add_field( array(
        'id'   => $prefix . 'post_quote',
		'name' => esc_html__( 'Quote', 'overlap' ),
		'desc' => esc_html__( 'Insert quote here.', 'overlap' ),
		'type' => 'textarea_small'
	));

    $post_options->add_field( array(
        'id'   => $prefix . 'post_quote_author',
		'name' => esc_html__( 'Author', 'overlap' ),
		'desc' => esc_html__( 'Insert quote\'s author.', 'overlap' ),
		'type' => 'text_medium'
	));   
    
    /* Post Options */
    $post_options->add_field( array(
        'id'         => $prefix . 'post_author',
		'name'       => esc_html__( 'Author Info', 'overlap' ),
		'desc'       => esc_html__( 'Display author description box.', 'overlap' ),
		'type'    => 'select',
		'options' => array(
            ''   => esc_html__('Default', 'overlap'),
            'show'   => esc_html__('Show', 'overlap'),
            'hide'   => esc_html__('Hide', 'overlap'),
        )
	) );

    $post_options->add_field( array(
        'id'         => $prefix . 'post_related',
		'name'       => esc_html__( 'Related Posts', 'overlap' ),
		'desc'       => esc_html__( 'Display related posts.', 'overlap' ),
		'type'    => 'select',
		'options' => array(
            ''   => esc_html__('Default', 'overlap'),
            'show'   => esc_html__('Show', 'overlap'),
            'hide'   => esc_html__('Hide', 'overlap'),
        )
	) ); 

    /***************************** 
    * Header Options 
    ******************************/
    $header_options = new_cmb2_box( array(
		'id'            => 'header_options',
		'title'         => esc_html__( 'Header', 'overlap' ),
		'icon'         => 'dashicons dashicons-menu',
		'object_types'  => array('post'),
	) );

    $header_options->add_field( array(
        'id'         => $prefix . 'page_header',
		'name'       => esc_html__( 'Display Header', 'overlap' ),
		'desc'       => esc_html__( 'Show or hide the header.', 'overlap' ),
		'type'    => 'select',
		'options' => array(
            ''   => esc_html__('Show', 'overlap'),
            'hide'   => esc_html__('Hide', 'overlap'),
        )
	) );


    $header_options->add_field( array(
        'id'         => $prefix . 'header_transparent',
		'name'       => esc_html__( 'Transparent Header', 'overlap' ),
		'desc'       => esc_html__( 'Select a transparent header.', 'overlap' ),
		'type'    => 'select',
		'options' => array(
            ''   => esc_html__('Disable', 'overlap'),
            'true'   => esc_html__('Enable', 'overlap'),
        )
	) );

    $header_options->add_field( array(
		'id'      => $prefix . 'header_text_style',
		'name'    => esc_html__( 'Foreground Style', 'overlap' ),
		'desc'    => esc_html__( 'Select a header navigation foreground style.', 'overlap' ),
		'type'       => 'select',
        'options'    => array(
            ''   => esc_html__('Default', 'overlap'),
            'light' => esc_html__('Light', 'overlap'),
            'dark' => esc_html__('Dark', 'overlap')
        ),
        'default'  => ''
	) );

    
    /***************************** 
    * Title Options 
    ******************************/
    $title_options = new_cmb2_box( array(
		'id'            => 'title_options',
		'title'         => esc_html__( 'Title Area', 'overlap' ),
        'icon'         => 'dashicons dashicons-feedback',
		'object_types'  => array('post'),
	) );

    $title_options->add_field( array(
        'id'         => $prefix . 'post_custom_title',
		'name'       => esc_html__( 'Title Area', 'overlap' ),
		'desc'       => esc_html__( 'Use custom title area for this post.', 'overlap' ),
		'type'       => 'checkbox'
	) );

    $title_options->add_field( array(
        'id'         => $prefix . 'title_area',
		'name'       => esc_html__( 'Display Title Area', 'overlap' ),
		'desc'       => esc_html__( 'Show or Hide the page title area.', 'overlap' ),
		'type'       => 'select',
        'options'    => array(
            'hide' => esc_html__('Hide', 'overlap'),
            'show' => esc_html__('Show', 'overlap')
        ),
        'default'  => 'hide'
	) );

    $title_options->add_field( array(				
        'id'   => $prefix . 'page_title',
		'name' => esc_html__( 'Page Title', 'overlap' ),
		'desc' => esc_html__( 'Custom text for the page title.', 'overlap' ),
		'type' => 'textarea_code',
        'default' => isset( $_GET['post'] ) ? get_the_title( $_GET['post'] ) : ''
	) );

    $title_options->add_field( array(				
        'id'   => $prefix . 'subtitle',
		'name' => esc_html__( 'Subtitle', 'overlap' ),
		'desc' => esc_html__( 'This text will display as subheading in the title area.', 'overlap' ),
		'type' => 'textarea_code',
        'default' => ''
	) );

    $title_options->add_field( array(               
        'id'   => $prefix . 'title_size',
        'name' => esc_html__( 'Size', 'overlap' ),
        'desc' => esc_html__( 'Select a title area size.', 'overlap' ),
        'type' => 'select',
        'options' => array(
            '' => esc_html__('Default', 'overlap'), 
            's' => esc_html__('Small', 'overlap'), 
            'm' => esc_html__('Medium', 'overlap'), 
            'l' => esc_html__('Large', 'overlap'), 
            'full' => esc_html__('Full Screen', 'overlap'), 
            ),
        'default' => ''
    ));

    $title_options->add_field( array(
		'id'   => $prefix . 'title_scroll_effect',
		'name' => esc_html__( 'Scrolling Effect', 'overlap' ),
		'desc' => esc_html__( 'Select a scrolling animation for title text and subtitle.', 'overlap' ),
		'type' => 'select',
        'options'   => array(
            '' => esc_html__('Default', 'overlap'), 
            'none' => esc_html__('None', 'overlap'),            
            'split' => esc_html__('Split', 'overlap'),
            'fadeOut' => esc_html__('Fade Out', 'overlap'), 
            'fadeOutUp' => esc_html__('Fade Out Up', 'overlap'), 
            'fadeOutDown' => esc_html__('Fade Out Down', 'overlap'), 
            'zoomIn' => esc_html__('Zoom In', 'overlap'), 
            'zoomInUp' => esc_html__('Zoom In Up', 'overlap'), 
            'zoomInDown' => esc_html__('Zoom In Down', 'overlap'), 
            'zoomOut' => esc_html__('Zoom Out', 'overlap'), 
            'zoomOutUp' => esc_html__('Zoom Out Up', 'overlap'), 
            'zoomOutDown' => esc_html__('Zoom Out Down', 'overlap'), 
            ),
        'default'  => ''
	));

    $title_options->add_field( array(
		'id'      => $prefix . 'title_color',
		'name'    => esc_html__( 'Text Color', 'overlap' ),
		'desc'    => esc_html__( 'Select the title text color.', 'overlap' ),
		'type'    => 'colorpicker',
		'default' => ''
	) );

    $title_options->add_field( array(				
        'id'   => $prefix . 'title_align',
		'name' => esc_html__( 'Alignment', 'overlap' ),
		'desc' => esc_html__( 'Select the title text alignment.', 'overlap' ),
		'type' => 'select',
        'options' => array(
            '' => esc_html__('Default', 'overlap'), 
            'left' => esc_html__('Left', 'overlap'), 
            'center' => esc_html__('Center', 'overlap'), 
            'right' => esc_html__('Right', 'overlap'), 
            ),
        'default' => ''
	) );    

    $title_options->add_field( array(				
        'id'   => $prefix . 'title_background',
		'name' => esc_html__( 'Background', 'overlap' ),
		'desc' => esc_html__( 'Select a background type for the title area.', 'overlap' ),
		'type' => 'select',
        'options' => array(
            '' => esc_html__('Default', 'overlap'), 
            'none' => esc_html__('None', 'overlap'), 
            'color' => esc_html__('Color', 'overlap'), 
            'image' => esc_html__('Image', 'overlap'), 
            'video' => esc_html__('Video', 'overlap')
            ),
        'default' => ''
	));

    $title_options->add_field( array(
        'id'   => $prefix . 'title_background_image',
        'name' => esc_html__( 'Background Image', 'overlap' ),
        'desc' => esc_html__( 'Choose an image or insert a URL.', 'overlap' ),
        'type' => 'file'
    ));


    $title_options->add_field( array(
        'id'   => $prefix . 'title_background_video',
        'name' => esc_html__( 'Background Video', 'overlap' ),
        'desc' => esc_html__( 'Choose an MP4 video or insert a URL.', 'overlap' ),
        'type' => 'file'
    ));

    $title_options->add_field( array(
		'id'      => $prefix . 'title_background_color',
		'name'    => esc_html__( 'Background Color', 'overlap' ),
		'desc'    => esc_html__( 'Choose a background color.', 'overlap' ),
		'type'    => 'colorpicker',
		'default' => ''
	));

    $title_options->add_field( array(
		'id'   => $prefix . 'title_background_size',
		'name' => esc_html__( 'Background Size', 'overlap' ),
		'desc' => esc_html__( 'For full width or high-definition background image, choose Cover. Otherwise, choose Contain.', 'overlap' ),
		'type' => 'select',
        'options'   => array(
            '' => esc_html__('Cover', 'overlap'), 
            'contain' => esc_html__('Contain', 'overlap')
            ),
        'default'  => ''
	));

    $title_options->add_field( array(
		'id'      => $prefix . 'title_overlay_color',
		'name'    => esc_html__( 'Background Overlay Color', 'overlap' ),
		'desc'    => esc_html__( 'Select background overlay color.', 'overlap' ),
		'type'    => 'colorpicker',
		'default' => ''
	));

    $title_options->add_field( array(
		'id'      => $prefix . 'title_overlay_opacity',
		'name'    => esc_html__( 'Background Overlay Opacity', 'overlap' ),
		'desc'    => esc_html__( 'Select background overlay opacity.', 'overlap' ),
		'type' => 'select',
        'options'   => array(
            '' => esc_html__('Default', 'overlap'), 
            '0.1' => '0.1', 
            '0.2' => '0.2', 
            '0.3' => '0.3', 
            '0.4' => '0.4', 
            '0.5' => '0.5', 
            '0.6' => '0.6', 
            '0.7' => '0.7', 
            '0.8' => '0.8', 
            '0.9' => '0.9', 
            ),
		'default' => ''
	));

    $title_options->add_field( array(
        'id'      => $prefix . 'title_background_effect',
        'name'    => esc_html__( 'Background Effect', 'overlap' ),
        'desc'    => esc_html__( 'Select background scrolling effect.', 'overlap' ),
        'type' => 'select',
        'options'   => array(
            '' => esc_html__('None', 'overlap'),
            'gradient' => esc_html__('Gradient', 'overlap'),
            'fadeOut' => esc_html__('Fade Out', 'overlap'),
            'parallax' => esc_html__('Parallax', 'overlap'), 
        ),
        'default' => ''
    ));

    
    /***************************** 
    * Background Options 
    ******************************/
    $background_options = new_cmb2_box( array(
		'id'         => 'background_options',
		'title'      => esc_html__( 'Background', 'overlap' ),
        'icon'         => 'dashicons dashicons-format-image',
		'object_types'      => array('post'),
	) );

    $background_options->add_field( array(
		'id'   => $prefix . 'background',
		'name' => esc_html__( 'Background', 'overlap' ),
		'desc' => esc_html__( 'Select a background type for this page.', 'overlap' ),
		'type' => 'select',
        'options' => array(
            '' => esc_html__('None', 'overlap'), 
            'color' => esc_html__('Color', 'overlap'), 
            'image' => esc_html__('Image', 'overlap'), 
        ),
        'default' => ''
	));

    $background_options->add_field( array(
		'id'   => $prefix . 'background_image',
		'name' => esc_html__( 'Background Image', 'overlap' ),
		'desc' => esc_html__( 'Upload an image or insert a URL.', 'overlap' ),
		'type' => 'file'
	));
    
    $background_options->add_field( array(
		'id'      => $prefix . 'background_color',
		'name'    => esc_html__( 'Background Color', 'overlap' ),
		'desc'    => esc_html__( 'Choose a background color.', 'overlap' ),
		'type'    => 'colorpicker',
		'default' => ''
	));

    $background_options->add_field( array(
		'id'   => $prefix . 'background_size',
		'name' => esc_html__( 'Background Size', 'overlap' ),
		'desc' => esc_html__( 'For full width or high-definition background image, choose Cover. Otherwise, choose Contain.', 'overlap' ),
		'type' => 'select',
        'options'   => array(
            'cover' => esc_html__('Cover', 'overlap'), 
            'contain' => esc_html__('Contain', 'overlap')
            ),
        'default'  => 'cover'
	));

    $background_options->add_field( array(
		'id'      => $prefix . 'background_overlay_color',
		'name'    => esc_html__( 'Background Overlay Color', 'overlap' ),
		'desc'    => esc_html__( 'Select background color overlay.', 'overlap' ),
		'type'    => 'colorpicker',
		'default' => ''
	));

    $background_options->add_field( array(
		'id'      => $prefix . 'background_overlay_opacity',
		'name'    => esc_html__( 'Background Overlay Opacity', 'overlap' ),
		'desc'    => esc_html__( 'Select background overlay opacity.', 'overlap' ),
		'type' => 'select',
        'options'   => array(
                '' => esc_html__('Default', 'overlap'), 
                '0.1' => '0.1', 
                '0.2' => '0.2', 
                '0.3' => '0.3', 
                '0.4' => '0.4', 
                '0.5' => '0.5', 
                '0.6' => '0.6', 
                '0.7' => '0.7', 
                '0.8' => '0.8', 
                '0.9' => '0.9', 
            ),
		'default' => ''
	));

    /***************************** 
    * Sidebar Options 
    ******************************/
    $sidebars = array();
    $sidebars[''] = esc_html__('Default', 'overlap');
    foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) { 
        $sidebars[$sidebar['id']] = $sidebar['name'];
    }

    $sidebar_options = new_cmb2_box( array(
		'id'         => 'sidebar_options',
		'title'      => esc_html__( 'Sidebar', 'overlap' ),
        'icon'         => 'dashicons dashicons-format-aside',
		'object_types'      => array('post'),
	) );

    $sidebar_options->add_field( array(
        'id'         => $prefix . 'post_custom_sidebar',
		'name'       => esc_html__( 'Sidebar', 'overlap' ),
		'desc'       => esc_html__( 'Use custom sidebar for this post.', 'overlap' ),
		'type'       => 'checkbox'
	) );

    $sidebar_options->add_field( array(
        'id'         => $prefix . 'sidebar_position',
		'name'       => esc_html__( 'Sidebar Position', 'overlap' ),
		'desc'       => esc_html__( 'Select sidebar position.', 'overlap' ),
		'type'    => 'radio_inline',
		'options' => array(
			'1' => '<img src="' . get_template_directory_uri() . '/images/columns/1.png" alt="No Sidebar"/>',
			'2' => '<img src="' . get_template_directory_uri() . '/images/columns/2.png" alt="One Left"/>',
			'3' => '<img src="' . get_template_directory_uri() . '/images/columns/3.png" alt="One Right"/>',
		),
        'default'   =>  '1'
	));

    $sidebar_options->add_field( array(
		'id'   => $prefix . 'sidebar_name',
		'name' => esc_html__( 'Sidebar Name', 'overlap' ),
		'desc' => esc_html__( 'Select a sidebar to display.', 'overlap' ),
		'type' => 'select',
        'options' => $sidebars,
        'default' => ''
	));
   
    /****************************
    * Footer Options 
    ******************************/
    $footer_options = new_cmb2_box( array(
		'id'         => 'footer_options',
		'title'      => esc_html__( 'Footer', 'overlap' ),
        'icon'         => 'dashicons dashicons-editor-insertmore',
		'object_types'      => array('post'),
	) );

    $footer_options->add_field( array(
        'id'         => $prefix . 'page_footer_content',
        'name'       => esc_html__( 'Footer Content', 'overlap' ),
        'desc'       => esc_html__( 'Show or hide the footer content area.', 'overlap' ),
        'type'      => 'select',
        'options'   => array(
            ''   => esc_html__('Show', 'overlap'),
            'hide'   => esc_html__('Hide', 'overlap'),
        ),
    ));

    $footer_options->add_field( array(
        'id'         => $prefix . 'page_footer',
        'name'       => esc_html__( 'Footer', 'overlap' ),
        'desc'       => esc_html__( 'Show or hide the footer bottom bar.', 'overlap' ),
        'type'      => 'select',
        'options'   => array(
            ''   => esc_html__('Show', 'overlap'),
            'hide'   => esc_html__('Hide', 'overlap'),
        ),
    ));