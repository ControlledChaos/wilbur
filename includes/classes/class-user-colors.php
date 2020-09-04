<?php
/**
 * User colors class
 *
 * Creates a widget for users to toggle
 * light and dark theme modes.
 *
 * @package    Hindsight
 * @subpackage Classes
 * @category   Users
 * @access     public
 * @since      1.0.0
 */

// Theme file namespace.
namespace Hindsight\Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) { exit; }

class User_Colors {

	/**
	 * Instance of the class
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object Returns the instance.
	 */
	public static function instance() {

		// Varialbe for the instance to be used outside the class.
		static $instance = null;

		if ( is_null( $instance ) ) {

			// Set variable for new instance.
			$instance = new self;
		}

		// Return the instance.
		return $instance;
	}

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access private
	 * @return self
	 */
	public function __construct() {

		remove_action( 'admin_init', 'register_admin_color_schemes', 1 );

		// Add the color palettes.
		add_action( 'admin_init' , [ $this, 'add_palettes' ], 1 );

		remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
		add_action( 'admin_color_scheme_picker', [ $this, 'admin_color_scheme_picker' ] );

		add_action( 'admin_enqueue_scripts', [ $this, 'tooltips' ] );

	}

	/**
	 * Enqueue tooltips
	 *
	 * Used for easy color code identification in the user color schemes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed[] Returns color functions with
	 *                 arrays of color palettes.
	 */
	public function tooltips() {

		// Enqueue the Tooltip UI script.
		wp_enqueue_script( 'jquery-ui-tooltip', '', [], true );

		// Add tooltips to the color items.
		wp_add_inline_script(
			'jquery-ui-tooltip',
			'jQuery(document).ready(function($){
				$( ".hindsight-color-picker-tooltip" ).tooltip({
					position : { my: "left bottom-5", at: "left top", collision: "flipfit" },
				});
			});',
			true
		);
	}

	/**
	 * Register color palettes
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed[] Returns color functions with
	 *                 arrays of color palettes.
	 */
	public function add_palettes() {

		// Set up the right-to-left language type suffix.
		if ( is_rtl() ) {
			$suffix = '-rtl';
		} else {
			$suffix = '';
		}

		wp_admin_css_color( 'hindsight_light', _x( 'Custom Light', 'admin color scheme' ),
			get_theme_file_uri( "/assets/css/colors/light/colors$suffix.min.css" ),
			[ '#ffffff', '#fefcf7', '#d7d2c5', '#716d64' ],
			[ 'base' => '#999999', 'focus' => '#cccccc', 'current' => '#cccccc' ]
		);

		wp_admin_css_color( 'hindsight_dark', _x( 'Custom Dark', 'admin color scheme' ),
			get_theme_file_uri( "/assets/css/colors/light/colors$suffix.min.css" ),
			[ '#222222', '#fefcf7', '#d7d2c5', '#716d64' ],
			[ 'base' => '#999999', 'focus' => '#cccccc', 'current' => '#cccccc' ]
		);

		wp_admin_css_color( 'hindsight_pink', _x( 'Pink', 'admin color scheme' ),
			get_theme_file_uri( "/assets/css/colors/pink/colors$suffix.min.css" ),
			[ '#f0eddb', '#d7d2c5', '#cd2653', '#5f1b29' ],
			[ 'base' => '#999999', 'focus' => '#cccccc', 'current' => '#cccccc' ]
		);

		wp_admin_css_color( 'hindsight_blue', _x( 'Blue', 'admin color scheme' ),
			get_theme_file_uri( "/assets/css/colors/blue/colors$suffix.min.css" ),
			[ '#eeeeee', '#bed2db', '#0073aa', '#074460' ],
			[ 'base' => '#999999', 'focus' => '#cccccc', 'current' => '#cccccc' ]
		);

		wp_admin_css_color( 'hindsight_violet', _x( 'Violet', 'admin color scheme' ),
			get_theme_file_uri( "/assets/css/colors/violet/colors$suffix.min.css" ),
			[ '#eeeeee', '#d0c4db', '#674d7e', '#433251' ],
			[ 'base' => '#999999', 'focus' => '#cccccc', 'current' => '#cccccc' ]
		);

		wp_admin_css_color( 'hindsight_dark_pink', _x( 'Dark Pink', 'admin color scheme' ),
			get_theme_file_uri( "/assets/css/colors/dark-pink/colors$suffix.min.css" ),
			[ '#222222', '#ffffff', '#cd2653', '#5f1b29' ],
			[ 'base' => '#999999', 'focus' => '#cccccc', 'current' => '#cccccc' ]
		);

		wp_admin_css_color( 'hindsight_dark_blue', _x( 'Dark Blue', 'admin color scheme' ),
			get_theme_file_uri( "/assets/css/colors/dark-blue/colors$suffix.min.css" ),
			[ '#222222', '#ffffff', '#0073aa', '#074460' ],
			[ 'base' => '#999999', 'focus' => '#cccccc', 'current' => '#cccccc' ]
		);

		wp_admin_css_color( 'hindsight_dark_violet', _x( 'Dark Violet', 'admin color scheme' ),
			get_theme_file_uri( "/assets/css/colors/dark-violet/colors$suffix.min.css" ),
			[ '#222222', '#ffffff', '#674d7e', '#433251' ],
			[ 'base' => '#999999', 'focus' => '#cccccc', 'current' => '#cccccc' ]
		);
	}

