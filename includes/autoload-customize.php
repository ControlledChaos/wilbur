<?php
/**
 * Register theme classes
 *
 * @package  Wilbur
 * @category General
 * @access   public
 * @since    1.0.0
 */

// Theme file namespace.
namespace Wilbur;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Define the `classes` directory.
define( 'WILBUR_CUSTOMIZE_CLASS', get_theme_file_path() . '/includes/classes/customize/class-' );

// Array of classes to register.
const CUSTOMIZE_CLASSES = [
	'Wilbur\Classes\Customize'          => WILBUR_CUSTOMIZE_CLASS . 'customize.php',
	'Wilbur\Classes\Customize_Controls' => WILBUR_CUSTOMIZE_CLASS . 'customize-controls.php',
	'Wilbur\Classes\Customize_Sanitize' => WILBUR_CUSTOMIZE_CLASS . 'customize-sanitize.php',
];

// Autoload class files.
spl_autoload_register(
	function ( string $classname ) {
		if ( isset( CUSTOMIZE_CLASSES[ $classname ] ) ) {
			require CUSTOMIZE_CLASSES[ $classname ];
		}
	}
);