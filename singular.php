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

			if ( class_exists( 'acf_pro' ) && is_front_page() ) {
				get_template_part( 'template-parts/content', 'front-page-acf' );
			} elseif ( is_front_page() ) {
				get_template_part( 'template-parts/content', 'front-page' );
			} else {
				get_template_part( 'template-parts/content', get_post_type() );
			}
		}
	}

	?>

	<?php
	if ( class_exists( 'acf_pro' ) ) :
		if ( is_singular( 'post' ) ) {
			printf(
				'<hr class="post-separator styled-separator section-inner" /><p class="blog-description"><span>%s</span><br />%s</a>',
				__( 'Bigcone Blog:', 'wilbur' ),
				get_field( 'blog_description', 'options' )
			);
		}
	endif;
	?>

</main><!-- #site-content -->

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer();
