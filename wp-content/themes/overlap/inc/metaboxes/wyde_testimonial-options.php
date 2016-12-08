<?php

	$prefix = '_w_';
  
    
    /***************************** 
    * Testimonial Options 
    ******************************/
    /** Customer Information **/
    $testimonial_options = new_cmb2_box( array(
		'id'         => 'testimonial_options',
		'title'      => esc_html__( 'Customer Information', 'overlap' ),
		'object_types'      => array('wyde_testimonial'),
	) );

    $testimonial_options->add_field( array(
        'id'       => $prefix . 'testimonial_position',
		'name'     => esc_html__( 'Job Title', 'overlap' ),
        'desc' => esc_html__( 'Insert a customer\'s job title.', 'overlap' ),
		'type'     => 'text_medium'
	));

    $testimonial_options->add_field( array(
        'id'       => $prefix . 'testimonial_company',
		'name'     => esc_html__( 'Company', 'overlap' ),
        'desc' => esc_html__( 'Insert a company name.', 'overlap' ),
		'type'     => 'text_medium'
	));

    $testimonial_options->add_field( array(
        'id'   => $prefix . 'testimonial_website',
		'name' => esc_html__( 'Website', 'overlap' ),
		'desc' => esc_html__( 'Insert a website URL for this customer or company.', 'overlap' ),
		'type' => 'text_url'
	));

    $testimonial_options->add_field( array(
        'id'   => $prefix . 'testimonial_email',
		'name'     => esc_html__( 'Email Address', 'overlap' ),
        'desc' => esc_html__( 'Insert a customer\'s email address.', 'overlap' ),
		'type'     => 'text_medium'
	));

    $testimonial_options->add_field( array(
        'id'   => $prefix . 'testimonial_detail',
		'name' => '',
		'desc' => '',
		'type' => 'wysiwyg',
        'options' => array(
            'media_buttons' => false,
            'teeny' => true,
        ),
	));    