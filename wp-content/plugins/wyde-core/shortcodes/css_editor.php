<?php

if ( ! class_exists( 'WPBakeryVisualComposerCssEditor' ) ) {
	/**
	 * Class WPBakeryVisualComposerCssEditor
	 */
	class WPBakeryVisualComposerCssEditor {

		/**
		 * @var array
		 */
		protected $settings = array();
		/**
		 * @var string
		 */
		protected $value = '';
		/**
		 * @var array
		 */
		protected $layers = array( 'margin', 'border', 'padding', 'content' );
		/**
		 * @var array
		 */
		protected $positions = array( 'top', 'right', 'bottom', 'left' );

		/**
		 *
		 */
		function __construct() {
		}

		/**
		 * Setters/Getters {{
		 *
		 * @param null $settings
		 *
		 * @return array
		 */
		function settings( $settings = null ) {
			if ( is_array( $settings ) ) {
				$this->settings = $settings;
			}

			return $this->settings;
		}

		/**
		 * @param $key
		 *
		 * @return string
		 */
		function setting( $key ) {
			return isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : '';
		}

		/**
		 * @param null $value
		 *
		 * @return string
		 */
		function value( $value = null ) {
			if ( is_string( $value ) ) {
				$this->value = $value;
			}

			return $this->value;
		}

		/**
		 * @param null $values
		 *
		 * @return array
		 */
		function params( $values = null ) {
			if ( is_array( $values ) ) {
				$this->params = $values;
			}

			return $this->params;
		}

		// }}
		/**
		 * vc_filter: vc_css_editor - hook to override output of this method
		 * @return mixed|void
		 */
		function render() {
			$output = '<div class="vc_css-editor vc_row" data-css-editor="true">';
			$output .= $this->onionLayout();
			$output .= '<div class="vc_col-xs-5 vc_settings">'
			           . '    <label>' . __( 'Border', 'flora' ) . '</label> '
			           . '    <div class="color-group"><input type="text" name="border_color" value="" class="vc_color-control"></div>'
			           . '    <div class="vc_border-style"><select name="border_style" class="vc_border-style">' . $this->getBorderStyleOptions() . '</select></div>'
			           . '    <label>' . __( 'Border radius', 'flora' ) . '</label> '
			           . '    <div class="vc_border-radius"><select name="border_radius" class="vc_border-radius">' . $this->getBorderRadiusOptions() . '</select></div>'
			           . '    <label>' . __( 'Box controls', 'flora' ) . '</label>'
			           . '    <label class="vc_checkbox"><input type="checkbox" name="simply" class="vc_simplify" value=""> ' . __( 'Simplify controls', 'flora' ) . '</label>'
			           . '</div>';
			$output .= '<input name="' . $this->setting( 'param_name' ) . '" class="wpb_vc_param_value  ' . $this->setting( 'param_name' ) . ' ' . $this->setting( 'type' ) . '_field" type="hidden" value="' . esc_attr( $this->value() ) . '"/>';
			$output .= '</div><div class="vc_clearfix"></div>';

			return apply_filters( 'vc_css_editor', $output );
		}

		/**
		 * @return string
		 */
		function getBorderRadiusOptions() {
			$radiuses = apply_filters( 'vc_css_editor_border_radius_options_data', array(
				'' => __( 'None', 'flora' ),
				'1px' => '1px',
				'2px' => '2px',
				'3px' => '3px',
				'4px' => '4px',
				'5px' => '5px',
				'10px' => '10px',
				'15px' => '15px',
				'20px' => '20px',
				'25px' => '25px',
				'30px' => '30px',
				'35px' => '35px',
			) );

			$output = '';
			foreach ( $radiuses as $radius => $title ) {
				$output .= '<option value="' . $radius . '">' . $title . '</option>';
			}

			return $output;
		}

		/**
		 * @return string
		 */
		function getBorderStyleOptions() {
			$output = '<option value="">' . __( 'Theme defaults', 'flora' ) . '</option>';
			$styles = apply_filters( 'vc_css_editor_border_style_options_data', array(
				'Solid',
				'Dotted',
				'Dashed',
				'None',
				'Hidden',
				'Double',
				'Groove',
				'Ridge',
				'Inset',
				'Outset',
				'Initial',
				'Inherit',
			) );
			foreach ( $styles as $style ) {
				$output .= '<option value="' . $style . '">' . ucfirst( $style ) . '</option>';
			}

			return $output;
		}

		
		/**
		 * @return string
		 */
		function onionLayout() {
			$output = '<div class="vc_layout-onion vc_col-xs-7">'
			          . '    <div class="vc_margin">' . $this->layerControls( 'margin' )
			          . '      <div class="vc_border">' . $this->layerControls( 'border', 'width' )
			          . '          <div class="vc_padding">' . $this->layerControls( 'padding' )
			          . '              <div class="vc_content"><i></i></div>'
			          . '          </div>'
			          . '      </div>'
			          . '    </div>'
			          . '</div>';

			return apply_filters( 'vc_css_editor_onion_layout', $output );
		}

		/**
		 * @param $name
		 * @param string $prefix
		 *
		 * @return string
		 */
		protected function layerControls( $name, $prefix = '' ) {
			$output = '<label>' .  $name . '</label>';
			foreach ( $this->positions as $pos ) {
				$output .= '<input type="text" name="' . $name . '_' . $pos . ( $prefix != '' ? '_' . $prefix : '' ) . '" data-name="' . $name . ( $prefix != '' ? '-' . $prefix : '' ) . '-' . $pos . '" class="vc_' . $pos . '" placeholder="-" data-attribute="' . $name . '" value="">';
			}

			return apply_filters( 'vc_css_editor_layer_controls', $output );
		}
	}
}