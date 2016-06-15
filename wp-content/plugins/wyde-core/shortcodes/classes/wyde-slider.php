<?php
/* Slider
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Slider extends WPBakeryShortCode {
	static $filter_added = false;
	protected $controls_css_settings = 'out-tc vc_controls-content-widget';
	protected $controls_list = array('edit', 'clone', 'delete');
	public function __construct( $settings ) {
		parent::__construct( $settings );
		// WPBakeryVisualComposer::getInstance()->addShortCode( array( 'base' => 'wyde_content' ) );
		if ( ! self::$filter_added ) {
			$this->addFilter( 'vc_inline_template_content', 'setCustomSlideId' );
			self::$filter_added = true;
		}
	}

	public function contentAdmin( $atts, $content = null ) {
		$width = $custom_markup = '';
		$shortcode_attributes = array( 'width' => '1/1' );
		foreach ( $this->settings['params'] as $param ) {
			if ( $param['param_name'] != 'content' ) {
				//$shortcode_attributes[$param['param_name']] = $param['value'];
				if ( isset( $param['value'] ) && is_string( $param['value'] ) ) {
					$shortcode_attributes[ $param['param_name'] ] = $param['value'];
				} elseif ( isset( $param['value'] ) ) {
					$shortcode_attributes[ $param['param_name'] ] = $param['value'];
				}
			} else if ( $param['param_name'] == 'content' && $content == null ) {
				//$content = $param['value'];
				$content = $param['value'];
			}
		}
		extract( shortcode_atts(
			$shortcode_attributes
			, $atts ) );

		// Extract slide titles

		preg_match_all( '/wyde_slide title="([^\"]+)"(\sslide_id\=\"([^\"]+)\"){0,1}/i', $content, $matches, PREG_OFFSET_CAPTURE );
		/*
        $slide_titles = array();
        if ( isset($matches[1]) ) { $slide_titles = $matches[1]; }
        */
		$output = '';
		$slide_titles = array();

		if ( isset( $matches[0] ) ) {
			$slide_titles = $matches[0];
		}
		$tmp = '';
		if ( count( $slide_titles ) ) {
			$tmp .= '<ul class="clearfix tabs_controls">';
			foreach ( $slide_titles as $slide ) {
				preg_match( '/title="([^\"]+)"(\sitem_id\=\"([^\"]+)\"){0,1}/i', $slide[0], $slide_matches, PREG_OFFSET_CAPTURE );
				if ( isset( $slide_matches[1][0] ) ) {
					$tmp .= '<li><a href="#item-' . ( isset( $slide_matches[3][0] ) ? $slide_matches[3][0] : sanitize_title( $slide_matches[1][0] ) ) . '">' . $slide_matches[1][0] . '</a></li>';

				}
			}
			$tmp .= '</ul>' . "\n";
		} else {
			$output .= do_shortcode( $content );
		}


		$elem = $this->getElementHolder( $width );

		$iner = '';
		foreach ( $this->settings['params'] as $param ) {
			$custom_markup = '';
			$param_value = isset( $$param['param_name'] ) ? $$param['param_name'] : '';
			if ( is_array( $param_value ) ) {
				// Get first element from the array
				reset( $param_value );
				$first_key = key( $param_value );
				$param_value = $param_value[$first_key];
			}
			$iner .= $this->singleParamHtmlHolder( $param, $param_value );
		}
		//$elem = str_ireplace('%wpb_element_content%', $iner, $elem);

		if ( isset( $this->settings["custom_markup"] ) && $this->settings["custom_markup"] != '' ) {
			if ( $content != '' ) {
				$custom_markup = str_ireplace( "%content%", $tmp . $content, $this->settings["custom_markup"] );
			} else if ( $content == '' && isset( $this->settings["default_content_in_template"] ) && $this->settings["default_content_in_template"] != '' ) {
				$custom_markup = str_ireplace( "%content%", $this->settings["default_content_in_template"], $this->settings["custom_markup"] );
			} else {
				$custom_markup = str_ireplace( "%content%", '', $this->settings["custom_markup"] );
			}
			//$output .= do_shortcode($this->settings["custom_markup"]);
			$iner .= do_shortcode( $custom_markup );
		}
		$elem = str_ireplace( '%wpb_element_content%', $iner, $elem );
		$output = $elem;

		return $output;
	}

	public function getTabTemplate() {
		return '<div class="wpb_template">' . do_shortcode( '[wyde_slide title="Slide" slide_id=""][/wyde_slide]' ) . '</div>';
	}

	public function setCustomSlideId( $content ) {
		return preg_replace( '/slide\_id\=\"([^\"]+)\"/', 'slide_id="$1-' . time() . '"', $content );
	}
}

