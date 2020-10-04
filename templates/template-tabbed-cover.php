<?php
/**
 * Template Name: Tabbed Cover Header
 * Template Post Type: post, page
 *
 * @package WordPress
 * @subpackage Wilbur
 * @since 1.0.0
 */

get_header();
?>

<main id="site-content" role="main">

	<?php

	if ( ! class_exists( 'acf_pro' ) ) {

		get_template_part( 'template-parts/content', 'acf-notice' );

	} elseif ( have_posts() ) {

		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content', 'tabbed-cover' );
		}
	}

	?>

</main><!-- #site-content -->

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer();
