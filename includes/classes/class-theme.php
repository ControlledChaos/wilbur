<?php
/**
 * Wilbur theme class file
 *
 * Adds theme support, enqueues styles & scripts, registers
 * navigation menus & widget areas, all the typical theme stuff.
 *
 * @package    Wilbur
 * @subpackage Classes
 * @category   General
 * @since      1.0.0
 */

 // Theme file namespace.
namespace Wilbur\Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) { exit; }

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

		// Wilbur theme page.
		add_action( 'admin_menu', [ $this, 'theme_page' ] );

		// Tabbed theme page scripts.
		add_action( 'admin_enqueue_scripts', [ $this, 'tabs' ] );

		// Filter the parent theme starter content.
		if ( is_customize_preview() ) {
			add_filter( 'wilbur_starter_content', [ $this, 'starter_content' ] );
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

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Set content-width.
		global $content_width;
		if ( ! isset( $content_width ) ) {
			$content_width = 1280;
		}

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

		// Set post thumbnail size.
		set_post_thumbnail_size( 1280, 720, [ 'center', 'center' ] );

		// Add custom image size used in Cover Template.
		add_image_size( 'wilbur-fullscreen', 1920, 1080, true );

		/**
		 * Custom background
		 *
		 * @since 1.0.0
		 */
		remove_theme_support( 'custom-background' );
		add_theme_support( 'custom-background', apply_filters( 'wilbur_background', [
			'default-image'          => '',
			'default-preset'         => 'default',
			'default-position-x'     => 'left',
			'default-position-y'     => 'top',
			'default-size'           => 'auto',
			'default-repeat'         => 'repeat',
			'default-attachment'     => 'scroll',
			'default-color'          => 'f0eddb',
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
				'wilbur_header_images',
				[
					'wilbur' => [
						'url'           => get_theme_file_uri( 'assets/images/wilbur.jpg' ),
						'thumbnail_url' => get_theme_file_uri( 'assets/images/wilbur-thumb.jpg' ),
						'description'   => __( 'Wilbur Image', 'wilbur' ),
					],
				]
			)
		);

		// Custom header support.
		add_theme_support(
			'custom-header',
			apply_filters(
				'wilbur_header',
				[
					'width'                  => 1920,
					'height'                 => 1080,
					'flex-height'            => true,
					'flex-width'             => false,
					'default-text-color'     => 'ffffff',
					'default-image'          => 'wilbur',
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

		// Custom logo.
		$logo_width  = 120;
		$logo_height = 90;

		// If the retina setting is active, double the recommended width and height.
		if ( get_theme_mod( 'retina_logo', false ) ) {
			$logo_width  = floor( $logo_width * 2 );
			$logo_height = floor( $logo_height * 2 );
		}

		add_theme_support(
			'custom-logo',
			[
				'height'      => $logo_height,
				'width'       => $logo_width,
				'flex-height' => true,
				'flex-width'  => true,
			]
		);

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			[
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'script',
				'style',
			]
		);

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Wilbur, use a find and replace
		 * to change 'wilbur' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'wilbur' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );

		/*
		 * Adds starter content to highlight the theme on fresh sites.
		 * This is done conditionally to avoid loading the starter content on every
		 * page load, as it is a one-off operation only needed once in the customizer.
		 */
		if ( is_customize_preview() ) {
			require get_theme_file_path( '/includes/starter-content.php' );
			add_theme_support( 'starter-content', wilbur_get_starter_content() );
		}

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/*
		 * Adds `async` and `defer` support for scripts registered or enqueued
		 * by the theme.
		 */
		$loader = new \Wilbur_Script_Loader();
		add_filter( 'script_loader_tag', [ $loader, 'filter_script_loader_tag' ], 10, 2 );
	}

	/**
	 * Enqueue frontend styles
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function frontend_styles() {

		// Get theme version.
		$theme_version  = wp_get_theme()->get( 'Version' );

		/**
		 * Theme sylesheet
		 *
		 * This enqueues the minified stylesheet compiled from SASS (.scss) files.
		 * The main stylesheet, in the root directory, only contains the theme header but
		 * it is necessary for theme activation. DO NOT delete the main stylesheet!
		 */
		wp_enqueue_style( 'wilbur', get_theme_file_uri( 'assets/css/style.min.css' ), [], $theme_version, 'all' );

		// Right-to-left languages.
		if ( is_rtl() ) {
			wp_enqueue_style( 'wilbur-rtl', get_theme_file_uri( 'assets/css/rtl.min.css' ), [ 'wilbur' ], $theme_version, 'all' );
		}

		/**
		 * Dark mode styles
		 *
		 * These syles are enqueued if the user's device prefers dark mode.
		 * Same as the dark mode option in the Customizer.
		 */
		wp_enqueue_style( 'wilbur-dark', get_theme_file_uri( 'assets/css/dark-mode.min.css' ), [ 'wilbur' ], $theme_version, '(prefers-color-scheme: dark)' );

		// Toolbar styles.
		wp_enqueue_style( 'wilbur-toolbar', get_theme_file_uri( 'assets/css/toolbar.min.css' ), [ 'wilbur' ], $theme_version, 'all' );
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
		wp_enqueue_style( 'wilbur-admin', get_theme_file_uri( 'assets/css/admin.css' ), [], $theme_version, 'all' );

		// WordPress 5.4 + styles.
		if ( version_compare( $wp_version, '5.4', '>=' ) ) {
			wp_enqueue_style( 'wilbur-edit-page', get_theme_file_uri( 'assets/css/edit-page.css' ), [], $theme_version, 'all' );
		} else {
			wp_enqueue_style( 'wilbur-edit-page', get_theme_file_uri( 'assets/css/edit-page-early.css' ), [], $theme_version, 'all' );
		}

		// Right-to-left languages.
		if ( is_rtl() ) {
			wp_enqueue_style( 'wilbur-admin-rtl', get_theme_file_uri( 'assets/css/admin-rtl.css' ), [ 'wilbur-admin' ], $theme_version, 'all' );
		}

		// Toolbar styles.
		wp_enqueue_style( 'wilbur-toolbar', get_theme_file_uri( 'assets/css/toolbar.css' ), [ 'admin-bar' ], $theme_version, 'all' );
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

		wp_enqueue_script( 'wilbur-customize', get_theme_file_uri( 'assets/js/customize.min.js' ), [], $theme_version, true );
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

		wp_enqueue_style( 'wilbur-customize', get_theme_file_uri( 'assets/css/customize.min.css' ), [], $theme_version, 'all' );
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

		wp_enqueue_style( 'wilbur-login', get_theme_file_uri( 'assets/css/login.min.css' ), [], $theme_version, 'all' );
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
	 * Wilbur theme page
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function theme_page() {

		// Add a submenu page under Themes.
		$this->theme_page = add_theme_page(
			__( 'Wilbur Theme Information', 'wilbur' ),
			__( 'Wilbur Theme', 'wilbur' ),
			'manage_options',
			'wilbur',
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
	 * Passes through the `wilbur_starter_content` filter before returning.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $starter_content Array of starter content added by the parent theme.
	 * @return array Returns a filtered array of args for the starter_content.
	 */
	public function starter_content( $starter_content ) {

		// Define and register starter content to showcase the theme on new sites.
		require get_theme_file_path( '/inc/starter-content.php' );
		$starter_content = \Wilbur\Includes\starter_content();

		return $starter_content;
	}
}