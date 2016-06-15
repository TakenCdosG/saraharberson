<?php
class Wyde_Widget_Popular_Posts extends WP_Widget {

	function __construct() {
		parent::__construct(
            'w-popular-posts', 
            esc_html__('Wyde Popular Posts', 'wyde-core'), 
            array(
                'classname' => 'wyde_widget_popular_posts', 
                'description' => esc_html__('Displays the popular posts with thumbnails.', 'wyde-core')
            )
        );

	}

	function widget($args, $instance) {

		extract($args);

		$title = apply_filters('widget_title', $instance['title']);

		$count = ( ! empty( $instance['count'] ) ) ? intval( $instance['count'] ) : 5;
		if ( ! $count ) $count = 5;

		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;		
		
		echo wp_kses_post($before_widget);
		
		if($title) {
			echo wp_kses_post( $before_title ). esc_html( $title ) .wp_kses_post( $after_title );
		}

		echo do_shortcode(sprintf('[wyde_popular_posts count="%s" show_date="%s"]', esc_attr( $count ), esc_attr( $show_date ) ) );
        
		echo wp_kses_post($after_widget);		

	}

	function update( $new_instance, $old_instance ) {
		
        $instance = $old_instance;
		
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['count'] = intval( $new_instance['count'] );
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;

		return $instance;
	}


	function form( $instance ) {

        $defaults = array(
            'title' => esc_html__('Popular Posts', 'wyde-core'), 
            'count' => 5, 
            'show_date' => true
        );

		$instance = wp_parse_args((array) $instance, $defaults); 
        
        ?>


		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__('Title', 'wyde-core'); ?>:</label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo sanitize_text_field( $instance['title'] ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php echo esc_html__('Number of posts to show', 'wyde-core'); ?>:</label>
		<input id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['count'] ); ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $instance['show_date']); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" />
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php echo esc_html__('Post date.', 'wyde-core'); ?></label></p>
<?php
	}
}

add_action('widgets_init', 'wyde_widget_popular_posts_load');

function wyde_widget_popular_posts_load()
{
	register_widget('Wyde_Widget_Popular_Posts');
}