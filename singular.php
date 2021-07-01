<?php
/**
 * The template for displaying single posts and pages.
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

			if ( is_front_page() ) {
				get_template_part( 'template-parts/content', 'front-page' );
			} else {
				get_template_part( 'template-parts/content', get_post_type() );
			}
		}
	}

	?>

</main><!-- #site-content -->

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer();
