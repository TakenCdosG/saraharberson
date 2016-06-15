<?php
class Wyde_Widget_Flickr extends WP_Widget {

	function __construct() {
		parent::__construct(
            'w-flickr', 
            esc_html__('Wyde Flickr Stream', 'wyde-core'), 
            array(
                'classname' => 'wyde_widget_flickr', 
                'description' => esc_html__('Displays a Flickr photo stream.', 'wyde-core')
            )
        );

	}

	function widget($args, $instance) {

        extract( $args );

		$title = apply_filters('widget_title', $instance['title']);
		$flickr_id = strip_tags( $instance['flickr_id'] );
		$type = strip_tags( $instance['type'] );
		$count = $instance['count'];
		$columns =  $instance['columns'];
	
        $output = '';

        if($title) {
            $output = $before_title . esc_html( $title ) . $after_title;
        }
	
		$output .= do_shortcode(sprintf('[wyde_flickr flickr_id="%s" type="%s" count="%s" columns="%s"]', esc_attr( $flickr_id ), esc_attr( $type ), intval( $count ), intval( $columns ) ) );
		
		echo "{$before_widget}{$output}{$after_widget}";
	}

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		
        $instance['title']			= $new_instance['title'];
		$instance['flickr_id'] 		= $new_instance['flickr_id'];
		$instance['type']           = $new_instance['type'];
		$instance['count'] 			= $new_instance['count'];
		$instance['columns']        = $new_instance['columns'];
        
		return $instance;
	}

	function form( $instance ) {
		
        // Set up the default form values.
		$defaults = array(
			'title'			=> esc_html__('Flickr Stream', 'wyde-core'),
			'flickr_id'		=> '',
			'type'     => 'user',
			'count'         => 9,
			'columns'       => 3,
		);


		/* Merge the user-selected arguments with the defaults. */
		$instance = wp_parse_args( (array) $instance, $defaults );
        
        ?>
		<p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__('Title', 'wyde-core'); ?>:</label>
		    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php echo esc_html__('Type', 'wyde-core'); ?>:</label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>" class="widefat">
                <?php
                $options = array( 
                    'user' => __('User', 'wyde-core'), 
                    'group'  => __('Group', 'wyde-core'),
                );
                ?>
                <?php foreach($options as $value => $text): ?>
                <option value="<?php echo esc_attr($value); ?>"<?php echo ($instance['type'] == $value) ? ' selected="selected"':'';?>><?php echo esc_html($text); ?></option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'flickr_id' ) ); ?>"><?php echo esc_html__('Flickr ID', 'wyde-core'); ?>:</label>
		    <input id="<?php echo esc_attr( $this->get_field_id( 'flickr_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'flickr_id' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['flickr_id'] ); ?>" />
            <p><?php echo sprintf( esc_html__( 'To find your flickID visit %s.', 'wyde-core' ), '<a href="http://idgettr.com/" target="_blank">idGettr</a>' ); ?></p>
        </p>

		<p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php echo esc_html__('Number of images to show', 'wyde-core'); ?>:</label>
		    <input id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="text" value="<?php echo intval( $instance['count'] ); ?>" size="3" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"><?php echo esc_html__('Columns', 'wyde-core'); ?>:</label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>" class="widefat">
                <?php
                $options = array( 
                    2,
                    3,
                    4,
                    5,
                    6,
                    12,
                );
                ?>
                <?php foreach($options as $value): ?>
                <option value="<?php echo esc_attr($value); ?>"<?php if ($value == $instance['columns']) echo ' selected="selected"'; ?>><?php echo esc_html($value); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
<?php
	}
}
add_action('widgets_init', 'wyde_widget_flickr_load');

function wyde_widget_flickr_load()
{
	register_widget('Wyde_Widget_Flickr');
}