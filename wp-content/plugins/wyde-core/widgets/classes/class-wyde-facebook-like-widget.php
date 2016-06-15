<?php
class Wyde_Widget_Facebook_Like extends WP_Widget {

	function __construct() {
		parent::__construct(
            'w-facebook-like', 
            esc_html__('Wyde Facebook Like', 'wyde-core'), 
            array(
                'classname' => 'wyde_widget_facebook_like', 
                'description' => esc_html__('Facebook like box.', 'wyde-core')
            )
        );

	}

	function widget($args, $instance)
	{
		extract($args);

		$title = apply_filters('widget_title', $instance['title']);
		$page_url = $instance['page_url'];
		$width = $instance['width'];
		$height = $instance['height'];
		$show_facepile = ($instance['show_facepile'] == 'true' ? 'true':'false');		
		$small_header = ($instance['small_header'] == 'true' ? 'true':'false');

		$tabs = array();

		if( isset( $instance['timeline'] ) && true == (bool) $instance['timeline'] ) $tabs[] = 'timeline';
		if( isset( $instance['events'] ) && true == (bool) $instance['events'] ) $tabs[] = 'events';
		if( isset( $instance['messages'] ) && true ==  (bool) $instance['messages'] ) $tabs[] = 'messages';
				
		if($title) {
			$title = $before_title . esc_html( $title ) . $after_title;
		}		
	
		$output = do_shortcode( sprintf('[wyde_facebook_like page_url="%s" width="%s" height="%s" show_facepile="%s" small_header="%s" tabs="%s"]',
				esc_attr($page_url),
				esc_attr($width),
				esc_attr($height),
				esc_attr($show_facepile),			
				esc_attr($small_header),
				esc_attr( implode(',', $tabs) )
			) 
		); 

		echo "{$before_widget}{$title}{$output}{$after_widget}";
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = $new_instance['title'];
		$instance['page_url'] = $new_instance['page_url'];
		$instance['width'] = $new_instance['width'];
		$instance['height'] = $new_instance['height'];
		$instance['show_facepile'] = isset( $new_instance['show_facepile'] ) ? (bool) $new_instance['show_facepile'] : false;			
		$instance['small_header'] = isset( $new_instance['small_header'] ) ? (bool) $new_instance['small_header'] : false;
		$instance['timeline'] = isset( $new_instance['timeline'] ) ? (bool) $new_instance['timeline'] : false;
		$instance['events'] = isset( $new_instance['events'] ) ? (bool) $new_instance['events'] : false;
		$instance['messages'] = isset( $new_instance['messages'] ) ? (bool) $new_instance['messages'] : false;

		return $instance;
	}

	function form($instance)
	{
		$defaults = array(
            'title' => esc_html__('Find us on Facebook', 'wyde-core'), 
            'page_url' => '', 
            'width' => '240', 
            'height' => '500', 
            'show_facepile' => true,           
            'small_header' => false, 
            'timeline' => true,
            'events' => false,
            'messages' => false,
        );

		$instance = wp_parse_args( (array) $instance, $defaults); ?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php echo esc_html__('Title', 'wyde-core'); ?>:</label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('page_url') ); ?>"><?php echo esc_html__('Facebook Page URL', 'wyde-core'); ?>:</label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id('page_url') ); ?>" name="<?php echo esc_attr( $this->get_field_name('page_url') ); ?>" value="<?php echo esc_url( $instance['page_url'] ); ?>" />
		</p>

		<div>
			<label for="<?php echo esc_attr( $this->get_field_id('width') ); ?>"><?php echo esc_html__('Width', 'wyde-core'); ?>:</label>
			<input class="widefat" type="text" style="width: 50px;" id="<?php echo esc_attr( $this->get_field_id('width') ); ?>" name="<?php echo esc_attr( $this->get_field_name('width') ); ?>" value="<?php echo esc_attr( $instance['width'] ); ?>" />
			<p><?php echo esc_html__('Set the box width, or leave it blank to use auto width.', 'wyde-core'); ?></p>
		</div>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('height') ); ?>"><?php echo esc_html__('Height', 'wyde-core'); ?>:</label>
			<input class="widefat" type="text" style="width: 50px;" id="<?php echo esc_attr( $this->get_field_id('height') ); ?>" name="<?php echo esc_attr( $this->get_field_name('height') ); ?>" value="<?php echo esc_attr( $instance['height'] ); ?>" />
		</p>	

		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_facepile']); ?> id="<?php echo esc_attr( $this->get_field_id('show_facepile') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_facepile') ); ?>" value="true" />
			<label for="<?php echo esc_attr( $this->get_field_id('show_facepile') ); ?>"><?php echo esc_html__('Show Friend\'s Faces', 'wyde-core'); ?></label>
		</p>				

		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['small_header']); ?> id="<?php echo esc_attr( $this->get_field_id('small_header') ); ?>" name="<?php echo esc_attr( $this->get_field_name('small_header') ); ?>" value="true" />
			<label for="<?php echo esc_attr( $this->get_field_id('small_header') ); ?>"><?php echo esc_html__('Small Header', 'wyde-core'); ?></label>
		</p>

		<p>
			<strong><?php echo esc_html__('Tabs', 'wyde-core'); ?></strong><br />

			<input class="checkbox" type="checkbox" <?php checked($instance['timeline']); ?> id="<?php echo esc_attr( $this->get_field_id('timeline') ); ?>" name="<?php echo esc_attr( $this->get_field_name('timeline') ); ?>" value="true" />
			<label for="<?php echo esc_attr( $this->get_field_id('timeline') ); ?>"><?php echo esc_html__('Timeline', 'wyde-core'); ?></label>
			
			<input class="checkbox" type="checkbox" <?php checked($instance['events']); ?> id="<?php echo esc_attr( $this->get_field_id('events') ); ?>" name="<?php echo esc_attr( $this->get_field_name('events') ); ?>" value="true" />
			<label for="<?php echo esc_attr( $this->get_field_id('events') ); ?>"><?php echo esc_html__('Events', 'wyde-core'); ?></label>
			
			<input class="checkbox" type="checkbox" <?php checked($instance['messages']); ?> id="<?php echo esc_attr( $this->get_field_id('messages') ); ?>" name="<?php echo esc_attr( $this->get_field_name('messages') ); ?>" value="true" />
			<label for="<?php echo esc_attr( $this->get_field_id('messages') ); ?>"><?php echo esc_html__('Messages', 'wyde-core'); ?></label>
		</p>
		
	<?php
	}
}
add_action('widgets_init', 'wyde_widget_facebook_like_load');

function wyde_widget_facebook_like_load()
{
	register_widget('Wyde_Widget_Facebook_Like');
}