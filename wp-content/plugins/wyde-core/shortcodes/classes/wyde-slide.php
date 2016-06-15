<?php

/* Slide
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Slide extends WPBakeryShortCode {

	public function singleParamHtmlHolder( $param, $value ) {
		$output = '';
		// Compatibility fixes
		$old_names = array(
			'yellow_message',
			'blue_message',
			'green_message',
			'button_green',
			'button_grey',
			'button_yellow',
			'button_blue',
			'button_red',
			'button_orange'
		);
		$new_names = array(
			'alert-block',
			'alert-info',
			'alert-success',
			'btn-success',
			'btn',
			'btn-info',
			'btn-primary',
			'btn-danger',
			'btn-warning'
		);
		$value = str_ireplace( $old_names, $new_names, $value );

		$param_name = isset( $param['param_name'] ) ? $param['param_name'] : '';
		$type = isset( $param['type'] ) ? $param['type'] : '';
		$class = isset( $param['class'] ) ? $param['class'] : '';

		if ( 'attach_image' === $param['type'] && 'image' === $param_name ) {
			$output .= '<input type="hidden" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '" />';
			$element_icon = $this->settings( 'icon' );
			$img = wpb_getImageBySize( array(
				'attach_id' => (int) preg_replace( '/[^\d]/', '', $value ),
				'thumb_size' => 'thumbnail'
			) );
			$this->setSettings( 'logo', ( $img ? $img['thumbnail'] : '<img width="150" height="150" src="' . vc_asset_url( 'vc/blank.gif' ) . '" class="attachment-thumbnail vc_general vc_element-icon"  data-name="' . $param_name . '" alt="" title="" style="display: none;" />' ) . '<span class="no_image_image vc_element-icon' . ( ! empty( $element_icon ) ? ' ' . $element_icon : '' ) . ( $img && ! empty( $img['p_img_large'][0] ) ? ' image-exists' : '' ) . '" />' );
			$output .= $this->outputTitleTrue( $this->settings['name'] );
		} elseif ( ! empty( $param['holder'] ) ) {
			if ( $param['holder'] === 'input' ) {
				$output .= '<' . $param['holder'] . ' readonly="true" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '">';
			} elseif ( in_array( $param['holder'], array( 'img', 'iframe' ) ) ) {
				$output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" src="' . $value . '">';
			} elseif ( $param['holder'] !== 'hidden' ) {
				$output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '">' . $value . '</' . $param['holder'] . '>';
			}
		}

		if ( ! empty( $param['admin_label'] ) && $param['admin_label'] === true ) {
			$output .= '<span class="vc_admin_label admin_label_' . $param['param_name'] . ( empty( $value ) ? ' hidden-label' : '' ) . '"><label>' . $param['heading'] . '</label>: ' . $value . '</span>';
		}

		return $output;
	}

	public function getImageSquareSize( $img_id, $img_size ) {
		if ( preg_match_all( '/(\d+)x(\d+)/', $img_size, $sizes ) ) {
			$exact_size = array(
				'width' => isset( $sizes[1][0] ) ? $sizes[1][0] : '0',
				'height' => isset( $sizes[2][0] ) ? $sizes[2][0] : '0',
			);
		} else {
			$image_downsize = image_downsize( $img_id, $img_size );
			$exact_size = array(
				'width' => $image_downsize[1],
				'height' => $image_downsize[2],
			);
		}
		if ( isset( $exact_size['width'] ) && (int) $exact_size['width'] !== (int) $exact_size['height'] ) {
			$img_size = (int) $exact_size['width'] > (int) $exact_size['height']
				? $exact_size['height'] . 'x' . $exact_size['height']
				: $exact_size['width'] . 'x' . $exact_size['width'];
		}

		return $img_size;
	}

	protected function outputTitle( $title ) {
		return '';
	}

	protected function outputTitleTrue( $title ) {
		return '<h4 class="wpb_element_title">' . $title . ' ' . $this->settings( 'logo' ) . '</h4>';
	}
}

vc_map( array(
	'name' => __( 'Slide', 'wyde-core' ),
	'base' => 'wyde_slide',
	'icon' => 'wyde-icon slide-icon',
	'content_element' => false,
	'params' => array(
	    array(
	    	'param_name' => "slide_id",
		    'type' => 'tab_id',
		    'heading' => __( 'Slide ID', 'wyde-core' ),			    
	    ),
	    array(
	    	'param_name' => 'title',
		    'type' => 'textfield',
		    'heading' => __( 'Title', 'wyde-core' ),			    
		    'description' => __( 'Slide heading.', 'wyde-core' ),
	    ),
	    array(
	    	'param_name' => 'subtitle',
		    'type' => 'textfield',
		    'heading' => __( 'Subtitle', 'wyde-core' ),			    
		    'description' => __( 'Slide subheading.', 'wyde-core' ),
	    ),
        array(
        	'param_name' => 'image',
            'type' => 'attach_image',
            'heading' => __( 'Image', 'wyde-core' ),
            'description' => __( 'Select image from media library.', 'wyde-core' )
        ),
        array(
        	'param_name' => 'image_size',
	        'type' => 'dropdown',
	        'heading' => __( 'Image Size', 'wyde-core' ),	
	        'admin_label' => true, 	        
	        'value' => array(
	            __('Medium', 'wyde-core' ) => 'medium',
	            __('Large', 'wyde-core' ) => 'large',
                __('Original', 'wyde-core') => 'full',
	        ),
	        'description' => __( 'Select image size.', 'wyde-core' ),
	        'std'	=> 'full',
        ),
		array(
			'param_name' => 'content',
			'type' => 'textarea_html',
		),
		array(
			'param_name' => 'el_class',
			'type' => 'textfield',
			'heading' => __( 'Extra CSS Class', 'wyde-core' ),				
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
		),
		array(
			'param_name' => 'css',
			'type' => 'css_editor',
			'heading' => __( 'CSS', 'wyde-core' ),				
			'group' => __( 'Design Options', 'wyde-core' )
		),

	),
	'js_view' => 'WydeSlideView'
) );