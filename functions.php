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
define( 'WILBUR_PHP_VERSION', '7.0' );

/**
 * Get the theme activation class
 *
 * Instantiate before the following version compare
 * to allow the deatcivation methods to run.
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
	// require get_theme_file_path( '/includes/autoload-customize.php' );

	// Get the filename of the current page.
	global $pagenow;

	// Instantiate theme classes.
	Wilbur\Classes\Theme       :: instance();
	Wilbur\Classes\Non_Latin       :: instance();
	// Wilbur\Classes\Media       :: instance();
	// Wilbur\Classes\Customize   :: instance();
	// Wilbur\Classes\User_Colors :: instance();

	// Instantiate admin theme classes.
	if ( is_admin() ) {

		// Run the page header on all screens.
		// Classes\Admin_Pages :: instance();

		// Run the dashboard only on the admin index screen.
		if ( 'index.php' == $pagenow ) {
			// Classes\Dashboard :: instance();
		}
	}
}

// Run theme classes.
wilbur();

/**
 * REQUIRED FILES
 * Include required files.
 */
require get_theme_file_path( '/includes/template-functions.php' );
require get_theme_file_path( '/includes/template-tags.php' );

// Handle SVG icons.
require get_theme_file_path( '/includes/classes/class-wilbur-svg-icons.php' );
require get_theme_file_path( '/includes/svg-icons.php' );

// Handle Customizer settings.
require get_theme_file_path( '/includes/classes/class-wilbur-customize.php' );

// Require Separator Control class.
require get_theme_file_path( '/includes/classes/class-wilbur-separator-control.php' );

// Custom comment walker.
require get_theme_file_path( '/includes/classes/class-wilbur-walker-comment.php' );

// Custom page walker.
require get_theme_file_path( '/includes/classes/class-wilbur-walker-page.php' );

// Custom script loader class.
require get_theme_file_path( '/includes/classes/class-wilbur-script-loader.php' );

// Custom CSS.
require get_theme_file_path( '/includes/custom-css.php' );

/**
 * Enqueue scripts for the customizer preview.
 *
 * @since Wilbur 1.0
 *
 * @return void
 */
function wilbur_customize_preview_init() {

	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_script( 'wilbur-customize-preview', get_theme_file_uri( '/assets/js/customize-preview.js' ), array( 'customize-preview', 'customize-selective-refresh', 'jquery' ), $theme_version, true );
	wp_localize_script( 'wilbur-customize-preview', 'twentyTwentyBgColors', wilbur_get_customizer_color_vars() );
	wp_localize_script( 'wilbur-customize-preview', 'twentyTwentyPreviewEls', wilbur_get_elements_array() );

	wp_add_inline_script(
		'wilbur-customize-preview',
		sprintf(
			'wp.customize.selectiveRefresh.partialConstructor[ %1$s ].prototype.attrs = %2$s;',
			wp_json_encode( 'cover_opacity' ),
			wp_json_encode( wilbur_customize_opacity_range() )
		)
	);
}

add_action( 'customize_preview_init', 'wilbur_customize_preview_init' );

/**
 * Returns an array of variables for the customizer preview.
 *
 * @since Wilbur 1.0
 *
 * @return array
 */
function wilbur_get_customizer_color_vars() {

	$colors = array(
		'content'       => array(
			'setting' => 'background_color',
		),
		'header-footer' => array(
			'setting' => 'header_footer_background_color',
		),
	);
	return $colors;
}

/**
 * Get an array of elements.
 *
 * @since Wilbur 1.0
 *
 * @return array
 */
