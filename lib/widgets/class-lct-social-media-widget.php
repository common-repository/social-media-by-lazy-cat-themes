<?php
/**
 * Lazy Cat Themes Social Media Plugin : Social Media Widget
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */

/**
 * Provides a widget for users to include social media icons wherever they like within Wordpress and configure the options
 * using an Admin GUI
 */
class LCT_Social_Media_Widget extends WP_Widget {

	/**
	 * Construct widget by calling parent constructor and set defaults
	 */
	function __construct() {

		/// Register ID, name and description with base class
		parent::__construct(
			'LCT_Social_Media_Widget',
			__( 'LCT - Social Media Networking', 'lazy-cat-generic' ),
			array( 'description' => __( 'Lazy Cat Themes widget to add social media links to your site.', 'lazy-cat-generic' ) )
		);

		//Default all the instance properties
		$this->defaults['title']      = '';
		$this->defaults['alignment']  = 'center';
		$this->defaults['size']       = 'large';
		$this->defaults['show_count'] = false;

		add_action( 'sidebar_admin_setup', array( $this, 'admin_setup' ) );

	}

	/**
	 * Used to properly queue admin scripts
	 */
	public function admin_setup() {
		wp_enqueue_script(
			'lct-social-media-widget',
			plugin_dir_url( __FILE__ ) . 'class-lct-social-media-widget.js',
			array(),
			'1.0.0' );
	}

	/**
	 *
	 * Front-end display
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $args['before_widget'];

		$title = apply_filters( 'widget_title', $instance['title'] );
		if ( $title ) {
			echo "<h4 class='widget-title widgettitle'>$title</h4>";
		}

		//build text align classes and reference these here
		LCT_Social_Stats_Api::show_social_data_html(
			null,
			array(
				'alignment'  => $instance['alignment'],
				'size'       => $instance['size'],
				'show_count' => $instance['show_count']
			) );

		echo $args['after_widget'];

	}


	/**
	 *  Back-end widget form.
	 *
	 * @param array $instance
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$title      = $instance['title'];
		$alignment  = $instance['alignment'];
		$size       = $instance['size'];
		$show_count = $instance['show_count'];

		?>
		<p>
			<label
				for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'lazy-cat-generic' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
			       value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label
				for="<?php echo $this->get_field_id( 'alignment' ); ?>"><?php _e( 'Alignment:', 'lct-social-media' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'alignment' ); ?>"
			        name="<?php echo $this->get_field_name( 'alignment' ); ?>">
				<option
					value="left" <?php echo ( 'left' === $alignment ) ? 'selected' : ''; ?>><?php _e( 'Left', 'lct-social-media' ); ?></option>
				<option
					value="center" <?php echo ( 'center' === $alignment ) ? 'selected' : ''; ?>><?php _e( 'Center', 'lct-social-media' ); ?></option>
				<option
					value="right" <?php echo ( 'right' === $alignment ) ? 'selected' : ''; ?>><?php _e( 'Right', 'lct-social-media' ); ?></option>
			</select>
		<p>
		<p>
			<label
				for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Size:', 'lct-social-media' ); ?></label>
			<select class="widefat social-bar-size" id="<?php echo $this->get_field_id( 'size' ); ?>"
			        name="<?php echo $this->get_field_name( 'size' ); ?>">
				<option
					value="small" <?php echo ( 'small' === $size ) ? 'selected' : ''; ?>><?php _e( 'Small', 'lct-social-media' ); ?></option>
				<option
					value="large" <?php echo ( 'large' === $size ) ? 'selected' : ''; ?>><?php _e( 'Large', 'lct-social-media' ); ?></option>
			</select>
		<p>
		<p>
			<input type="checkbox" class="show_follower_count <?php echo ( 'small' === $size ) ? 'hidden' : ''; ?>" <?php echo ( 'small' === $size ) ? 'class="hidden"' : ''; ?> id="<?php echo $this->get_field_id( 'show_count' ); ?>"
			       name="<?php echo $this->get_field_name( 'show_count' ); ?>"
			       value="1" <?php echo $show_count ? 'checked' : ''; ?>/>
			<label  class="show_follower_count <?php echo ( 'small' === $size ) ? 'hidden' : ''; ?>"
				for="<?php echo $this->get_field_id( 'show_count' ); ?>"><?php _e( 'Show Follower Count', 'lazy-cat-generic' ); ?></label>
		<p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance               = array();
		$instance['title']      = ( ! empty ( $new_instance['title'] ) ) ? $new_instance['title'] : '';
		$instance['alignment']  = ( ! empty ( $new_instance['alignment'] ) ) ? $new_instance['alignment'] : '';
		$instance['size']       = ( ! empty ( $new_instance['size'] ) ) ? $new_instance['size'] : '';
		$instance['show_count'] = ( ! empty ( $new_instance['show_count'] ) ) ? $new_instance['show_count'] : '';
		return $instance;
	}

}