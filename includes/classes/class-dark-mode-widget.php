<?php
/**
 * Dark mode widget class
 *
 * Creates a widget for users to toggle
 * light and dark theme modes.
 *
 * @package    Wilbur
 * @subpackage Classes
 * @category   Widgets
 * @access     public
 * @since      1.0.0
 */

// Theme file namespace.
namespace Wilbur\Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Register the widget from the following class.
add_action( 'widgets_init', [ 'Wilbur\Classes\Dark_Mode_Widget', 'register' ] );

class Dark_Mode_Widget extends \WP_Widget {

    /**
     * Unique widget identifier
     *
     * @since 1.0.0
     * @var   string
     */
	protected $dark_mode = 'dark-mode';

	/**
     * Used by the widgets_init hook to add the widget instance.
     *
	 * @since  1.0.0
	 * @access public
     * @return object WP_Widget A new instance of the widget object.
     */
    public static function register() {
        return register_widget( 'Wilbur\Classes\Dark_Mode_Widget' );
    }

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		parent::__construct(
			$this->get_widget_slug(),
			__( 'Dark/Light Mode Toggle', 'wilbur' ),
			[
				'classname'   => $this->get_widget_slug() . '-class',
				'description' => __( 'Add a button to toggle dark and light modes of the Wilbur theme.', 'wilbur' )
			]
		);

		// Register admin styles and scripts.
		// add_action( 'admin_print_styles', [ $this, 'register_admin_styles' ] );
		// add_action( 'admin_enqueue_scripts', [ $this, 'register_admin_scripts' ] );

		// Register site styles and scripts.
		// add_action( 'wp_enqueue_scripts', [ $this, 'register_widget_styles' ] );
		// add_action( 'wp_enqueue_scripts', [ $this, 'register_widget_scripts' ] );

		// Refreshing the widget's cached output with each new post.
		add_action( 'save_post', [ $this, 'flush_widget_cache' ] );
		add_action( 'deleted_post', [ $this, 'flush_widget_cache' ] );
		add_action( 'switch_theme', [ $this, 'flush_widget_cache' ] );

	}


    /**
     * Return the widget slug.
     *
     * @since  1.0.0
     * @access public
     * @return string Returns the widget slug.
     */
    public function get_widget_slug() {
        return $this->dark_mode;
    }

	/**
	 * Outputs the content of the widget.
	 *
	 * @since  1.0.0
     * @access public
	 * @param array $args  The array of form elements.
	 * @param array $instance The current instance of the widget.
	 * @return string
	 */
	public function widget( $args, $instance ) {

		// Check if there is a cached output.
		$cache = wp_cache_get( $this->get_widget_slug(), 'widget' );

		if ( ! is_array( $cache ) ) {
			$cache = [];
		}

		if ( ! isset ( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset ( $cache[ $args['widget_id'] ] ) ) {
			return print $cache[ $args['widget_id'] ];
		}

		// go on with your widget logic, put everything into a string and …


		extract( $args, EXTR_SKIP );

		$widget_string = $before_widget;

		// TODO: Here is where you manipulate your widget's values based on their input fields
		ob_start();
		include( get_theme_file_path( '/includes/views/dark-mode-widget-frontend.php' ) );
		$widget_string .= ob_get_clean();
		$widget_string .= $after_widget;


		$cache[ $args['widget_id'] ] = $widget_string;

		wp_cache_set( $this->get_widget_slug(), $cache, 'widget' );

		print $widget_string;

	}


	public function flush_widget_cache() {
    	wp_cache_delete( $this->get_widget_slug(), 'widget' );
	}

	/**
	 * Processes the widget's options to be saved.
	 *
	 * @since  1.0.0
     * @access public
	 * @param array $new_instance The new instance of values to be generated via the update.
	 * @param array $old_instance The previous instance of values before the update.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		// TODO: Here is where you update your widget's old values with the new, incoming values

		return $instance;

	}

	/**
	 * Generates the administration form for the widget.
	 *
	 * @since  1.0.0
     * @access public
	 * @param array $instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, [ 'title' => '', 'label' => '' ] );
		$title    = $instance['title'];
		?>
		<p class="description"><?php _e( 'Add a button for users to toggle dark & light modes.' ); ?></p>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Heading:' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" placeholder="<?php _e( 'Theme Mode', 'wilbur' ); ?>" /></label></p>

		<p><label for="<?php echo $this->get_field_id( 'label' ); ?>"><?php _e( 'Label:' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'label' ); ?>" name="<?php echo $this->get_field_name( 'label' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" placeholder="<?php _e( 'Toggle lights off/on', 'wilbur' ); ?>" /></label></p>

		<p><label for="<?php echo $this->get_field_id( 'icons' ); ?>"><?php _e( 'Icons:' ); ?></label>
			<br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'icons' ); ?>" name="<?php echo $this->get_field_name( 'icons' ); ?>">
				<option value="<?php echo 'light-bulb'; ?>" selected="selected"><?php _e( 'Light Bulb', 'wilbur' ); ?></option>
				<option value="<?php echo 'sun-moon'; ?>"><?php _e( 'Sun/Moon', 'wilbur' ); ?></option>
			</select>
		</p>

		<?php

		// Display the admin form
		include( get_theme_file_path( '/includes/views/dark-mode-widget-admin.php' ) );

	}

	/**
	 * Enqueue widget admin styles
	 *
	 * @since  1.0.0
     * @access public
	 */
	public function register_admin_styles() {
		wp_enqueue_style( $this->get_widget_slug() . '-admin', get_theme_file_uri( '/assets/css/dark-mode-admin.min.css' ) );
	}

	/**
	 * Enqueue widget admin scripts
	 *
	 * @since  1.0.0
     * @access public
	 */
	public function register_admin_scripts() {
		wp_enqueue_script( $this->get_widget_slug() . '-admin', get_theme_file_uri( '/assets/js/dark-mode-admin.min.js' ), [ 'jquery' ] );
	}

	/**
	 * Enqueue widget frontend styles
	 *
	 * @since  1.0.0
     * @access public
	 */
	public function register_widget_styles() {
		wp_enqueue_style( $this->get_widget_slug() . '-widget', get_theme_file_uri( '/assets/css/dark-mode.min.css' ) );
	}

	/**
	 * Enqueue widget frontend scripts
	 *
	 * @since  1.0.0
     * @access public
	 */
	public function register_widget_scripts() {
		wp_enqueue_script( $this->get_widget_slug() . '-widget', get_theme_file_uri( '/assets/js/dark-mode.min.js' ), [ 'jquery' ] );
	}

}