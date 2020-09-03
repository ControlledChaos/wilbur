<?php
/**
 * Hindsight starter content
 *
 * @package    Hindsight
 * @subpackage Includes
 * @category   Content
 * @since      1.0.0
 *
 * @link https://make.wordpress.org/core/2016/11/30/starter-content-for-themes-in-4-7/
 */

// Theme file namespace.
namespace Hindsight\Includes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Function to return the array of starter content for the theme.
 *
 * Passes it through the `twentytwenty_starter_content` filter before returning.
 *
 * @since  Twenty Twenty 1.0.0
 * @return array a filtered array of args for the starter_content.
 */
function starter_content() {

	// Define and register starter content to showcase the theme on new sites.
	$starter_content = array(
		'widgets'     => [
			// Place one core-defined widgets in the first footer widget area.
			'sidebar-1' => [
				'search',
				'text_about',
			],
			// Place one core-defined widgets in the second footer widget area.
			'sidebar-2' => [
				'meta',
			],
		],

		// Create the custom image attachments used as post thumbnails for pages.
		'attachments' => [
			'image-opening' => [
				'post_title' => _x( 'They Say Twenty Twenty Is Hindsight', 'Theme starter content', 'hindsight' ),
				'file'       => 'assets/images/hindsight.jpg', // URL relative to the template directory.
			],
		],

		// Specify the core-defined pages to create and add custom thumbnails to some of them.
		'posts' => [
			'front' => [
				'post_type'    => 'page',
				'post_title'   => __( 'They Say Twenty Twenty Is Hindsight', 'hindsight' ),
				// Use the above featured image with the predefined about page.
				'thumbnail'    => '{{image-opening}}',
				'post_content' => join(
					'',
					[
						'<!-- wp:paragraph -->',
						sprintf(
							'<p>%1s</p>',
							__( 'Hindsight augments and enhances the WordPress Twenty Twenty theme.', 'hindsight' )
						),
						'<!-- /wp:paragraph -->',
						'<!-- wp:heading {"level":3} -->',
						sprintf(
							'<h3>%1s</h3>',
							__( 'Theme Options', 'hindsight' )
						),
						'<!-- /wp:heading -->',
						'<!-- wp:paragraph -->',
						sprintf(
							'<p>%1s</p>',
							__( 'Section coming soon.', 'hindsight' )
						),
						'<!-- /wp:paragraph -->',
						'<!-- wp:heading {"level":3} -->',
						sprintf(
							'<h3>%1s</h3>',
							__( 'Credits', 'hindsight' )
						),
						'<!-- /wp:heading -->',
						'<!-- wp:paragraph -->',
						sprintf(
							'<p>%1s <a href="%2s" target="_blank" rel="noindex nofollow">%3s</a></p>',
							__( 'The Hindsight child theme for Twenty Twenty is designed & developed by', 'hindsight' ),
							esc_url( 'https://ccdzine.com' ),
							__( 'Controlled Chaos Design.', 'hindsight' )
						),
						'<!-- /wp:paragraph -->',
						'<!-- wp:paragraph -->',
						sprintf(
							'<p>%1s <a href="%2s" target="_blank" rel="noindex nofollow">%3s</a></p>',
							__( 'The Twenty Twenty parent theme is designed & developed by', 'hindsight' ),
							esc_url( 'https://wordpress.org/themes/twentytwenty/' ),
							__( 'the WordPress team.', 'hindsight' )
						),
						'<!-- /wp:paragraph -->',
						'<!-- wp:paragraph -->',
						sprintf(
							'<p>%1s <a href="%2s" target="_blank" rel="noindex nofollow">%3s</a></p>',
							__( 'The image used for the theme cover and default header is made available by', 'hindsight' ),
							esc_url( 'https://unsplash.com/@atn' ),
							__( 'Etienne Desclides on Unsplash.', 'hindsight' )
						),
						'<!-- /wp:paragraph -->'
					]
				),
			],
			'about',
			'contact',
			'blog',
		],

		// Default to a static front page and assign the front and posts pages.
		'options' => [
			'show_on_front'  => 'page',
			'page_on_front'  => '{{front}}',
			'page_for_posts' => '{{blog}}',
		],

		// Set up nav menus for each of the two areas registered in the theme.
		'nav_menus' => [
			// Assign a menu to the "primary" location.
			'primary' => [
				'name'  => __( 'Primary', 'hindsight' ),
				'items' => [
					'link_home', // Note that the core "home" page is actually a link in case a static front page is not used.
					'page_blog',
					'page_about',
					'page_contact',
				],
			],
			// This replicates primary just to demonstrate the expanded menu.
			'expanded' => [
				'name'  => __( 'Primary', 'hindsight' ),
				'items' => [
					'link_home', // Note that the core "home" page is actually a link in case a static front page is not used.
					'page_blog',
					'page_about',
					'page_contact',
				],
			],
			// Assign a menu to the "social" location.
			'social' => [
				'name'  => __( 'Social Links Menu', 'hindsight' ),
				'items' => [
					'link_github'  => [
						'title' => _x( 'Hindsight on GitHub', 'Theme starter content', 'hindsight' ),
						'url'   => 'https://github.com/ControlledChaos/hindsight'
					],
					'link_twitter' => [
						'title' => _x( '@CCDzine on Twitter', 'Theme starter content', 'hindsight' ),
						'url'   => 'https://www.twitter.com/CCDzine'
					],
					'link_email'   => [
						'title' => _x( 'Join the Hindsight Newsletter', 'Theme starter content', 'hindsight' ),
						'url'   => 'maito:hindsight@ccdzine.com'
					],
					'', // Blanks needed to prevent extraneous menu items when fewer than five.
					''
				]
			]
		]
	);

	/**
	 * Filters Twenty Twenty array of starter content.
	 *
	 * @since Twenty Twenty 1.0.0
	 *
	 * @param array $starter_content Array of starter content.
	 */
	return $starter_content;

}