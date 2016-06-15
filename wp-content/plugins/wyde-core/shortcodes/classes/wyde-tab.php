<?php

/* Tab Section
---------------------------------------------------------- */
require_once( vc_path_dir( 'SHORTCODES_DIR', 'vc-column.php' ) );

class WPBakeryShortCode_Wyde_Tab extends WPBakeryShortCode_VC_Column {
	protected $controls_css_settings = 'tc vc_control-container';
	protected $controls_list = array( 'add', 'edit', 'clone', 'delete' );
	protected $predefined_atts = array(
		'tab_id' => "Tab",
		'title' => ''
	);
	protected $controls_template_file = 'editors/partials/backend_controls_tab.tpl.php';

	public function __construct( $settings ) {
		parent::__construct( $settings );
	}

	public function customAdminBlockParams() {
		return ' id="tab-' . $this->atts['tab_id'] . '"';
	}

	public function mainHtmlBlockParams( $width, $i ) {
		return 'data-element_type="' . $this->settings["base"] . '" class="wpb_' . $this->settings['base'] . ' wpb_sortable wpb_content_holder"' . $this->customAdminBlockParams();
	}

	public function containerHtmlBlockParams( $width, $i ) {
		return 'class="wpb_column_container vc_container_for_children"';
	}

	public function getColumnControls( $controls, $extended_css = '' ) {
		return $this->getColumnControlsModular( $extended_css );
	}
}

$icon_picker_options = array();
$icon_picker_options = apply_filters('wyde_iconpicker_options', $icon_picker_options);

vc_map( array(
	'name' => __( 'Tab', 'wyde-core' ),
	'base' => 'wyde_tab',
	'allowed_container_element' => 'vc_row',
	'is_container' => true,
	'content_element' => false,
	'params' => array(
        $icon_picker_options[0],
        $icon_picker_options[1],
        $icon_picker_options[2],
        $icon_picker_options[3],
        $icon_picker_options[4],
        $icon_picker_options[5],
        array(
        	'param_name' => 'title',
		    'type' => 'textfield',
		    'heading' => __( 'Title', 'wyde-core' ),			    
		    'description' => __( 'Tab title.', 'wyde-core' ),
	    ),
	    array(
	    	'param_name' => "tab_id",
		    'type' => 'tab_id',
		    'heading' => __( 'Tab ID', 'wyde-core' ),			   
	    ),
	),
	'js_view' => 'WydeTabView'
) );