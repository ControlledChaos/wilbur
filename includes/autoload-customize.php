<?php
/**
 * Register theme classes
 *
 * @package  Hindsight
 * @category General
 * @access   public
 * @since    1.0.0
 */

// Theme file namespace.
namespace Hindsight;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Define the `classes` directory.
define( 'HINDSIGHT_CUSTOMIZE_CLASS', get_theme_file_path() . '/classes/customize/class-' );

// Array of classes to register.
const CUSTOMIZE_CLASSES = [
	'Hindsight\Classes\Customize'          => HINDSIGHT_CUSTOMIZE_CLASS . 'customize.php',
	'Hindsight\Classes\Customize_Controls' => HINDSIGHT_CUSTOMIZE_CLASS . 'customize-controls.php',
	'Hindsight\Classes\Customize_Sanitize' => HINDSIGHT_CUSTOMIZE_CLASS . 'customize-sanitize.php',
];

// Autoload class files.
spl_autoload_register(
	function ( string $classname ) {
		if ( isset( CUSTOMIZE_CLASSES[ $classname ] ) ) {
			require CUSTOMIZE_CLASSES[ $classname ];
		}
	}
);