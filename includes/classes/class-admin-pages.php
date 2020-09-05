<?php
/**
 * Admin page header class
 *
 * Adds a header with site branding and navigation
 * to admin screens.
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

class Admin_Pages {

	/**
	 * Instance of the class
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object Returns the instance.
	 */
	public static function instance() {
		return new self;
	}

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access private
	 * @return self
	 */
    private function __construct() {

		// Register admin menus.
		add_action( 'after_setup_theme', [ $this, 'admin_menus' ] );

		// Include the admin header template.
		add_action( 'in_admin_header', [ $this, 'admin_header' ] );

		// Add icons to parent items.
		add_filter( 'nav_menu_item_args', [ $this, 'add_sub_toggles' ], 10, 3 );

		// Register widget areas. Secondary in priority to frontend widgets.
		add_action( 'widgets_init', [ $this, 'widgets' ], 11 );

		// Backend search form template.
		add_filter( 'get_search_form', [ $this, 'search_form' ] );

		// Add widget area to the admin footer.
		add_action( 'in_admin_footer', [ $this, 'admin_footer' ] );

		/**
		 * Add a wrapper to #wpfooter
		 *
		 * Opening tag priority 11 to run after the widget area.
		 * Closing tag priority 9 to run before any script tags.
		 */
		add_action( 'in_admin_footer', function() { echo '<div class="wpfooter-wrapper">'; }, 11 );
		add_action( 'admin_footer', function() { echo '</div><!-- .wpfooter-wrapper -->'; }, 9 );

		// Add inline scripts to the footer.
		add_action( 'in_admin_footer', [ $this, 'admin_footer_scripts' ] );
	}


	/**
	 * Register admin menus
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_menus() {

		register_nav_menus( [
			'admin-header' => __( 'Admin Header Menu', 'wilbur' ),
			'admin-footer' => __( 'Admin Footer Menu', 'wilbur' )
		] );
	}

	/**
	 * Fallback for the admin header menu
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the markup for the fallback menu.
	 */
	public static function admin_menu_fallback() {

		// Start the list.
		$html = '<ul id="admin-header-menu-fallback" class="primary-menu admin-header-menu reset-list-style">';

		// Link to nav menus screen.
		$html .= sprintf(
			'<li><a href="%1s">%2s</a></li>',
			esc_url( admin_url( 'nav-menus.php' ) ),
			__( 'Add Menu Here', 'wilbur' )
		);

		// Link to Wilbur theme page.
		$html .= sprintf(
			'<li><a href="%1s">%2s</a></li>',
			esc_url( admin_url( 'themes.php?page=wilbur' ) ),
			__( 'Wilbur Theme', 'wilbur' )
		);

		// End the list.
		$html .= '</ul>';

		// Return the markup.
		echo $html;
	}

    /**
	 * Admin page header
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object Returns the HTML for the admin header.
	 */
	public function admin_header() {
		get_template_part( 'template-parts/admin/admin-header' );
	}

	/**
	 * Get the backend search form template.
	 *
	 * @since  1.0.0
	 * @return mixed Returns the HTML of the search form.
	 */
	public function get_search_form() {

		// Bail if not in admin.
		if ( ! is_admin() ) {
			return;
		}

		ob_start();

		require get_theme_file_path( '/template-parts/admin/searchform.php' );

		$form = ob_get_clean();

		return $form;
	}

	/**
	 * Output the backend search form.
	 *
	 * @since  1.0.0
	 * @param  mixed $form
	 * @return mixed Returns the HTML of the search form.
	 */
	public function search_form( $form ) {

		// Bail if not in admin.
		if ( ! is_admin() ) {
			return;
		}

		// Get the HTML of the form.
		$form = $this->get_search_form();

		return $form;
	}

	/**
	 * Register widgets
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function widgets() {

		// Arguments used in all register_sidebar() calls.
		$shared_args = [
			'before_title'  => '<h2 class="widget-title subheading heading-size-3">',
			'after_title'   => '</h2>',
			'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
			'after_widget'  => '</div></div>',
		];

		// Footer #1.
		register_sidebar(
			array_merge(
				$shared_args,
				[
					'name'        => __( 'Admin Footer #1', 'wilbur' ),
					'id'          => 'footer-sidebar-1',
					'description' => __( 'Widgets in this area will be displayed in the first column in the admin footer.', 'wilbur' ),
				]
			)
		);

		// Footer #2.
		register_sidebar(
			array_merge(
				$shared_args,
				[
					'name'        => __( 'Admin Footer #2', 'wilbur' ),
					'id'          => 'footer-sidebar-2',
					'description' => __( 'Widgets in this area will be displayed in the second column in the admin footer.', 'wilbur' ),
				]
			)
		);

	}

	/**
	 * Admin page footer widget area
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object Returns the HTML for the admin widget area.
	 */
	public function admin_footer() {

		/**
		 * Do not include on the Widgets screen. Widgets get wrapped
		 * with the management UI by JavaScript.
		 */
		$screen = get_current_screen();
		if ( $screen->id === 'widgets' ) {
			return;
		}

		get_template_part( 'template-parts/admin/admin-footer' );
	}

	/**
	 * Add a sub nav aoggle to the admin-header menu
	 *
	 * Adapted from the `wilbur_add_sub_toggles_to_main_menu` function.
	 *
	 * @see wilbur/includes/template-tags.php
	 *
	 * @since  1.0.0
	 * @access public
	 * @param stdClass $args An array of arguments.
	 * @param string   $item Menu item.
	 * @param int      $depth Depth of the current menu item.
	 *
	 * @return stdClass $args An object of wp_nav_menu() arguments.
	 */
	public function add_sub_toggles( $args, $item, $depth ) {

		if ( 'admin-header' === $args->theme_location ) {
			if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
				$args->after = '<span class="icon"></span>';
			} else {
				$args->after = '';
			}
		}

		return $args;
	}

	/**
	 * Add inline scripts to footer
	 */
	public function admin_footer_scripts() {

		// Get the current screen as a variable.
		$screen = get_current_screen();

		// Adjust height of editor pages for the admin header.
		if ( $screen->id == ( 'post' || 'post-new' ) ) :
		?>
		<script>

		var header = document.getElementById( 'admin-site-header' );

		// Create styles block.
		if ( header ) {
			var style = document.createElement( 'style' );
			style.innerHTML =
			'#wpbody, .block-editor {' +
				'height: calc(100vh - ' + header.offsetHeight + 'px) !important;' +
			'}';
		}

		// Get the first script tag.
		var ref = document.querySelector( 'script' );

		// Insert new styles before the first script tag.
		ref.parentNode.insertBefore( style, ref );

		</script>
		<?php
		endif;

	}
}