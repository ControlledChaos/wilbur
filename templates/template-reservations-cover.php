<?php
/**
 * For the Sturyevant Camp reservations page
 * using the cover image header.
 *
 * Template Name: Reservations Cover Header
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

	if ( have_posts() ) {

		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content', 'tabbed-cover' );
		}
	}

	?>

</main><!-- #site-content -->

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer();
