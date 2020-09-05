<?php
/**
 * Customizer sanitization class
 *
 * Sanitizes customizer settings for security.
 *
 * Uses sanitization methods in the parent theme.
 * @see wp-content/wilbur/classes/class-wilbur-customize.php
 *
 * @package    Wilbur
 * @subpackage Classes
 * @category   Customizer
 * @access     public
 * @since      1.0.0
 */

// Theme file namespace.
namespace Wilbur\Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Customize_Sanitize {

	/**
	 * Sanitization callback for the "accent_accessible_colors" setting.
	 *
	 * @static
	 * @access public
	 * @since 1.0.0
	 * @param array $value The value we want to sanitize.
	 * @return array Returns sanitized value. Each item in the array gets sanitized separately.
	 */
	public static function sanitize_accent_accessible_colors( $value ) {

		// Make sure the value is an array. Do not typecast, use empty array as fallback.
		$value = is_array( $value ) ? $value : [];

		// Loop values.
		foreach ( $value as $area => $values ) {
			foreach ( $values as $context => $color_val ) {
				$value[ $area ][ $context ] = sanitize_hex_color( $color_val );
			}
		}

		return $value;
	}

	/**
	 * Sanitize select.
	 *
	 * @param string $input The input from the setting.
	 * @param object $setting The selected setting.
	 * @return string The input from the setting or the default setting.
	 */
	public static function sanitize_select( $input, $setting ) {
		$input   = sanitize_key( $input );
		$choices = $setting->manager->get_control( $setting->id )->choices;
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}

	/**
	 * Sanitize boolean for checkbox.
	 *
	 * @param bool $checked Whether or not a box is checked.
	 * @return bool
	 */
	public static function sanitize_checkbox( $checked ) {
		return ( ( isset( $checked ) && true === $checked ) ? true : false );
	}

}