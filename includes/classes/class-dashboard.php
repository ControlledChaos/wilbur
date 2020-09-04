<?php
/**
 * Dashboard class
 *
 * Adds a custom dashboard panel.
 *
 * @package    Wilbur
 * @subpackage Classes
 * @category   Administration
 * @access     public
 * @since      1.0.0
 */

// Theme file namespace.
namespace Wilbur\Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Dashboard {

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
    private function __construct() {

		// Tabbed dashboard panel scripts.
		add_action( 'admin_enqueue_scripts', [ $this, 'tabs' ] );

		// Remove the default welcome panel then add the new panel.
		remove_action( 'welcome_panel', 'wp_welcome_panel' );
		add_action( 'welcome_panel', [ $this, 'dashboard_panel' ], 11 );

		// Add theme page button to the "At a Glance" widget.
		add_action( 'rightnow_end', [ $this, 'glance_theme_button' ] );
		add_action( 'mu_rightnow_end', [ $this, 'glance_theme_button' ] );
	}

	/**
	 * Enqueue jQuery tabs
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the script tag for jQuery tabs.
	 */
	public function tabs() {
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_add_inline_script( 'jquery-ui-tabs', 'jQuery(document).ready(function($){ $(".dashboard-panel .tabbed-content").tabs({ activate: function( event, ui ) { ui.newPanel.hide().fadeIn(250); } }) });', true );
	}

	/**
	 * Dashboard panel markup
	 *
	 * Gets the template from the template-parts directory.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function dashboard_panel() {
		get_template_part( 'template-parts/admin/dashboard-panel' );
	}

	/**
	 * At a Glance button
	 *
	 * Adds a theme page button to the "At a Glance" widget.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function glance_theme_button() {

		echo sprintf(
			'<p><a class="button button-primary glance-theme-button" href="%1s">%2s</a></p>',
			esc_url( admin_url( 'themes.php?page="wilbur' ) ),
			__( 'Wilbur Theme Page', 'wilbur' )
		);

	}

}