$slide_id_1 = 'def' . time() . '-1-' . rand( 0, 100 );
$slide_id_2 = 'def' . time() . '-2-' . rand( 0, 100 );
vc_map( array(
	"name" => __( 'Slider', 'wyde-core' ),
	'base' => 'wyde_slider',
	'show_settings_on_create' => false,
	'is_container' => true,
	'wrapper_class' => 'vc_clearfix',
	'icon' => 'wyde-icon slider-icon',
    'weight'    => 900,
	'category' => __('Wyde', 'wyde-core'),
	'description' => __( 'Content slider', 'wyde-core' ),
	'params' => array(
        array(
            'param_name' => 'visible_items',
            'type' => 'dropdown',
            'heading' => __('Visible Items', 'wyde-core'),                
            'value' => array('1', '2', '3', '4', '5'),
            'std' => '3',
            'description' => __('The maximum amount of items displayed at a time.', 'wyde-core'),
        ),
        array(
            'param_name' => 'transition',
            'type' => 'dropdown',
            'heading' => __('Transition', 'wyde-core'),                
            'value' => array(
                __('Slide', 'wyde-core') => '', 
                __('Fade', 'wyde-core') => 'fade', 
            ),
            'description' => __('Select animation type.', 'wyde-core'),
            'dependency' => array(
			    'element' => 'visible_items',
			    'value' => array('1')
		    )
        ),
        array(
            'param_name' => 'show_navigation',
            'type' => 'checkbox',
            'heading' => __('Show Navigation', 'wyde-core'),                
            'description' => __('Display "next" and "prev" buttons.', 'wyde-core'),
        ),
        array(
            'param_name' => 'show_pagination',
            'type' => 'checkbox',
            'heading' => __('Show Pagination', 'wyde-core'),                
            'description' => __('Show pagination.', 'wyde-core'),
        ),
        array(
            'param_name' => 'slide_loop',
            'type' => 'checkbox',
            'heading' =>  __('Loop', 'wyde-core'),                
            'description' => __('Inifnity loop. Duplicate last and first items to get loop illusion.', 'wyde-core'),
        ),
        array(
            'param_name' => 'auto_play',
            'type' => 'checkbox',             
            'heading' => __('Auto Play', 'wyde-core'),                
            'description' => __('Auto play slide.', 'wyde-core'),
        ),
        array(
        	'param_name' => 'el_class',
	        'type' => 'textfield',
	        'heading' => __( 'Extra CSS Class', 'wyde-core' ),	        
	        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
        ),
	),
	'custom_markup' => '<div class="wpb_tabs_holder wpb_holder vc_container_for_children"><ul class="tabs_controls"></ul>%content%</div>',
	'default_content' => '[wyde_slide title="' . __( 'Slide 1', 'wyde-core' ) . '" slide_id="' . $slide_id_1 . '"][/wyde_slide][wyde_slide title="' . __( 'Slide 2', 'wyde-core' ) . '" slide_id="' . $slide_id_2 . '"][/wyde_slide]',
	'js_view' => 'WydeSliderView',
) );