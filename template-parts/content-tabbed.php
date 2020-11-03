<?php
/**
 * The default template for displaying tabbed content
 *
 * @package WordPress
 * @subpackage Wilbur
 * @since 1.0.0
 */

/**
 * Safety net
 *
 * In case the Advanced Custom Fields Pro
 * inactive notice file was not accessed.
 *
 * @see content-acf-notice.php
 */
if ( ! class_exists( 'acf_pro' ) ) {
	return;
}

?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<?php

	get_template_part( 'template-parts/entry-header' );

	if ( ! is_search() ) {
		get_template_part( 'template-parts/featured-image' );
	}

	?>

	<div class="post-inner <?php echo is_page_template( 'templates/template-full-width.php' ) ? '' : 'thin'; ?> ">

		<div class="entry-content">

		</div><!-- .entry-content -->

	</div><!-- .post-inner -->

	<div class="section-inner">
		<?php

		// Single bottom post meta.
		wilbur_the_post_meta( get_the_ID(), 'single-bottom' );

		if ( post_type_supports( get_post_type( get_the_ID() ), 'author' ) && is_single() ) {

			get_template_part( 'template-parts/entry-author-bio' );

		}
		?>

	</div><!-- .section-inner -->

	<?php

	if ( is_single() ) {

		get_template_part( 'template-parts/navigation' );

	}

	/**
	 *  Output comments wrapper if it's a post, or if comments are open,
	 * or if there's a comment number – and check for password.
	 * */
	if ( ( is_single() || is_page() ) && ( comments_open() || get_comments_number() ) && ! post_password_required() ) {
		?>

		<div class="comments-wrapper section-inner">

			<?php comments_template(); ?>

		</div><!-- .comments-wrapper -->

		<?php
	}
	?>

</article><!-- .post -->
