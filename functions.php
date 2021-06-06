<?php
/**
 * Core theme functions file
 *
 * @package  Wilbur
 * @category General
 * @access   public
 * @since    1.0.0
 */

// Theme file namespace.
// namespace Wilbur;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Define the minimum required PHP version.
define( 'WILBUR_PHP_VERSION', '7.3' );

/**
 * Get the theme activation class
 *
 * Instantiate before the following version compare
 * to allow the deactivation methods to run.
 */
require get_theme_file_path( '/includes/classes/class-activate.php' );

// Stop here if the minimum PHP version is not met.
if ( version_compare( phpversion(), WILBUR_PHP_VERSION, '<' ) ) {
	return;
}

/**
 * Core theme function
 *
 * Loads PHP classes.
 *
 * @since  1.0.0
 * @access public
 * @global string $pagenow Gets the filename of the current page.
 * @return void
 */
function wilbur() {

	// Register theme classes.
	require get_theme_file_path( '/includes/autoload-base.php' );
	require get_theme_file_path( '/includes/autoload-customize.php' );

	// Get the filename of the current page.
	global $pagenow;

	// Instantiate theme classes.
	Wilbur\Classes\Theme     :: instance();
	Wilbur\Classes\Non_Latin :: instance();
	Wilbur\Classes\Media     :: instance();
	Wilbur\Classes\Customize :: instance();

	// Instantiate admin theme classes.
	if ( is_admin() ) {

		// Run the page header on all screens.
		Wilbur\Classes\Admin_Pages :: instance();

		// Run the dashboard only on the admin index screen.
		if ( 'index.php' == $pagenow ) {
			Wilbur\Classes\Dashboard :: instance();
		}
	}

	new Wilbur\Classes\Dark_Mode_Widget;

	// Template files.
	require get_theme_file_path( '/includes/template-functions.php' );
	require get_theme_file_path( '/includes/template-tags.php' );

	// Handle SVG icons.
	require get_theme_file_path( '/includes/svg-icons.php' );

	// Custom CSS.
	require get_theme_file_path( '/includes/custom-css.php' );
}

// Run the theme.
wilbur();
