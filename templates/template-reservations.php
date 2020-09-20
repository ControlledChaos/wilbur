<?php
/**
 * For the Sturyevant Camp reservations page
 * using the default header.
 *
 * Template Name: Reservations Default Header
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

			get_template_part( 'template-parts/content', 'tabbed' );
		}
	}

	?>

</main><!-- #site-content -->

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer();
