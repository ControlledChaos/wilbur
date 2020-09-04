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
define( 'WILBUR_CLASS', get_theme_file_path() . '/classes/class-' );

// Array of classes to register.
const CLASSES = [
	'Wilbur\Classes\Theme'            => WILBUR_CLASS . 'theme.php',
	'Wilbur\Classes\Media'            => WILBUR_CLASS . 'media.php',
	'Wilbur\Classes\Dark_Mode_Widget' => WILBUR_CLASS . 'dark-mode-widget.php',
	'Wilbur\Classes\User_Bio'         => WILBUR_CLASS . 'user-bio.php',
	'Wilbur\Classes\User_Colors'      => WILBUR_CLASS . 'user-colors.php',
	'Wilbur\Classes\Admin_Pages'      => WILBUR_CLASS . 'admin-pages.php',
	'Wilbur\Classes\Dashboard'        => WILBUR_CLASS . 'dashboard.php'
];

// Autoload class files.
spl_autoload_register(
	function ( string $classname ) {
		if ( isset( CLASSES[ $classname ] ) ) {
			require CLASSES[ $classname ];
		}
	}
);
