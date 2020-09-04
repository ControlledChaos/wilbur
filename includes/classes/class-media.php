<?php
/**
 * Media class
 *
 * Registers image options.
 *
 * @package    Wilbur
 * @subpackage Classes
 * @category   Media
 * @access     public
 * @since      1.0.0
 */

// Theme file namespace.
namespace Wilbur\Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Media {

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

        // Media settings.
        add_action( 'admin_init', [ $this, 'settings' ] );

        // Hard crop default image sizes.
        add_action( 'after_setup_theme', [ $this, 'crop' ] );

    }

    /**
	 * Media settings
	 *
	 * Adds fields to the Media Settings page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function settings() {

        // Crop medium size images.
		add_settings_field(
			'medium_crop',
			__( 'Medium size crop', 'wilbur' ),
			[ $this, 'medium_crop' ],
			'media',
			'default',
			[ __( 'Crop medium size images to exact dimensions (normally proportional)', 'wilbur' ) ]
		);

		register_setting(
            'media',
            'medium_crop'
        );

		// Crop large size images.
        add_settings_field(
			'large_crop',
			__( 'Large size crop', 'wilbur' ),
			[ $this, 'large_crop' ],
			'media',
			'default',
			[ __( 'Crop large size images to exact dimensions (normally proportional)', 'wilbur' ) ]
		);

        register_setting(
            'media',
            'large_crop'
        );

    }

    /**
     * Medium crop field
	 *
	 * Adds a checkbox field and label to the Media Settings page.
     *
     * @since  1.0.0
	 * @access public
	 * @return string Returns the field markup.
     */
    public function medium_crop( $args ) {

        $html = '<p><input type="checkbox" id="medium_crop" name="medium_crop" value="1" ' . checked( 1, get_option( 'medium_crop' ), false ) . '/>';

        $html .= '<label for="medium_crop"> '  . $args[0] . '</label></p>';

        echo $html;

    }

    /**
     * Large crop field
	 *
	 * Adds a checkbox field and label to the Media Settings page.
     *
     * @since  1.0.0
	 * @access public
	 * @return string Returns the field markup.
     */
    public function large_crop( $args ) {

        $html = '<p><input type="checkbox" id="large_crop" name="large_crop" value="1" ' . checked( 1, get_option( 'large_crop' ), false ) . '/>';

        $html .= '<label for="large_crop"> '  . $args[0] . '</label></p>';

        echo $html;

    }

    /**
     * Update crop options
     *
     * @since  1.0.0
	 * @access public
	 * @return void
     */
    public function crop() {

		// Update medium size image crop.
        if ( get_option( 'medium_crop' ) ) {
            update_option( 'medium_crop', 1 );
        } else {
            update_option( 'medium_crop', 0 );
        }

		// Update large size image crop.
        if ( get_option( 'large_crop' ) ) {
            update_option( 'large_crop', 1 );
        } else {
            update_option( 'large_crop', 0 );
        }
    }
}