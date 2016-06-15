<?php
/* Tabs
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_Tabs extends WPBakeryShortCode {
	static $filter_added = false;
	protected $controls_css_settings = 'out-tc vc_controls-content-widget';
	protected $controls_list = array('edit', 'clone', 'delete');
	public function __construct( $settings ) {
		parent::__construct( $settings );
		// WPBakeryVisualComposer::getInstance()->addShortCode( array( 'base' => 'wyde_tab' ) );
		if ( ! self::$filter_added ) {
			$this->addFilter( 'vc_inline_template_content', 'setCustomTabId' );
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

		// Extract tab titles

		preg_match_all( '/wyde_tab title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $content, $matches, PREG_OFFSET_CAPTURE );
		/*
        $tab_titles = array();
        if ( isset($matches[1]) ) { $tab_titles = $matches[1]; }
        */
		$output = '';
		$tab_titles = array();

		if ( isset( $matches[0] ) ) {
			$tab_titles = $matches[0];
		}
		$tmp = '';
		if ( count( $tab_titles ) ) {
			$tmp .= '<ul class="clearfix tabs_controls">';
			foreach ( $tab_titles as $tab ) {
				preg_match( '/title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE );
				if ( isset( $tab_matches[1][0] ) ) {
					$tmp .= '<li><a href="#tab-' . ( isset( $tab_matches[3][0] ) ? $tab_matches[3][0] : sanitize_title( $tab_matches[1][0] ) ) . '">' . $tab_matches[1][0] . '</a></li>';

				}
			}
			$tmp .= '</ul>' . "\n";
		} else {
			$output .= do_shortcode( $content );
		}


		/*
        if ( count($tab_titles) ) {
	        $tmp .= '<ul class="clearfix">';
	        foreach ( $tab_titles as $tab ) {
		        $tmp .= '<li><a href="#tab-'. sanitize_title( $tab[0] ) .'">' . $tab[0] . '</a></li>';
	        }
	        $tmp .= '</ul>';
        } else {
	        $output .= do_shortcode( $content );
        }
        */
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
		return '<div class="wpb_template">' . do_shortcode( '[wyde_tab title="Tab" tab_id=""][/wyde_tab]' ) . '</div>';
	}

	public function setCustomTabId( $content ) {
		return preg_replace( '/tab\_id\=\"([^\"]+)\"/', 'tab_id="$1-' . time() . '"', $content );
	}
}

$tab_id_1 = 'def' . time() . '-1-' . rand( 0, 100 );
$tab_id_2 = 'def' . time() . '-2-' . rand( 0, 100 );
vc_map( array(
	"name" => __( 'Tabs Icon', 'wyde-core' ),
	'base' => 'wyde_tabs',
	'show_settings_on_create' => false,
	'is_container' => true,
	'icon' => 'wyde-icon tabs-icon',
    'weight'    => 900,
	'category' => __('Wyde', 'wyde-core'),
	'description' => __( 'Tabbed content with navigation icon', 'wyde-core' ),
	'params' => array(
		array(
			'param_name' => 'interval',
			'type' => 'dropdown',
			'heading' => __( 'Auto rotate tabs', 'wyde-core' ),			
			'value' => array( __( 'Disable', 'wyde-core' ) => 0, 3, 5, 10, 15 ),
			'std' => 0,
			'description' => __( 'Auto rotate tabs each X seconds.', 'wyde-core' )
		),
		array(
			'param_name' => 'el_class',
			'type' => 'textfield',
			'heading' => __( 'Extra CSS Class', 'wyde-core' ),			
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
		),
	),
	'custom_markup' => '<div class="wpb_tabs_holder wpb_holder vc_container_for_children"><ul class="tabs_controls"></ul>%content%</div>',
	'default_content' => '[wyde_tab title="' . __( 'Tab 1', 'wyde-core' ) . '" tab_id="' . $tab_id_1 . '"][/wyde_tab][wyde_tab title="' . __( 'Tab 2', 'wyde-core' ) . '" tab_id="' . $tab_id_2 . '"][/wyde_tab]',
	'js_view' => 'WydeTabsView'
) );