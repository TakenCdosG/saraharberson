<?php

	$prefix = '_w_';

    /***************************** 
    * Portfolio Post Options 
    ******************************/
    /** Portfolio Options **/
    $portfolio_options = new_cmb2_box( array(
		'id'         => 'portfolio_options',
		'title'      => esc_html__( 'Portfolio', 'overlap' ),
        'icon'         => 'dashicons dashicons-portfolio',
		'object_types'      => array('wyde_portfolio'),
	) );
    
    $portfolio_options->add_field( array(
        'id'   => $prefix . 'portfolio_layout',
		'name' => esc_html__( 'Layout', 'overlap' ),
		'desc' => esc_html__( 'Select portfolio layout.', 'overlap' ),
		'type' => 'radio_inline',
        'options' => array(
            '1'     => '<img src="' . get_template_directory_uri() . '/images/portfolio/1.jpg" alt="Overlap" title="Overlap"/>',
			'2' => '<img src="' . get_template_directory_uri() . '/images/portfolio/2.jpg" alt="Gallery" title="Gallery"/>',
			'3'   => '<img src="' . get_template_directory_uri() . '/images/portfolio/3.jpg" alt="Slider" title="Slider"/>',
			'4'   => '<img src="' . get_template_directory_uri() . '/images/portfolio/4.jpg" alt="Grid" title="Grid"/>',
		),
        'default'   => '1'
	));

    $portfolio_options->add_field( array(
        'id'   => $prefix . 'project_url',
		'name' => esc_html__( 'Project URL', 'overlap' ),
		'desc' => esc_html__( 'Insert a project URL.', 'overlap' ),
		'type' => 'text_url'
	));

    $portfolio_options->add_field( array(
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
    
    /** Embeds Options **/
    $portfolio_options->add_field( array(
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

    $portfolio_options->add_field( array(
        'id'   => $prefix . 'embed_url',
		'name' => esc_html__( 'Media URL', 'overlap' ),
		'desc' => esc_html__( 'Insert a media URL.', 'overlap' ),
		'type' => 'oembed'
	));
   
    /** Gallery Options **/
    $portfolio_options->add_field( array(
        'id'       => $prefix . 'gallery_info',
		'name'     => esc_html__( 'Gallery Options', 'overlap' ),
		'type'     => 'title',
	));

    $portfolio_options->add_field( array(
        'id'   => $prefix . 'gallery_images',
		'name' => esc_html__( 'Images', 'overlap' ),
		'desc' => esc_html__( 'Upload or select images from media library. Recommended size: 640x640px or larger.', 'overlap' ),
		'type' => 'file_list',
        'preview_size' => 'thumbnail', 
	));


    /** Client Options **/
    $portfolio_options->add_field( array(
        'id'       => $prefix . 'client_info',
		'name'     => esc_html__('Client Information', 'overlap'),
		'type'     => 'title',
	));

    $portfolio_options->add_field( array(						
        'id'   => $prefix . 'client_name',
		'name' => esc_html__('Name', 'overlap'),
        'description' => esc_html__('Insert a client name.', 'overlap'),
		'type' => 'text_medium'
	));

    $portfolio_options->add_field( array(
        'id'   => $prefix . 'client_detail',
		'name' => esc_html__('Description', 'overlap' ),
		'description' => esc_html__('Insert a short description about the client.', 'overlap'),
		'type' => 'wysiwyg',
        'options' => array(
            'wpautop' => false, 
            'media_buttons' => false,
            'textarea_rows' => 5, 
            'teeny' => true, 
        ),
	));

    $portfolio_options->add_field( array(
        'id'   => $prefix . 'client_website',
		'name' => 'Website',
        'description' =>  esc_html__('Insert a client website.', 'overlap'),
		'type' => 'text_url',
	));
    
     /** Custom Description Options **/
    $portfolio_options->add_field( array(
        'id'       => $prefix . 'custom_field_info',
		'name'     => esc_html__('Custom Description', 'overlap'),
		'type'     => 'title',
	));

    $custom_fields = $portfolio_options->add_field( array(
        'id'          => $prefix . 'custom_fields',
        'type'        => 'group',
        'description' => esc_html__( 'Add new custom description fields.', 'overlap'),
        'options'     => array(
            'group_title'   => esc_html__( 'Entry {#}', 'overlap' ), // since version 1.1.4, {#} gets replaced by row number
            'add_button'    => esc_html__( 'Add Another Entry', 'overlap' ),
            'remove_button' => esc_html__( 'Remove Entry', 'overlap' ),
            'sortable'      => true, // beta
        ),
        'fields'    => array(
            array(						
                'id'   => 'custom_field_title',
		        'name' => 'Title',
                'description' => esc_html__('Insert a custom field title.', 'overlap'),
		        'type' => 'text_medium',
	        ),
            array(						
                'id'   => 'custom_field_value',
		        'name' => 'Description',
                'description' => esc_html__('Insert a custom field description.', 'overlap'),
		        'type' => 'textarea_small',
	        )

        ),
    ) );


    /***************************** 
    * Header Options 
    ******************************/
    $header_options = new_cmb2_box( array(
		'id'            => 'header_options',
		'title'         => esc_html__( 'Header', 'overlap' ),
		'icon'         => 'dashicons dashicons-menu',
		'object_types'  => array('wyde_portfolio'),
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
		'object_types'  => array('wyde_portfolio'),
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
		'object_types'      => array('wyde_portfolio'),
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

    /****************************
    * Footer Options 
    ******************************/
    $footer_options = new_cmb2_box( array(
		'id'         => 'footer_options',
		'title'      => esc_html__( 'Footer', 'overlap' ),
        'icon'         => 'dashicons dashicons-editor-insertmore',
		'object_types'      => array('wyde_portfolio'),
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