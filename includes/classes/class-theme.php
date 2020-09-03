<?php
/**
 * Hindsight theme class file
 *
 * Adds theme support, enqueues styles & scripts, registers
 * navigation menus & widget areas, all the typical theme stuff.
 *
 * @package    Hindsight
 * @subpackage Classes
 * @category   General
 * @since      1.0.0
 */

 // Theme file namespace.
namespace Hindsight\Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Define the URL to report issues
 *
 * Used in the contextual help tab on the theme page.
 */
if ( ! defined( 'HINDSIGHT_ISSUES' ) ) {
	define( 'HINDSIGHT_ISSUES', 'https://github.com/ControlledChaos/hindsight/issues' );
}

class Theme {

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

		// Theme setup.
		add_action( 'after_setup_theme', [ $this, 'setup' ], 11 );

		// Frontend styles.
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_styles' ] );

		// Backend styles.
		add_action( 'admin_enqueue_scripts', [ $this, 'backend_styles' ], 99 );

		// Customizer scripts.
		add_action( 'customize_controls_print_footer_scripts', [ $this, 'customize_scripts' ] );

		// Customizer styles.
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'customize_styles' ] );

		// Login styles.
		add_action( 'login_enqueue_scripts', [ $this, 'login_styles' ] );

		// Add excerpts to pages for use in meta data.
        add_action( 'init', [ $this, 'add_page_excerpts' ] );

        // Show excerpt metabox by default.
        add_filter( 'default_hidden_meta_boxes', [ $this, 'show_excerpt_metabox' ], 10, 2 );

		// Hindsight theme page.
		add_action( 'admin_menu', [ $this, 'theme_page' ] );

		// Tabbed theme page scripts.
		add_action( 'admin_enqueue_scripts', [ $this, 'tabs' ] );

		// Filter the parent theme starter content.
		if ( is_customize_preview() ) {
			add_filter( 'twentytwenty_starter_content', [ $this, 'starter_content' ] );
		}
	}

	/**
	 * Theme setup
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function setup() {

		// Set the post thumbnail size, 16:9 HD Video aspect ratio.
		set_post_thumbnail_size( 1920, 1080, [ 'center', 'center' ] );

		/**
		 * Custom background
		 *
		 * @since 1.0.0
		 */
		remove_theme_support( 'custom-background' );
		add_theme_support( 'custom-background', apply_filters( 'hindsight_background', [
			'default-image'          => '',
			'default-preset'         => 'default',
			'default-position-x'     => 'left',
			'default-position-y'     => 'top',
			'default-size'           => 'auto',
			'default-repeat'         => 'repeat',
			'default-attachment'     => 'scroll',
			'default-color'          => 'ffffff',
			'wp-head-callback'       => '_custom_background_cb',
			'admin-head-callback'    => '',
			'admin-preview-callback' => '',
		] ) );

		/**
		 * Custom header
		 *
		 * @since 1.0.0
		 */
		register_default_headers(
			apply_filters(
				'hindsight_header_images',
				[
					'hindsight' => [
						'url'           => get_theme_file_uri( 'assets/images/hindsight.jpg' ),
						'thumbnail_url' => get_theme_file_uri( 'assets/images/hindsight-thumb.jpg' ),
						'description'   => __( 'Hindsight Image', 'hindsight' ),
					],
				]
			)
		);

		add_theme_support(
			'custom-header',
			apply_filters(
				'hindsight_header',
				[
					'width'                  => 1920,
					'height'                 => 1080,
					'flex-height'            => true,
					'flex-width'             => false,
					'default-text-color'     => 'ffffff',
					'default-image'          => 'hindsight',
					'random-default'         => false,
					'header-text'            => false,
					'uploads'                => true,
					'wp-head-callback'       => '',
					'admin-head-callback'    => '',
					'admin-preview-callback' => '',
					'video'                  => false,
					'video-active-callback'  => '',
				]
			)
		);
	}

	/**
	 * Enqueue frontend styles
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function frontend_styles() {

		// Get theme versions.
		$theme_version  = wp_get_theme()->get( 'Version' );
		$parent_version = wp_get_theme( 'twentytwenty' )->get( 'Version' );

		// Parent theme stylesheet.
		$parent_style = 'twentytwenty';
		wp_enqueue_style( $parent_style, get_parent_theme_file_uri( 'style.css' ), [], $parent_version, 'all' );

		/**
		 * Theme sylesheet
		 *
		 * This enqueues the minified stylesheet compiled from SASS (.scss) files.
		 * The main stylesheet, in the root directory, only contains the theme header but
		 * it is necessary for theme activation. DO NOT delete the main stylesheet!
		 */
		wp_enqueue_style( 'hindsight', get_theme_file_uri( 'assets/css/style.min.css' ), [ $parent_style ], $theme_version, 'all' );

		// Right-to-left languages.
		if ( is_rtl() ) {
			wp_enqueue_style( 'hindsight-rtl', get_theme_file_uri( 'assets/css/rtl.min.css' ), [ 'hindsight' ], $theme_version, 'all' );
		}

		/**
		 * Dark mode styles
		 *
		 * These syles are enqueued if the user's device prefers dark mode.
		 * Same as the dark mode option in the Customizer.
		 */
		wp_enqueue_style( 'hindsight-dark', get_theme_file_uri( 'assets/css/dark-mode.min.css' ), [ 'hindsight' ], $theme_version, '(prefers-color-scheme: dark)' );

		// Toolbar styles.
		wp_enqueue_style( 'hindsight-toolbar', get_theme_file_uri( 'assets/css/toolbar.min.css' ), [ 'hindsight' ], $theme_version, 'all' );
	}

	/**
	 * Enqueue backend styles
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function backend_styles() {

		// Get WordPress version.
		global $wp_version;

		// Get theme versions.
		$theme_version  = wp_get_theme()->get( 'Version' );

		// Admin page styles.
		wp_enqueue_style( 'hindsight-admin', get_theme_file_uri( 'assets/css/admin.css' ), [], $theme_version, 'all' );

		// WordPress 5.4 + styles.
		if ( version_compare( $wp_version, '5.4', '>=' ) ) {
			wp_enqueue_style( 'hindsight-edit-page', get_theme_file_uri( 'assets/css/edit-page.css' ), [], $theme_version, 'all' );
		} else {
			wp_enqueue_style( 'hindsight-edit-page', get_theme_file_uri( 'assets/css/edit-page-early.css' ), [], $theme_version, 'all' );
		}

		// Right-to-left languages.
		if ( is_rtl() ) {
			wp_enqueue_style( 'hindsight-admin-rtl', get_theme_file_uri( 'assets/css/admin-rtl.css' ), [ 'hindsight-admin' ], $theme_version, 'all' );
		}

		// Toolbar styles.
		wp_enqueue_style( 'hindsight-toolbar', get_theme_file_uri( 'assets/css/toolbar.css' ), [ 'admin-bar' ], $theme_version, 'all' );
	}

	/**
	 * Enqueue customizer scripts
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function customize_scripts() {

		// Get theme versions.
		$theme_version  = wp_get_theme()->get( 'Version' );

		wp_enqueue_script( 'hindsight-customize', get_theme_file_uri( 'assets/js/customize.min.js' ), [], $theme_version, true );
	}

	/**
	 * Enqueue customizer styles
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function customize_styles() {

		// Get theme versions.
		$theme_version  = wp_get_theme()->get( 'Version' );

		wp_enqueue_style( 'hindsight-customize', get_theme_file_uri( 'assets/css/customize.min.css' ), [], $theme_version, 'all' );
	}

	/**
	 * Enqueue login styles
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function login_styles() {

		// Get theme versions.
		$theme_version  = wp_get_theme()->get( 'Version' );

		wp_enqueue_style( 'hindsight-login', get_theme_file_uri( 'assets/css/login.min.css' ), [], $theme_version, 'all' );
	}

	/**
     * Add excerpts to pages
     *
     * @since  1.0.0
	 * @access public
	 * @return void
     */
    public function add_page_excerpts() {
        add_post_type_support( 'page', 'excerpt' );
    }

    /**
     * Excerpts visible by default
     *
     * @since  1.0.0
	 * @access public
     * @param  array $hidden
     * @param  object $screen
	 * @return array Unsets the hidden value in the screen base array.
     */
    public function show_excerpt_metabox( $hidden, $screen ) {

        // Post type screens to show excerpt.
        if ( 'post' == $screen->base || 'page' == $screen->base ) {

            // Look for hidden stuff.
            foreach( $hidden as $key=>$value ) {

                // If the excerpt is hidden, show it.
                if ( 'postexcerpt' == $value ) {
                    unset( $hidden[$key] );
                    break;
                }

            }

        }

        // Return the default for other post types.
        return $hidden;
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
		wp_add_inline_script( 'jquery-ui-tabs', 'jQuery(document).ready(function($){ $("#theme-page-content .tabbed-content").tabs({ activate: function( event, ui ) { ui.newPanel.hide().fadeIn(250); } }) });', true );
	}

	/**
	 * Hindsight theme page
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_theme_page/
	 * @link https://codex.wordpress.org/Class_Reference/WP_Screen/add_help_tab
	 */
	public function theme_page() {

		// Add a submenu page under Themes.
		$this->theme_page = add_theme_page(
			__( 'Hindsight Theme Information', 'hindsight' ),
			__( 'Hindsight Theme', 'hindsight' ),
			'manage_options',
			'hindsight',
			[ $this, 'theme_page_output' ],
			-1
		);

		// Add sample help tab.
		add_action( 'load-' . $this->theme_page, [ $this, 'help_theme_page' ] );
	}

	/**
     * Add help tabs to the theme page
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
     */
    public function help_theme_page() {

		// Add to the about page.
		$screen = get_current_screen();
		if ( $screen->id != $this->theme_page ) {
			return;
		}

		// Report Issues tab.
		$screen->add_help_tab( [
			'id'       => 'theme_issues',
			'title'    => __( 'Report Issues', 'hindsight' ),
			'content'  => null,
			'callback' => [ $this, 'help_theme_issues' ]
		] );
	}

	/**
     * Report Issues help tab content
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
     */
	public function help_theme_issues() {

		?>
		<h3><?php _e( 'Report Theme Issues', 'hindsight' ); ?></h3>
		<?php echo sprintf(
			'<p>%1s <a href="%2s" target="_blank">%3s</a></p>',
			__( 'Please report issues with the Hindsight theme to:', 'hindsight' ),
			esc_attr( esc_url( HINDSIGHT_ISSUES ) ),
			esc_url( HINDSIGHT_ISSUES )
		); ?>

		<?php
	}

	/**
     * Get output of the theme page
     *
     * @since  1.0.0
	 * @access public
	 * @return void
     */
    public function theme_page_output() {
        require get_theme_file_path( '/templates/theme-page.php' );
	}

	/**
	 * Function to return the array of starter content for the theme.
	 *
	 * Adds starter content to highlight the theme on fresh sites.
	 * This is done conditionally to avoid loading the starter content on every
	 * page load, as it is a one-off operation only needed once in the customizer.
	 *
	 * Passes through the `twentytwenty_starter_content` filter before returning.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $starter_content Array of starter content added by the parent theme.
	 * @return array Returns a filtered array of args for the starter_content.
	 */
	public function starter_content( $starter_content ) {

		// Define and register starter content to showcase the theme on new sites.
		require get_theme_file_path( '/inc/starter-content.php' );
		$starter_content = \Hindsight\Includes\starter_content();

		return $starter_content;
	}
}