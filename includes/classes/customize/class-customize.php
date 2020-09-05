<?php
/**
 * Theme Customize class
 *
 * Registers settings and controls.
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

class Customize {

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

		// Add Customizer settings and controls
		add_action( 'customize_register', [ $this, 'register' ], 11 );
	}

	/**
	 * Register Customizer settings and controls
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @return void
	 */
	public static function register( $wp_customize ) {

		// New instance of the core theme class.
		$theme = new Theme;
		new Customize_Sanitize;

		/**
		 * Site Title & Description.
		 * */
		$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

		$wp_customize->selective_refresh->add_partial(
			'blogname',
			[
				'selector'        => '.site-title a',
				'render_callback' => 'wilbur_customize_partial_blogname',
			]
		);

		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			[
				'selector'        => '.site-description',
				'render_callback' => 'wilbur_customize_partial_blogdescription',
			]
		);

		$wp_customize->selective_refresh->add_partial(
			'custom_logo',
			[
				'selector'        => '.header-titles [class*=site-]:not(.site-description)',
				'render_callback' => 'wilbur_customize_partial_site_logo',
			]
		);

		$wp_customize->selective_refresh->add_partial(
			'retina_logo',
			[
				'selector'        => '.header-titles [class*=site-]:not(.site-description)',
				'render_callback' => 'wilbur_customize_partial_site_logo',
			]
		);

		/**
		 * Site Identity
		 */

		/* 2X Header Logo ---------------- */
		$wp_customize->add_setting(
			'retina_logo',
			[
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => [ 'Wilbur\Classes\Customize_Sanitize', 'sanitize_checkbox' ],
				'transport'         => 'postMessage',
			]
		);

		$wp_customize->add_control(
			'retina_logo',
			[
				'type'        => 'checkbox',
				'section'     => 'title_tagline',
				'priority'    => 10,
				'label'       => __( 'Retina logo', 'wilbur' ),
				'description' => __( 'Scales the logo to half its uploaded size, making it sharp on high-res screens.', 'wilbur' ),
			]
		);

		// Header & Footer Background Color.
		$wp_customize->add_setting(
			'header_footer_background_color',
			[
				'default'           => '#ffffff',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			]
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'header_footer_background_color',
				[
					'label'   => __( 'Header &amp; Footer Background Color', 'wilbur' ),
					'section' => 'colors',
				]
			)
		);

		// Enable picking an accent color.
		$wp_customize->add_setting(
			'accent_hue_active',
			[
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => [ 'Wilbur\Classes\Customize_Sanitize', 'sanitize_select' ],
				'transport'         => 'postMessage',
				'default'           => 'default',
			]
		);

		$wp_customize->add_control(
			'accent_hue_active',
			[
				'type'    => 'radio',
				'section' => 'colors',
				'label'   => __( 'Primary Color', 'wilbur' ),
				'choices' => [
					'default' => __( 'Default', 'wilbur' ),
					'custom'  => __( 'Custom', 'wilbur' ),
				],
			]
		);

		/**
		 * Implementation for the accent color.
		 * This is different to all other color options because of the accessibility enhancements.
		 * The control is a hue-only colorpicker, and there is a separate setting that holds values
		 * for other colors calculated based on the selected hue and various background-colors on the page.
		 *
		 * @since 1.0.0
		 */

		// Add the setting for the hue colorpicker.
		$wp_customize->add_setting(
			'accent_hue',
			[
				'default'           => 344,
				'type'              => 'theme_mod',
				'sanitize_callback' => 'absint',
				'transport'         => 'postMessage',
			]
		);

		// Add setting to hold colors derived from the accent hue.
		$wp_customize->add_setting(
			'accent_accessible_colors',
			[
				'default'           => [
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
				],
				'type'              => 'theme_mod',
				'transport'         => 'postMessage',
				'sanitize_callback' => [ 'Wilbur\Classes\Customize_Sanitize', 'sanitize_accent_accessible_colors' ],
			]
		);

		// Add the hue-only colorpicker for the accent color.
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'accent_hue',
				[
					'section'         => 'colors',
					'settings'        => 'accent_hue',
					'description'     => __( 'Apply a custom color for links, buttons, featured images.', 'wilbur' ),
					'mode'            => 'hue',
					'active_callback' => function() use ( $wp_customize ) {
						return ( 'custom' === $wp_customize->get_setting( 'accent_hue_active' )->value() );
					},
				]
			)
		);

		// Update background color with postMessage, so inline CSS output is updated as well.
		$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';

		/**
		 * Theme Options
		 */

		$wp_customize->add_section(
			'options',
			[
				'title'      => __( 'Theme Options', 'wilbur' ),
				'priority'   => 40,
				'capability' => 'edit_theme_options',
			]
		);

		/* Enable Header Search ----------------------------------------------- */

		$wp_customize->add_setting(
			'enable_header_search',
			[
				'capability'        => 'edit_theme_options',
				'default'           => true,
				'sanitize_callback' => [ 'Wilbur\Classes\Customize_Sanitize', 'sanitize_checkbox' ],
			]
		);

		$wp_customize->add_control(
			'enable_header_search',
			[
				'type'     => 'checkbox',
				'section'  => 'options',
				'priority' => 10,
				'label'    => __( 'Show search in header', 'wilbur' ),
			]
		);

		/* Show author bio ---------------------------------------------------- */

		$wp_customize->add_setting(
			'show_author_bio',
			[
				'capability'        => 'edit_theme_options',
				'default'           => true,
				'sanitize_callback' => [ 'Wilbur\Classes\Customize_Sanitize', 'sanitize_checkbox' ],
			]
		);

		$wp_customize->add_control(
			'show_author_bio',
			[
				'type'     => 'checkbox',
				'section'  => 'options',
				'priority' => 10,
				'label'    => __( 'Show author bio', 'wilbur' ),
			]
		);

		/* Display full content or excerpts on the blog and archives --------- */

		$wp_customize->add_setting(
			'blog_content',
			[
				'capability'        => 'edit_theme_options',
				'default'           => 'full',
				'sanitize_callback' => [ 'Wilbur\Classes\Customize_Sanitize', 'sanitize_select' ],
			]
		);

		$wp_customize->add_control(
			'blog_content',
			[
				'type'     => 'radio',
				'section'  => 'options',
				'priority' => 10,
				'label'    => __( 'On archive pages, posts show:', 'wilbur' ),
				'choices'  => [
					'full'    => __( 'Full text', 'wilbur' ),
					'summary' => __( 'Summary', 'wilbur' ),
				],
			]
		);

		/**
		 * Template: Cover Template.
		 */
		$wp_customize->add_section(
			'cover_template_options',
			[
				'title'       => __( 'Cover Template', 'wilbur' ),
				'capability'  => 'edit_theme_options',
				'description' => __( 'Settings for the "Cover Template" page template. Add a featured image to use as background.', 'wilbur' ),
				'priority'    => 42,
			]
		);

		/* Overlay Fixed Background ------ */

		$wp_customize->add_setting(
			'cover_template_fixed_background',
			[
				'capability'        => 'edit_theme_options',
				'default'           => true,
				'sanitize_callback' => [ 'Wilbur\Classes\Customize_Sanitize', 'sanitize_checkbox' ],
				'transport'         => 'postMessage',
			]
		);

		$wp_customize->add_control(
			'cover_template_fixed_background',
			[
				'type'        => 'checkbox',
				'section'     => 'cover_template_options',
				'label'       => __( 'Fixed Background Image', 'wilbur' ),
				'description' => __( 'Creates a parallax effect when the visitor scrolls.', 'wilbur' ),
			]
		);

		$wp_customize->selective_refresh->add_partial(
			'cover_template_fixed_background',
			[
				'selector' => '.cover-header',
				'type'     => 'cover_fixed',
			]
		);

		/* Separator --------------------- */

		$wp_customize->add_setting(
			'cover_template_separator_1',
			[
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			]
		);

		$wp_customize->add_control(
			new \Wilbur\Classes\Separator_Control(
				$wp_customize,
				'cover_template_separator_1',
				[
					'section' => 'cover_template_options',
				]
			)
		);

		/* Overlay Background Color ------ */
		$accent = $theme->get_color_for_area( 'content', 'accent' );
		$wp_customize->add_setting(
			'cover_template_overlay_background_color',
			[
				'default'           => $accent,
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'cover_template_overlay_background_color',
				[
					'label'       => __( 'Overlay Background Color', 'wilbur' ),
					'description' => __( 'The color used for the overlay. Defaults to the accent color.', 'wilbur' ),
					'section'     => 'cover_template_options',
				]
			)
		);

		/* Overlay Text Color ------------ */

		$wp_customize->add_setting(
			'cover_template_overlay_text_color',
			[
				'default'           => '#ffffff',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'cover_template_overlay_text_color',
				[
					'label'       => __( 'Overlay Text Color', 'wilbur' ),
					'description' => __( 'The color used for the text in the overlay.', 'wilbur' ),
					'section'     => 'cover_template_options',
				]
			)
		);

		/* Overlay Color Opacity --------- */

		$wp_customize->add_setting(
			'cover_template_overlay_opacity',
			[
				'default'           => 50,
				'sanitize_callback' => 'absint',
				'transport'         => 'postMessage',
			]
		);

		$wp_customize->add_control(
			'cover_template_overlay_opacity',
			[
				'label'       => __( 'Overlay Opacity', 'wilbur' ),
				'description' => __( 'Make sure that the contrast is high enough so that the text is readable.', 'wilbur' ),
				'section'     => 'cover_template_options',
				'type'        => 'range',
				'input_attrs' => wilbur_customize_opacity_range(),
			]
		);

		$wp_customize->selective_refresh->add_partial(
			'cover_template_overlay_opacity',
			[
				'selector' => '.cover-color-overlay',
				'type'     => 'cover_opacity',
			]
		);

		/**
		 * Headers panel
		 *
		 * @since 1.0.0
		 */
		$wp_customize->add_panel(
			'theme_header',
			[
				'title'       => __( 'Headers', 'wilbur' ),
				'description' => sprintf(
					'<p>%1s</p>',
					__( '', 'wilbur' )
				),
				'priority'    => 21,
				'capability'  => 'edit_theme_options',
			]
		);

		/**
		 * Theme Options panel
		 *
		 * @since 1.0.0
		 */
		$wp_customize->remove_section( 'options' );
		$wp_customize->add_panel(
			'theme_options',
			[
				'title'       => __( 'Appearance', 'wilbur' ),
				'description' => sprintf(
					'<p>%1s <a href="%2s">%3s</a> %4s</p>',
					__( 'These options enhance those included with the', 'wilbur' ),
					esc_url( admin_url( 'customize.php?url=' . site_url() . '&theme=wilbur' ) ),
					__( 'Twenty Twenty', 'wilbur' ),
					__( 'parent theme.', 'wilbur' )
				),
				'priority'    => 22,
				'capability'  => 'edit_theme_options',
			]
		);

		/**
		 * Content panel
		 *
		 * @since 1.0.0
		 */
		$wp_customize->add_panel(
			'content_options',
			[
				'title'      => __( 'Content', 'wilbur' ),
				'description' => sprintf(
					'<p>%1s</p>',
					__( '', 'wilbur' )
				),
				'priority'   => 23,
				'capability' => 'edit_theme_options',
			]
		);

		/**
		 * Move core & parent theme sections
		 *
		 * Relocate to new panels and reorder priorites.
		 */

		// Header image, rename & move to Headers panel.
		$wp_customize->get_section( 'header_image' )->panel    = 'theme_header';
		$wp_customize->get_section( 'header_image' )->priority = 11;
		$wp_customize->get_section( 'header_image' )->title    = __( 'Header Images', 'wilbur' );

		// Twenty Twenty cover image template, rename & move to Headers panel.
		$wp_customize->get_section( 'cover_template_options' )->section  = '';
		$wp_customize->get_section( 'cover_template_options' )->panel    = 'theme_header';
		$wp_customize->get_section( 'cover_template_options' )->priority = 12;
		$wp_customize->get_section( 'cover_template_options' )->title    = __( 'Cover Images', 'wilbur' );

		// Theme colors, rename & move to Theme Options panel.
		$wp_customize->get_section( 'colors' )->section  = '';
		$wp_customize->get_section( 'colors' )->panel    = 'theme_options';
		$wp_customize->get_section( 'colors' )->priority = 25;
		$wp_customize->get_section( 'colors' )->title    = __( 'Theme Colors', 'wilbur' );

		// Background image, move to Theme Options panel.
		$wp_customize->get_section( 'background_image' )->section  = '';
		$wp_customize->get_section( 'background_image' )->panel    = 'theme_options';
		$wp_customize->get_section( 'background_image' )->priority = 30;

		// Home page settings, rename & move to Content panel.
		$wp_customize->get_section( 'static_front_page' )->panel       = 'content_options';
		$wp_customize->get_section( 'static_front_page' )->title       = __( 'Home Page', 'wilbur' );
		$wp_customize->get_section( 'static_front_page' )->description = __( 'Choose how to greet visitors.', 'wilbur' );
		$wp_customize->get_section( 'static_front_page' )->priority    = 1;

		// Site identity, rarely needed so move it down the list.
		$wp_customize->get_section( 'title_tagline' )->priority = 9999;

		// Header options section, add to Theme Options panel.
		$wp_customize->add_section(
			'header_options',
			[
				'panel'      => 'theme_header',
				'title'      => __( 'Header Options', 'wilbur' ),
				'priority'   => 10,
				'capability' => 'edit_theme_options',
			]
		);

		// Description for the blog options section, first paragraph.
		$description = sprintf(
			'<h4>%1s</h4>',
			__( 'Content Display Options', 'wilbur' )
		);

		// Second paragraph.
		$description .= sprintf(
			'<p>%1s</p>',
			__( 'Choose which content to display on main blog pages, on archive pages (categories, tags, dates, etc), and on individual posts.', 'wilbur' )
		);

		// Blog options section, add to Content panel.
		$wp_customize->add_section(
			'blog_options',
			[
				'panel'       => 'content_options',
				'title'       => __( 'Blog & Archives', 'wilbur' ),
				'description' => $description,
				'priority'    => 10,
				'capability'  => 'edit_theme_options',
			]
		);
		unset( $description );

		// Header image location, add to Theme Options panel.
		$wp_customize->add_setting(
			'header_image_blog',
			[
				'capability'        => 'edit_theme_options',
				'default'           => true,
				'sanitize_callback' => [ 'Wilbur\Classes\Customize_Sanitize', 'sanitize_checkbox' ],
			]
		);

		$wp_customize->add_control(
			'header_image_blog',
			[
				'type'     => 'checkbox',
				'section'  => 'header_options',
				'priority' => 1,
				'label'    => __( 'Display header on the first page of the blog.', 'wilbur' ),
			]
		);

		/* Header text color.
		$wp_customize->add_setting(
			'header_text_color',
			[
				'default'           => '#ffffff',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			]
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'header_text_color',
				[
					'label'   => __( 'Header text color', 'wilbur' ),
					'section' => 'colors',
					'priority'   => 9
				]
			)
		);
		*/

		$wp_customize->get_setting( 'enable_header_search' )->section = 'header_options';
		$wp_customize->get_control( 'enable_header_search' )->section = 'header_options';

		// Show author bio on individual posts.
		$wp_customize->get_control( 'show_author_bio' )->section     = 'blog_options';
		$wp_customize->get_control( 'show_author_bio' )->label       = __( 'Show author bio on individual posts.', 'wilbur' );
		$wp_customize->get_control( 'show_author_bio' )->description = __( 'Authors edit their bio information on the user profile screen.', 'wilbur' );

		/**
		 * Home Page Display
		 *
		 * Reword the label and options, add a description.
		 */

		 // Description/info for the control, first paragraph.
		$description = sprintf(
			'<p>%1s</p>',
			__( 'The home page can be dynamic content with a list of posts (default order is from newest to oldest) or static content that displays a single page.', 'wilbur' )
		);

		// Second paragraph.
		$description .= sprintf(
			'<p>%1s</p>',
			__( 'To set a static home page you first need to create two pages. One will become the home page and the other will be where your posts are displayed.', 'wilbur' )
		);

		// Array options for the control.
		$wp_customize->get_control( 'show_on_front' )->label       = __( 'Home Page Display', 'wilbur' );
		$wp_customize->get_control( 'show_on_front' )->description = $description;
		$wp_customize->get_control( 'show_on_front' )->choices     = [
			'posts' => __( 'Latest Posts', 'wilbur' ),
			'page'  => __( 'Static Content', 'wilbur' ),
		];
		unset( $description );

		// Text for the static home page control.
		$wp_customize->get_control( 'page_on_front' )->label = __( 'Home Page', 'wilbur' );

		// Text for the blog posts page control.
		$wp_customize->get_control( 'page_for_posts' )->label = __( 'Posts Page', 'wilbur' );

		/**
		 * Blog Content Display
		 *
		 * Move this control from the Theme Options section
		 * of the parent theme to the Blog & Archives section.
		 */

		// Description/info for the Blog Content Display control, first paragraph.
		$description = sprintf(
			'<p>%1s</p>',
			__( 'Display the full text of each post in the list or disply a summary of the post.', 'wilbur' )
		);

		// Second paragraph.
		$description .= sprintf(
			'<p>%1s</p>',
			__( 'The summary option looks first for a manual excerpt, which can be added on the post edit screen. If no manual excerpt is found then one will be automatically generated from the beginning text of the post.', 'wilbur' )
		);

		// Array options for the control.
		$wp_customize->get_control( 'blog_content' )->section     = 'blog_options';
		$wp_customize->get_control( 'blog_content' )->label       = __( 'Blog Content Display', 'wilbur' );
		$wp_customize->get_control( 'blog_content' )->description = $description;
		$wp_customize->get_control( 'blog_content' )->priority    = 1;
		$wp_customize->get_control( 'blog_content' )->choices     = [
			'full'    => __( 'Full Text', 'wilbur' ),
			'summary' => __( 'Summary', 'wilbur' ),
		];
		unset( $description );

		/**
		 * Custom colors.
		 */
		$wp_customize->add_setting(
			'color_scheme',
			[
				'default'           => 'none',
				'transport'         => 'postMessage',
				'sanitize_callback' => [ 'Wilbur\Classes\Customize_Sanitize', 'sanitize_select' ],
			]
		);

		$wp_customize->add_setting(
			'colorscheme_hue',
			[
				'default'           => 250,
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint', // The hue is stored as a positive integer.
			]
		);

		$wp_customize->add_control(
			'color_scheme',
			[
				'type'        => 'radio',
				'label'       => __( 'Color Scheme', 'wilbur' ),
				'description' => __( 'Set the overall color scheme of the site, including image overlays.', 'wilbur' ),
				'choices'     => apply_filters( 'wilbur_color_schemes', [
					'none'   => __( 'None', 'wilbur' ),
					'light'  => __( 'Light', 'wilbur' ),
					'dark'   => __( 'Dark', 'wilbur' ),
					'pink'   => __( 'Pink', 'wilbur' ),
					'blue'   => __( 'Blue', 'wilbur' ),
					'violet' => __( 'Violet', 'wilbur' ),
					'custom' => __( 'Custom', 'wilbur' ),
				] ),
				'section'     => 'colors',
				'priority'    => 5,
			]
		);

		// Header image location, add to Theme Options panel.
		$wp_customize->add_setting(
			'force_color_scheme_admin',
			[
				'capability'        => 'manage_options',
				'default'           => true,
				'sanitize_callback' => [ 'Wilbur\Classes\Customize_Sanitize', 'sanitize_checkbox' ],
			]
		);

		$wp_customize->add_control(
			'force_color_scheme_admin',
			[
				'type'     => 'checkbox',
				'section'  => 'colors',
				'priority' => 5,
				'label'    => __( 'Force use of the color scheme on admin pages.', 'wilbur' ),
				'description'    => __( 'This will remove the option for users to choose a color scheme for their admin experience.', 'wilbur' ),
			]
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'color_scheme_hue',
				[
					'mode'     => 'hue',
					'section'  => 'colors',
					'priority' => 6,
				]
			)
		);

		// Login background, add to Background Image section.
		$wp_customize->add_setting(
			'background_login',
			[
				'capability'        => 'edit_theme_options',
				'default'           => true,
				'sanitize_callback' => [ 'Wilbur\Classes\Customize_Sanitize', 'sanitize_checkbox' ],
			]
		);

		$wp_customize->add_control(
			'background_login',
			[
				'type'     => 'checkbox',
				'section'  => 'background_image',
				'priority' => 10,
				'label'    => __( 'Display the background on the user login screen.', 'wilbur' ),
			]
		);

		/**
		 * Typography section
		 */
		$wp_customize->add_section(
			'typography_options',
			[
				'panel'      => 'theme_options',
				'title'      => __( 'Typography', 'wilbur' ),
				'priority'   => 50,
				'capability' => 'edit_theme_options',
			]
		);

		$wp_customize->add_setting(
			'typography_test',
			[
				'capability'        => 'edit_theme_options',
				'default'           => true,
				'sanitize_callback' => '',
			]
		);

		$wp_customize->add_control(
			'typography_test',
			[
				'type'     => 'checkbox',
				'section'  => 'typography_options',
				'priority' => 10,
				'label'    => __( 'Placeholder for the section.', 'wilbur' ),
			]
		);

	}

	/**
	 * Sanitize the colorscheme
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $input Color scheme.
	 * @return string Returns the slug of the color scheme.
	 */
	public static function sanitize_color_scheme( $input ) {

		// Array of available color schemes.
		$valid = [
			'light',
			'dark',
			'pink',
			'blue',
			'violet',
			'custom'
		];

		// Return the sected color scheme.
		if ( in_array( $input, $valid, true ) ) {
			return $input;
		}

		// Otherwise return the default color scheme.
		return 'light';
	}
}

