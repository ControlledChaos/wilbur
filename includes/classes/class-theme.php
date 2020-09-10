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
		return new self;
	}

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Remove unpopular meta tags.
		add_action( 'init', [ $this, 'head_cleanup' ] );

		// Theme setup.
		add_action( 'after_setup_theme', [ $this, 'setup' ], 11 );

		// Add navigation menus.
		add_action( 'init', [ $this, 'menus' ] );

		// Register widget areas.
		add_action( 'widgets_init', [ $this, 'widgets' ] );

		// Frontend scripts.
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );

		// Print frontend scripts.
		add_action( 'wp_print_footer_scripts', [ $this, 'print_frontend_scripts' ] );

		// Frontend styles.
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_styles' ] );

		// Backend styles.
		add_action( 'admin_enqueue_scripts', [ $this, 'backend_styles' ], 99 );

		// Editor styles.
		add_action( 'init', [ $this, 'editor_styles' ] );

		// Editor styles from Customizer.
		add_filter( 'tiny_mce_before_init', [ $this, 'editor_customizer_styles' ] );

		// Editor non-latin styles.
		add_filter( 'tiny_mce_before_init',  [ $this, 'editor_non_latin_styles' ] );

		// Block editor styles.
		add_action( 'enqueue_block_editor_assets', [ $this, 'block_editor_styles' ], 1, 1 );

		// Customizer scripts.
		add_action( 'customize_controls_print_footer_scripts', [ $this, 'customize_scripts' ] );

		// Customizer styles.
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'customize_styles' ] );

		// Customizer preview.
		add_action( 'customize_preview_init', [ $this, 'customize_preview_init' ] );

		// Login styles.
		add_action( 'login_enqueue_scripts', [ $this, 'login_styles' ] );

		// Get the information about the logo.
		add_filter( 'get_custom_logo', [ $this, 'custom_logo' ] );

		// Skiip link.
		add_action( 'wilbur_body_open', [ $this, 'skip_link' ], 5 );

		// Read more link.
		add_filter( 'the_content_more_link', 'read_more' );

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
	 * Clean up meta tags from the <head>
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function head_cleanup() {

		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_generator' );
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
			$content_width = 1024;
		}

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

		// Set post thumbnail size.
		set_post_thumbnail_size( 1280, 720, [ 'center', 'center' ] );

		// Extra-large image the same as the content width.
		add_image_size( 'extra-large', 1024, 768, true );

		// Add custom image size used in Cover Template.
		add_image_size( 'cover-image', 1920, 1080, true );

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
			// require get_theme_file_path( '/includes/starter-content.php' );
			// add_theme_support( 'starter-content', wilbur_get_starter_content() );
		}

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/*
		 * Adds `async` and `defer` support for scripts registered or enqueued
		 * by the theme.
		 */
		$loader = new Script_Loader();
		add_filter( 'script_loader_tag', [ $loader, 'filter_script_loader_tag' ], 10, 2 );

		/**
		 * Block editor settings
		 *
		 * Add custom colors and font sizes to the block editor.
		 */
		$accent    = $this->get_color_for_area( 'content', 'accent' );
		$primary   = $this->get_color_for_area( 'content', 'text' );
		$secondary = $this->get_color_for_area( 'content', 'secondary' );
		$subtle    = $this->get_color_for_area( 'content', 'borders' );

		// Block Editor Palette.
		$editor_color_palette = [
			[
				'name'  => __( 'Accent Color', 'wilbur' ),
				'slug'  => 'accent',
				'color' => $accent,
			],
			[
				'name'  => __( 'Primary', 'wilbur' ),
				'slug'  => 'primary',
				'color' => $primary,
			],
			[
				'name'  => __( 'Secondary', 'wilbur' ),
				'slug'  => 'secondary',
				'color' => $secondary,
			],
			[
				'name'  => __( 'Subtle Background', 'wilbur' ),
				'slug'  => 'subtle-background',
				'color' => $subtle,
			],
		];

		// Add the background option.
		$background_color = get_theme_mod( 'background_color' );
		if ( ! $background_color ) {
			$background_color_arr = get_theme_support( 'custom-background' );
			$background_color     = $background_color_arr[0]['default-color'];
		}
		$editor_color_palette[] = [
			'name'  => __( 'Background Color', 'wilbur' ),
			'slug'  => 'background',
			'color' => '#' . $background_color,
		];

		// If we have accent colors, add them to the block editor palette.
		if ( $editor_color_palette ) {
			add_theme_support( 'editor-color-palette', $editor_color_palette );
		}

		// Block Editor Font Sizes.
		add_theme_support(
			'editor-font-sizes',
			[
				[
					'name'      => _x( 'Small', 'Name of the small font size in the block editor', 'wilbur' ),
					'shortName' => _x( 'S', 'Short name of the small font size in the block editor.', 'wilbur' ),
					'size'      => 18,
					'slug'      => 'small',
				],
				[
					'name'      => _x( 'Regular', 'Name of the regular font size in the block editor', 'wilbur' ),
					'shortName' => _x( 'M', 'Short name of the regular font size in the block editor.', 'wilbur' ),
					'size'      => 21,
					'slug'      => 'normal',
				],
				[
					'name'      => _x( 'Large', 'Name of the large font size in the block editor', 'wilbur' ),
					'shortName' => _x( 'L', 'Short name of the large font size in the block editor.', 'wilbur' ),
					'size'      => 26.25,
					'slug'      => 'large',
				],
				[
					'name'      => _x( 'Larger', 'Name of the larger font size in the block editor', 'wilbur' ),
					'shortName' => _x( 'XL', 'Short name of the larger font size in the block editor.', 'wilbur' ),
					'size'      => 32,
					'slug'      => 'larger',
				],
			]
		);

		add_theme_support( 'editor-styles' );

		// If we have a dark background color then add support for dark editor style.
		// We can determine if the background color is dark by checking if the text-color is white.
		if ( '#ffffff' === strtolower( $this->get_color_for_area( 'content', 'text' ) ) ) {
			add_theme_support( 'dark-editor-style' );
		}
	}

	/**
	 * Register navigation menus
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function menus() {

		$locations = [
			'primary'  => __( 'Desktop Horizontal Menu', 'wilbur' ),
			'expanded' => __( 'Desktop Expanded Menu', 'wilbur' ),
			'mobile'   => __( 'Mobile Menu', 'wilbur' ),
			'footer'   => __( 'Footer Menu', 'wilbur' ),
			'social'   => __( 'Social Menu', 'wilbur' ),
		];

		register_nav_menus( $locations );
	}

	/**
	 * Register widget areas
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
					'name'        => __( 'Footer #1', 'wilbur' ),
					'id'          => 'sidebar-1',
					'description' => __( 'Widgets in this area will be displayed in the first column in the footer.', 'wilbur' ),
				]
			)
		);

		// Footer #2.
		register_sidebar(
			array_merge(
				$shared_args,
				[
					'name'        => __( 'Footer #2', 'wilbur' ),
					'id'          => 'sidebar-2',
					'description' => __( 'Widgets in this area will be displayed in the second column in the footer.', 'wilbur' ),
				]
			)
		);
	}

	/**
	 * Enqueue frontend scripts
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function frontend_scripts() {

		$theme_version = wp_get_theme()->get( 'Version' );

		if ( ( ! is_admin() ) && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		wp_enqueue_script( 'wilbur-js', get_theme_file_uri( 'assets/js/index.min.js' ), [], $theme_version, false );
		wp_script_add_data( 'wilbur-js', 'async', true );

		// Tabbed content script.
		wp_enqueue_script( 'wilbur-tabs', get_theme_file_uri( 'assets/js/theme-tabs.min.js' ), [ 'jquery' ], $theme_version, true );
	}

	/**
	 * Enqueue frontend scripts
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function print_frontend_scripts() {

		// The following is minified via `terser --compress --mangle -- assets/js/skip-link-focus-fix.js`.
		?>
		<script>
		/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);

		jQuery( document ).ready( function($) {

			var href   = window.location.href,
				noHash = window.location.href.replace( /#.*$/, '' );

			$( '.to-the-content' ).click( function(event) {
				event.preventDefault();
				$( 'html, body' ).stop().animate( { scrollTop: $( '#post-inner' ).offset().top }, '250' );
				window.history.replaceState( '', document.title, noHash );
			});

			$( '.to-the-top' ).click( function(event) {
				event.preventDefault();
				$( 'html, body' ).stop().animate( { scrollTop: $( 'html' ).offset().top }, 'fast' );
				window.history.replaceState( '', document.title, noHash );
			});

			// Check to see if the window is top if not then display button.
			$( window ).scroll( function() {
				if ( $(this).scrollTop() > 450 ) {
					$( '.to-the-top' ).fadeIn( '250' );
				} else {
					$( '.to-the-top' ).fadeOut( '250' );
				}
			});
		});
		</script>
		<?php
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

		// Get non-latin CSS.
		$non_latin = Non_Latin :: get_non_latin_css( 'front-end' );

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

		wp_enqueue_style( 'wilbur-style', get_stylesheet_uri(), [ 'wilbur' ], $theme_version, 'all' );
		wp_style_add_data( 'wilbur-style', 'rtl', 'replace' );

		// Add output of Customizer settings as inline style.
		wp_add_inline_style( 'wilbur-style', wilbur_get_customizer_css( 'front-end' ) );

		if ( $non_latin ) {
			wp_add_inline_style( 'wilbur-style', $non_latin );
		}

		// Add print CSS.
		wp_enqueue_style( 'wilbur-print-style', get_theme_file_uri( 'print.css' ), null, $theme_version, 'print' );

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
	 * Editor styles
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function editor_styles() {

		$classic_editor_styles = [
			'/assets/css/editor-style-classic.css',
		];

		add_editor_style( $classic_editor_styles );
	}

	/**
	 * Editor styles from Customizer
	 *
	 * Adds styles to the head of the TinyMCE iframe.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $mce_init TinyMCE styles.
	 * @return array TinyMCE styles.
	 * @return void
	 */
	public function editor_customizer_styles( $mce_init ) {

		$styles = wilbur_get_customizer_css( 'classic-editor' );

		if ( ! isset( $mce_init['content_style'] ) ) {
			$mce_init['content_style'] = $styles . ' ';
		} else {
			$mce_init['content_style'] .= ' ' . $styles . ' ';
		}

		return $mce_init;
	}

	/**
	 * Editor non-latin styles
	 *
	 * Adds styles to the head of the TinyMCE iframe.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $mce_init TinyMCE styles.
	 * @return array TinyMCE styles.
	 */
	public function editor_non_latin_styles( $mce_init ) {

		$styles = Non_Latin :: get_non_latin_css( 'classic-editor' );

		// Return if there are no styles to add.
		if ( ! $styles ) {
			return $mce_init;
		}

		if ( ! isset( $mce_init['content_style'] ) ) {
			$mce_init['content_style'] = $styles . ' ';
		} else {
			$mce_init['content_style'] .= ' ' . $styles . ' ';
		}

		return $mce_init;
	}

	/**
	 * Block editor styles
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function block_editor_styles() {

		// Enqueue the editor styles.
		wp_enqueue_style( 'wilbur-block-editor-styles', get_theme_file_uri( '/assets/css/editor-style-block.css' ), [], wp_get_theme()->get( 'Version' ), 'all' );
		wp_style_add_data( 'wilbur-block-editor-styles', 'rtl', 'replace' );

		// Add inline style from the Customizer.
		wp_add_inline_style( 'wilbur-block-editor-styles', wilbur_get_customizer_css( 'block-editor' ) );

		// Add inline style for non-latin fonts.
		wp_add_inline_style( 'wilbur-block-editor-styles', Non_Latin :: get_non_latin_css( 'block-editor' ) );

		// Enqueue the editor script.
		wp_enqueue_script( 'wilbur-block-editor-script', get_theme_file_uri( '/assets/js/editor-script-block.js' ), [ 'wp-blocks', 'wp-dom' ], wp_get_theme()->get( 'Version' ), true );
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

		// Add main customizer js file.
		wp_enqueue_script( 'wilbur-customize', get_theme_file_uri( '/assets/js/customize.js' ), [ 'jquery' ], $theme_version, false );

		// Add script for color calculations.
		wp_enqueue_script( 'wilbur-color-calculations', get_theme_file_uri( '/assets/js/color-calculations.js' ), [ 'wp-color-picker' ], $theme_version, false );

		// Add script for controls.
		wp_enqueue_script( 'wilbur-customize-controls', get_theme_file_uri( '/assets/js/customize-controls.js' ), [ 'wilbur-color-calculations', 'customize-controls', 'underscore', 'jquery' ], $theme_version, false );
		wp_localize_script( 'wilbur-customize-controls', 'twentyTwentyBgColors', $this->get_customizer_color_vars() );

		wp_enqueue_style( 'wilbur-customize', get_theme_file_uri( 'assets/css/customize.min.css' ), [], $theme_version, 'all' );
	}

	/**
	 * Enqueue scripts for the customizer preview.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function customize_preview_init() {

		$theme_version = wp_get_theme()->get( 'Version' );

		wp_enqueue_script( 'wilbur-customize-preview', get_theme_file_uri( '/assets/js/customize-preview.js' ), [ 'customize-preview', 'customize-selective-refresh', 'jquery' ], $theme_version, true );
		wp_localize_script( 'wilbur-customize-preview', 'twentyTwentyBgColors', $this->get_customizer_color_vars() );
		wp_localize_script( 'wilbur-customize-preview', 'twentyTwentyPreviewEls', $this->get_elements_array() );

		wp_add_inline_script(
			'wilbur-customize-preview',
			sprintf(
				'wp.customize.selectiveRefresh.partialConstructor[ %1$s ].prototype.attrs = %2$s;',
				wp_json_encode( 'cover_opacity' ),
				wp_json_encode( wilbur_customize_opacity_range() )
			)
		);
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
	 * Get accessible color for an area
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $area The area we want to get the colors for.
	 * @param  string $context Can be 'text' or 'accent'.
	 * @return string Returns a HEX color.
	 */
	public function get_color_for_area( $area = 'content', $context = 'text' ) {

		// Get the value from the theme-mod.
		$settings = get_theme_mod(
			'accent_accessible_colors',
			[
				'content'       => [
					'text'      => '#2b2b21',
					'accent'    => '#b52712',
					'secondary' => '#6d6d6d',
					'borders'   => '#dcd7ca',
				],
				'headings'       => [
					'text'      => '#302a1e',
					'accent'    => '#b52712',
					'secondary' => '#6d6d6d',
					'borders'   => '#dcd7ca',
				],
				'header-footer' => [
					'text'      => '#302a1e',
					'accent'    => '#b52712',
					'secondary' => '#6d6d6d',
					'borders'   => '#dcd7ca',
				],
			]
		);

		// If we have a value return it.
		if ( isset( $settings[ $area ] ) && isset( $settings[ $area ][ $context ] ) ) {
			return $settings[ $area ][ $context ];
		}

		// Return false if the option doesn't exist.
		return false;
	}

	/**
	 * Returns an array of variables for the customizer preview.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_customizer_color_vars() {

		$colors = [
			'content'       => [
				'setting' => 'background_color',
			],
			'header-footer' => [
				'setting' => 'header_footer_background_color',
			],
		];
		return $colors;
	}

	/**
	 * Get an array of elements.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_elements_array() {

		// The array is formatted like this:
		// [key-in-saved-setting][sub-key-in-setting][css-property] = [elements].
		$elements = [
			'content' => [
				'accent' => [

					'color' => [ '.color-accent', '.color-accent-hover:hover', '.color-accent-hover:focus', ':root .has-accent-color', '.has-drop-cap:not(:focus):first-letter', '.wp-block-button.is-style-outline', 'a' ],

					'border-color' => [ 'blockquote', '.border-color-accent', '.border-color-accent-hover:hover', '.border-color-accent-hover:focus' ],

					'background-color' => [ 'button', '.button', '.faux-button', '.wp-block-button__link', '.wp-block-file .wp-block-file__button', 'input[type="button"]', 'input[type="reset"]', 'input[type="submit"]', '.bg-accent', '.bg-accent-hover:hover', '.bg-accent-hover:focus', ':root .has-accent-background-color', '.comment-reply-link' ],

					'fill' => [ '.fill-children-accent', '.fill-children-accent *' ],
				],
				'background' => [

					'color' => [ ':root .has-background-color', 'button', '.button', '.faux-button', '.wp-block-button__link', '.wp-block-file__button', 'input[type="button"]', 'input[type="reset"]', 'input[type="submit"]', '.wp-block-button', '.comment-reply-link', '.has-background.has-primary-background-color:not(.has-text-color)', '.has-background.has-primary-background-color *:not(.has-text-color)', '.has-background.has-accent-background-color:not(.has-text-color)', '.has-background.has-accent-background-color *:not(.has-text-color)' ],

					'background-color' => [ ':root .has-background-background-color' ],
				],
				'text' => [

					'color' => [ 'body', '.entry-title a', ':root .has-primary-color' ],

					'background-color'    => [ 'body:not(.overlay-header) .primary-menu ul', ':root .has-primary-background-color' ],
					'border-bottom-color' => [ 'body:not(.overlay-header) .primary-menu > li > ul:after' ],
					'border-left-color'   => [ 'body:not(.overlay-header) .primary-menu ul ul:after' ],
				],
				'secondary' => [

					'color' => [ 'cite', 'figcaption', '.wp-caption-text', '.post-meta', '.entry-content .wp-block-archives li', '.entry-content .wp-block-categories li', '.entry-content .wp-block-latest-posts li', '.wp-block-latest-comments__comment-date', '.wp-block-latest-posts__post-date', '.wp-block-embed figcaption', '.wp-block-image figcaption', '.wp-block-pullquote cite', '.comment-metadata', '.comment-respond .comment-notes', '.comment-respond .logged-in-as', '.pagination .dots', '.entry-content hr:not(.has-background)', 'hr.styled-separator', ':root .has-secondary-color' ],

					'background-color' => [ ':root .has-secondary-background-color' ],
				],
				'borders' => [

					'border-color'        => [ 'pre', 'fieldset', 'input', 'textarea', 'table', 'table *', 'hr' ],

					'background-color'    => [ 'caption', 'code', 'code', 'kbd', 'samp', '.wp-block-table.is-style-stripes tbody tr:nth-child(odd)', ':root .has-subtle-background-background-color' ],

					'border-bottom-color' => [ '.wp-block-table.is-style-stripes' ],
					'border-top-color'    => [ '.wp-block-latest-posts.is-grid li' ],
					'color'               => [ ':root .has-subtle-background-color' ],
				],
			],
			'headings' => [
				'text' => [
					'color' => [ '.entry-content h2', '.entry-content h3', '.entry-content h4' ],
				],
			],
			'header-footer' => [
				'accent' => [
					'color' => [ 'body:not(.overlay-header) .primary-menu > li', 'body:not(.overlay-header) .primary-menu > li > .icon', '.modal-menu a', '.footer-menu a, .footer-widgets a', '#site-footer .wp-block-button.is-style-outline', '.wp-block-pullquote:before', '.singular:not(.overlay-header) .entry-header a', '.archive-header a', '.header-footer-group .color-accent', '.header-footer-group .color-accent-hover:hover' ],

					'background-color' => [ '.social-icons a', '#site-footer button:not(.toggle)', '#site-footer .button', '#site-footer .faux-button', '#site-footer .wp-block-button__link', '#site-footer .wp-block-file__button', '#site-footer input[type="button"]', '#site-footer input[type="reset"]', '#site-footer input[type="submit"]' ],
				],
				'background' => [

					'color' => [ '.social-icons a', '.header-footer-group button', '.header-footer-group .button', '.header-footer-group .faux-button', '.header-footer-group .wp-block-button:not(.is-style-outline) .wp-block-button__link', '.header-footer-group .wp-block-file__button', '.header-footer-group input[type="button"]', '.header-footer-group input[type="reset"]', '.header-footer-group input[type="submit"]' ],

					'background-color' => [ '#site-header', '.footer-nav-widgets-wrapper', '#site-footer', '.menu-modal', '.menu-modal-inner', '.search-modal-inner', '.archive-header', '.singular .entry-header', '.singular .featured-media:before', '.wp-block-pullquote:before' ],
				],
				'text' => [
					'color'               => [ '.header-footer-group', 'body:not(.overlay-header) #site-header .toggle', '.menu-modal .toggle' ],
				],
				'secondary'  => [

					'color' => [ '.site-description', 'body:not(.overlay-header) .toggle-inner .toggle-text', '.widget .post-date', '.widget .rss-date', '.widget_archive li', '.widget_categories li', '.widget cite', '.widget_pages li', '.widget_meta li', '.widget_nav_menu li', '.singular .entry-header .post-meta', '.singular:not(.overlay-header) .entry-header .post-meta a' ],
				],
				'borders' => [

					'border-color'     => [ '.header-footer-group pre', '.header-footer-group fieldset', '.header-footer-group input', '.header-footer-group textarea', '.header-footer-group table', '.header-footer-group table *', '.footer-nav-widgets-wrapper', '#site-footer', '.menu-modal nav *', '.footer-widgets-outer-wrapper', '.footer-top' ],

					'background-color' => [ '.header-footer-group table caption', 'body:not(.overlay-header) .header-inner .toggle-wrapper::before' ],
				],
			],
		];

		/**
		* Filters Wilbur theme elements
		*
		* @since 1.0.0
		*
		* @param array Array of elements
		*/
		return apply_filters( 'wilbur_get_elements_array', $elements );
	}

	/**
	 * Get the information about the logo.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $html The HTML output from get_custom_logo (core function).
	 * @return string
	 */
	public function custom_logo( $html ) {

		$logo_id = get_theme_mod( 'custom_logo' );

		if ( ! $logo_id ) {
			return $html;
		}

		$logo = wp_get_attachment_image_src( $logo_id, 'full' );

		if ( $logo ) {
			// For clarity.
			$logo_width  = esc_attr( $logo[1] );
			$logo_height = esc_attr( $logo[2] );

			// If the retina logo setting is active, reduce the width/height by half.
			if ( get_theme_mod( 'retina_logo', false ) ) {
				$logo_width  = floor( $logo_width / 2 );
				$logo_height = floor( $logo_height / 2 );

				$search = [
					'/width=\"\d+\"/iU',
					'/height=\"\d+\"/iU',
				];

				$replace = [
					"width=\"{$logo_width}\"",
					"height=\"{$logo_height}\"",
				];

				// Add a style attribute with the height, or append the height to the style attribute if the style attribute already exists.
				if ( strpos( $html, ' style=' ) === false ) {
					$search[]  = '/(src=)/';
					$replace[] = "style=\"height: {$logo_height}px;\" src=";
				} else {
					$search[]  = '/(style="[^"]*)/';
					$replace[] = "$1 height: {$logo_height}px;";
				}

				$html = preg_replace( $search, $replace, $html );

			}
		}

		return $html;

	}

	/**
	 * Skip link
	 *
	 * Includes a skip to content link at the top of the page
	 * so that users can bypass the menu.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the markup of the link.
	 */
	public function skip_link() {
		echo '<a class="skip-link screen-reader-text" href="#site-content">' . __( 'Skip to the content', 'wilbur' ) . '</a>';
	}

	/**
	 * Read more link
	 *
	 * Overwrites the default more tag with styling and screen reader markup.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $html The default output HTML for the more tag.
	 * @return string
	 */
	public function read_more( $html ) {

		return preg_replace(
			'/<a(.*)>(.*)<\/a>/iU',
			sprintf(
				'<div class="read-more-button-wrap"><a$1><span class="faux-button">$2</span> <span class="screen-reader-text">"%1$s"</span></a></div>',
				get_the_title( get_the_ID() )
			),
			$html
		);
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
		require get_theme_file_path( '/includes/starter-content.php' );
		$starter_content = \Wilbur\Includes\starter_content();

		return $starter_content;
	}
}
