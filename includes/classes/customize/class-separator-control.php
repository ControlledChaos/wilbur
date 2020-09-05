<?php
/**
 * Customizer Separator Control settings for this theme.
 *
 * @package WordPress
 * @subpackage Wilbur
 * @since Wilbur 1.0
 */

// Theme file namespace.
namespace Wilbur\Classes;

if ( class_exists( 'WP_Customize_Control' ) ) {

	/**
	 * Separator Control.
	 */
	class Separator_Control extends \WP_Customize_Control {
		/**
		 * Render the hr.
		 */
		public function render_content() {
			echo '<hr/>';
		}

	}
}
