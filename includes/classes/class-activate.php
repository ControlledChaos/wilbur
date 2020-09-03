<?php
/**
 * Hindsight theme activation class
 *
 * Redirects to the Theme Info page upon activation,
 * if the user can customize themes.
 *
 * Not using the short array syntax because this needs
 * to be read by PHP versions lower than 5.4.
 *
 * @package    Hindsight
 * @subpackage Classes
 * @category   Administration
 * @access     public
 * @since      1.0.0
 */

// Theme file namespace.
namespace Hindsight\Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Get plugins path
 *
 * Used to check for active plugins with the `is_plugin_active` function.
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

class Activate {

    /**
	 * Theme activation method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function activate() {

		// Variable for an instance of the class.
		$activate = new self();

		// Conditional activation methods.
		add_action( 'after_switch_theme', array( $activate, 'theme_switch' ) );
	}

	/**
	 * Conditional activation methods
	 *
	 * Compares the theme required minimum PHP version with
	 * that of the current system.
	 *
	 * If the minimum is not met the previous theme is reactivated
	 * and an admin notice is displayed.
	 *
	 * If the minimum is met the the user is redirected
	 * to the theme info page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function theme_switch() {

		// Access the Customizer object.
		global $wp_customize;

		// Variable for version lower than required.
		$needs_update = version_compare( phpversion(), HINDSIGHT_PHP_VERSION, '<' );

		// If in the Customizer and less than the required minimum PHP version.
		if ( isset( $wp_customize ) && $needs_update ) :

			// Switch back to the previous theme.
			switch_theme( get_option( 'theme_switched' ) );

		// If less than the required minimum PHP version.
		elseif ( $needs_update ) :

			// Add admin notices that the theme was not activated.
			add_action( 'admin_notices', array( $this, 'php_deactivate_notice' ) );
			add_action( 'network_admin_notices', array( $this, 'php_deactivate_notice' ) );

			// Switch back to the previous theme.
			switch_theme( get_option( 'theme_switched' ) );

		// If the Options for Twenty Twenty plugin is active.
		elseif ( is_plugin_active( 'options-for-twenty-twenty/options-for-twenty-twenty.php' ) ) :

			// Add admin notices that the theme was not activated.
			add_action( 'admin_notices', array( $this, 'options_plugin_deactivate_notice' ) );
			add_action( 'network_admin_notices', array( $this, 'options_plugin_deactivate_notice' ) );

			// Switch back to the previous theme.
			switch_theme( get_option( 'theme_switched' ) );

		// If the required minimum is met.
		else :

			// Redirect to the theme info page.
			$this->redirect();

		// End version compare.
		endif;
	}

	/**
	 * PHP deactivation notice
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the markup of the admin notice.
	 */
	public function php_deactivate_notice() {

	?>
		<div id="hindsight-php-notice" class="notice notice-error is-dismissible">
			<?php echo sprintf(
				'<p>%1s %2s %3s %4s</p>',
				__( 'The Hindsight theme could not be activated because it requires PHP version', 'hindsight' ),
				HINDSIGHT_PHP_VERSION,
				__( 'or greater. Your system is running PHP version', 'hindsight' ),
				phpversion()
			); ?>
		</div>
	<?php

	}

	/**
	 * Options plugin deactivation notice
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the markup of the admin notice.
	 */
	public function options_plugin_deactivate_notice() {

		?>
			<div id="hindsight-options-plugin-notice" class="notice notice-error is-dismissible">
				<?php echo sprintf(
					'<p>%1s <a href="%2s">%3s</a> %4s</p>',
					__( 'The Hindsight theme could not be activated because it conflicts with the Options for Twenty Twenty plugin. Please', 'hindsight' ),
					esc_url( admin_url( 'plugins.php?s=Options for Twenty Twenty&plugin_status=active' ) ),
					__( 'deactivate the plugin', 'hindsight' ),
					__( 'before activating Hindsight.', 'hindsight' )
				); ?>
			</div>
		<?php

		}

    /**
	 * Redirect to the theme page
	 *
	 * Fired after the theme is activated.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
    private function redirect() {

		// Get the current screen.
		global $pagenow;

		// If on the Themes page and the `activated` URL parameter is set.
		if ( 'themes.php' == $pagenow && is_admin() && isset( $_GET['activated'] ) ) {

			/**
			 * Redirect to the theme info page
			 *
			 * Only if the current user can customize themes.
			 */
			if ( current_user_can( 'customize' ) ) {
				wp_redirect( admin_url( 'themes.php' ) . '?page=hindsight' );
			}
		}

    }
}

// Run the activate method.
Activate::activate();