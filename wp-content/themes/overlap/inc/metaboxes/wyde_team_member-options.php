<?php

	$prefix = '_w_';
  
    /***************************** 
    * Team Members Options 
    ******************************/
    // get social icons from custom-function.php
    $socials = overlap_get_social_icons();
    $social_icons = array();
    foreach($socials as $key => $value){
        $social_icons[$value] = $value; 
    }

    /** Member Information **/
    $member_options = new_cmb2_box( array(
		'id'         => 'member_options',
		'title'      => esc_html__( 'Member Information', 'overlap' ),
		'object_types'      => array('wyde_team_member'),
	) );

    $member_options->add_field( array(
        'id'       => $prefix . 'member_position',
		'name'     => esc_html__( 'Job Title', 'overlap' ),
        'desc' => esc_html__( 'Insert a member\'s job title.', 'overlap' ),
		'type'     => 'text_medium'
	));

    $member_options->add_field( array(
        'id'   => $prefix . 'member_detail',
		'name' => esc_html__( 'Description', 'overlap' ),
		'desc' => esc_html__( 'Input a member description.', 'overlap' ),
		'type' => 'wysiwyg',
        'options' => array(
            'wpautop' => true,
            'media_buttons' => false,
            'teeny' => false,
        ),
	));

    $member_options->add_field( array(
        'id'       => $prefix . 'member_email',
		'name'     => esc_html__( 'Email Address', 'overlap' ),
        'desc' => esc_html__( 'Insert a member\'s contact email address.', 'overlap' ),
		'type'     => 'text_medium'
	));

    $member_options->add_field( array(
        'id'   => $prefix . 'member_website',
		'name' => esc_html__( 'Website', 'overlap' ),
		'desc' => esc_html__( 'Insert a URL that applies to this member.', 'overlap' ),
		'type' => 'text_url'
	));


    $social_options = new_cmb2_box( array(
		'id'         => 'social_options',
		'title'      => esc_html__( 'Social Networks', 'overlap' ),
		'object_types'      => array('wyde_team_member'),
	) );

    $social_options->add_field( array(
        'id'          => $prefix . 'member_socials',
        'type'        => 'group',
        'name' => esc_html__( 'Social Networks', 'overlap' ),
        'description' => esc_html__('Add member\'s social networking websites.', 'overlap'),
        'options'     => array(
            'group_title'   => esc_html__( 'Website {#}', 'overlap' ),
            'add_button'    => esc_html__( 'Add Another Website', 'overlap' ),
            'remove_button' => esc_html__( 'Remove Website', 'overlap')
        ),
        'fields'    => array(
            array(
                'id'   => 'social',
                'name' => 'Website',
                'type' => 'select',
                'description' => esc_html__('Select a social networking websites.', 'overlap'),
                'options'   => $social_icons
            ),
            array(
                'id'   => 'url',
                'name' => 'URL',
                'description' => esc_html__('Insert member\'s profile URL or personal page.', 'overlap'),
                'type' => 'text_url'
            )
        )
    ));