/**
 * Display the default admin color scheme picker (Used in user-edit.php)
 *
 * @since 3.0.0
 *
 * @global array $_wp_admin_css_colors
 *
 * @param int $user_id User ID.
 */
function admin_color_scheme_picker( $user_id ) {

	global $_wp_admin_css_colors;

	ksort( $_wp_admin_css_colors );

	if ( isset( $_wp_admin_css_colors['hindsight_light'] ) ) {
		// Set Default ('fresh') and Light should go first.
		$_wp_admin_css_colors = array_filter(
			array_merge(
				array(
					'hindsight_pink' => '',
					'hindsight_blue' => '',
					'hindsight_violet' => '',
					'hindsight_dark_pink' => '',
					'hindsight_dark_blue' => '',
					'hindsight_dark_violet' => '',
					'hindsight_light' => '',
					'hindsight_dark' => ''
				),
				$_wp_admin_css_colors
			)
		);
	}

	$current_color = get_user_option( 'admin_color', $user_id );

	if ( empty( $current_color ) || ! isset( $_wp_admin_css_colors[ $current_color ] ) ) {
		$current_color = 'hindsight_light';
	}

	?>
	<div class="hindsight-color-picker">
		<div class="hindsight-color-message">
			<p><?php _e( 'Select from the following admin color schemes which compliment the aesthetics of the website. The Pink, Blue, and Violet color schemes are derived from frontend color scheme options. The Custom Light and Custom Dark options use custom colors applied via the Customizer to the front end of the website.' ); ?></p>
		</div>
		<fieldset id="color-picker" class="scheme-list">
			<legend class="screen-reader-text"><span><?php _e( 'User Color Palette' ); ?></span></legend>
			<div class="user-color-options">
			<?php
			wp_nonce_field( 'save-color-scheme', 'color-nonce', false );
			foreach ( $_wp_admin_css_colors as $color => $color_info ) :

				?>
				<div class="color-option <?php echo ( $color == $current_color ) ? 'selected' : ''; ?>">
					<input name="admin_color" id="admin_color_<?php echo esc_attr( $color ); ?>" type="radio" value="<?php echo esc_attr( $color ); ?>" class="tog" <?php checked( $color, $current_color ); ?> />
					<input type="hidden" class="css_url" value="<?php echo esc_url( $color_info->url ); ?>" />
					<input type="hidden" class="icon_colors" value="<?php echo esc_attr( wp_json_encode( array( 'icons' => $color_info->icon_colors ) ) ); ?>" />
					<label for="admin_color_<?php echo esc_attr( $color ); ?>"><?php echo esc_html( $color_info->name ); ?></label>

					<ul class="color-palette">
					<?php

					foreach ( $color_info->colors as $html_color ) {
						?>
						<li class="hindsight-color-picker-entry hindsight-color-picker-tooltip" style="background-color: <?php echo esc_attr( $html_color ); ?>" title="<?php echo esc_attr( __( 'Color hex code: ' ) . $html_color ); ?>"><span class="screen-reader-text"><?php echo esc_attr( __( 'Color hex code: ' ) . $html_color ); ?></span></li>
						<?php
					}

					?>
					</ul>
				</div>
				<?php endforeach; ?>
			</div>
		</fieldset>
	</div>
	<?php
	}

}