/**
 * PARTIAL REFRESH FUNCTIONS
 * */
if ( ! function_exists( 'wilbur_customize_partial_blogname' ) ) {
	/**
	 * Render the site title for the selective refresh partial.
	 */
	function wilbur_customize_partial_blogname() {
		bloginfo( 'name' );
	}
}

if ( ! function_exists( 'wilbur_customize_partial_blogdescription' ) ) {
	/**
	 * Render the site description for the selective refresh partial.
	 */
	function wilbur_customize_partial_blogdescription() {
		bloginfo( 'description' );
	}
}

if ( ! function_exists( 'wilbur_customize_partial_site_logo' ) ) {
	/**
	 * Render the site logo for the selective refresh partial.
	 *
	 * Doing it this way so we don't have issues with `render_callback`'s arguments.
	 */
	function wilbur_customize_partial_site_logo() {
		wilbur_site_logo();
	}
}


/**
 * Input attributes for cover overlay opacity option.
 *
 * @return array Array containing attribute names and their values.
 */
function wilbur_customize_opacity_range() {
	/**
	 * Filters the input attributes for opacity
	 *
	 * @param array $attrs {
	 *     The attributes
	 *
	 *     @type int $min Minimum value
	 *     @type int $max Maximum value
	 *     @type int $step Interval between numbers
	 * }
	 */
	return apply_filters(
		'wilbur_customize_opacity_range',
		[
			'min'  => 0,
			'max'  => 90,
			'step' => 5,
		]
	);
}
