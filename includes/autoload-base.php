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
define( 'HINDSIGHT_CLASS', get_theme_file_path() . '/classes/class-' );

// Array of classes to register.
const CLASSES = [
	'Hindsight\Classes\Theme'            => HINDSIGHT_CLASS . 'theme.php',
	'Hindsight\Classes\Media'            => HINDSIGHT_CLASS . 'media.php',
	'Hindsight\Classes\Dark_Mode_Widget' => HINDSIGHT_CLASS . 'dark-mode-widget.php',
	'Hindsight\Classes\User_Bio'         => HINDSIGHT_CLASS . 'user-bio.php',
	'Hindsight\Classes\User_Colors'      => HINDSIGHT_CLASS . 'user-colors.php',
	'Hindsight\Classes\Admin_Pages'      => HINDSIGHT_CLASS . 'admin-pages.php',
	'Hindsight\Classes\Dashboard'        => HINDSIGHT_CLASS . 'dashboard.php'
];

// Autoload class files.
spl_autoload_register(
	function ( string $classname ) {
		if ( isset( CLASSES[ $classname ] ) ) {
			require CLASSES[ $classname ];
		}
	}
);