function wilbur_get_elements_array() {

	// The array is formatted like this:
	// [key-in-saved-setting][sub-key-in-setting][css-property] = [elements].
	$elements = array(
		'content'       => array(
			'accent'     => array(
				'color'            => array( '.color-accent', '.color-accent-hover:hover', '.color-accent-hover:focus', ':root .has-accent-color', '.has-drop-cap:not(:focus):first-letter', '.wp-block-button.is-style-outline', 'a' ),
				'border-color'     => array( 'blockquote', '.border-color-accent', '.border-color-accent-hover:hover', '.border-color-accent-hover:focus' ),
				'background-color' => array( 'button', '.button', '.faux-button', '.wp-block-button__link', '.wp-block-file .wp-block-file__button', 'input[type="button"]', 'input[type="reset"]', 'input[type="submit"]', '.bg-accent', '.bg-accent-hover:hover', '.bg-accent-hover:focus', ':root .has-accent-background-color', '.comment-reply-link' ),
				'fill'             => array( '.fill-children-accent', '.fill-children-accent *' ),
			),
			'background' => array(
				'color'            => array( ':root .has-background-color', 'button', '.button', '.faux-button', '.wp-block-button__link', '.wp-block-file__button', 'input[type="button"]', 'input[type="reset"]', 'input[type="submit"]', '.wp-block-button', '.comment-reply-link', '.has-background.has-primary-background-color:not(.has-text-color)', '.has-background.has-primary-background-color *:not(.has-text-color)', '.has-background.has-accent-background-color:not(.has-text-color)', '.has-background.has-accent-background-color *:not(.has-text-color)' ),
				'background-color' => array( ':root .has-background-background-color' ),
			),
			'text'       => array(
				'color'            => array( 'body', '.entry-title a', ':root .has-primary-color' ),
				'background-color' => array( ':root .has-primary-background-color' ),
			),
			'secondary'  => array(
				'color'            => array( 'cite', 'figcaption', '.wp-caption-text', '.post-meta', '.entry-content .wp-block-archives li', '.entry-content .wp-block-categories li', '.entry-content .wp-block-latest-posts li', '.wp-block-latest-comments__comment-date', '.wp-block-latest-posts__post-date', '.wp-block-embed figcaption', '.wp-block-image figcaption', '.wp-block-pullquote cite', '.comment-metadata', '.comment-respond .comment-notes', '.comment-respond .logged-in-as', '.pagination .dots', '.entry-content hr:not(.has-background)', 'hr.styled-separator', ':root .has-secondary-color' ),
				'background-color' => array( ':root .has-secondary-background-color' ),
			),
			'borders'    => array(
				'border-color'        => array( 'pre', 'fieldset', 'input', 'textarea', 'table', 'table *', 'hr' ),
				'background-color'    => array( 'caption', 'code', 'code', 'kbd', 'samp', '.wp-block-table.is-style-stripes tbody tr:nth-child(odd)', ':root .has-subtle-background-background-color' ),
				'border-bottom-color' => array( '.wp-block-table.is-style-stripes' ),
				'border-top-color'    => array( '.wp-block-latest-posts.is-grid li' ),
				'color'               => array( ':root .has-subtle-background-color' ),
			),
		),
		'header-footer' => array(
			'accent'     => array(
				'color'            => array( 'body:not(.overlay-header) .primary-menu > li > a', 'body:not(.overlay-header) .primary-menu > li > .icon', '.modal-menu a', '.footer-menu a, .footer-widgets a', '#site-footer .wp-block-button.is-style-outline', '.wp-block-pullquote:before', '.singular:not(.overlay-header) .entry-header a', '.archive-header a', '.header-footer-group .color-accent', '.header-footer-group .color-accent-hover:hover' ),
				'background-color' => array( '.social-icons a', '#site-footer button:not(.toggle)', '#site-footer .button', '#site-footer .faux-button', '#site-footer .wp-block-button__link', '#site-footer .wp-block-file__button', '#site-footer input[type="button"]', '#site-footer input[type="reset"]', '#site-footer input[type="submit"]' ),
			),
			'background' => array(
				'color'            => array( '.social-icons a', 'body:not(.overlay-header) .primary-menu ul', '.header-footer-group button', '.header-footer-group .button', '.header-footer-group .faux-button', '.header-footer-group .wp-block-button:not(.is-style-outline) .wp-block-button__link', '.header-footer-group .wp-block-file__button', '.header-footer-group input[type="button"]', '.header-footer-group input[type="reset"]', '.header-footer-group input[type="submit"]' ),
				'background-color' => array( '#site-header', '.footer-nav-widgets-wrapper', '#site-footer', '.menu-modal', '.menu-modal-inner', '.search-modal-inner', '.archive-header', '.singular .entry-header', '.singular .featured-media:before', '.wp-block-pullquote:before' ),
			),
			'text'       => array(
				'color'               => array( '.header-footer-group', 'body:not(.overlay-header) #site-header .toggle', '.menu-modal .toggle' ),
				'background-color'    => array( 'body:not(.overlay-header) .primary-menu ul' ),
				'border-bottom-color' => array( 'body:not(.overlay-header) .primary-menu > li > ul:after' ),
				'border-left-color'   => array( 'body:not(.overlay-header) .primary-menu ul ul:after' ),
			),
			'secondary'  => array(
				'color' => array( '.site-description', 'body:not(.overlay-header) .toggle-inner .toggle-text', '.widget .post-date', '.widget .rss-date', '.widget_archive li', '.widget_categories li', '.widget cite', '.widget_pages li', '.widget_meta li', '.widget_nav_menu li', '.powered-by-wordpress', '.to-the-top', '.singular .entry-header .post-meta', '.singular:not(.overlay-header) .entry-header .post-meta a' ),
			),
			'borders'    => array(
				'border-color'     => array( '.header-footer-group pre', '.header-footer-group fieldset', '.header-footer-group input', '.header-footer-group textarea', '.header-footer-group table', '.header-footer-group table *', '.footer-nav-widgets-wrapper', '#site-footer', '.menu-modal nav *', '.footer-widgets-outer-wrapper', '.footer-top' ),
				'background-color' => array( '.header-footer-group table caption', 'body:not(.overlay-header) .header-inner .toggle-wrapper::before' ),
			),
		),
	);

	/**
	* Filters Wilbur theme elements
	*
	* @since Wilbur 1.0
	*
	* @param array Array of elements
	*/
	return apply_filters( 'wilbur_get_elements_array', $elements );
}
