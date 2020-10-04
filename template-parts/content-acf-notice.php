<?php
/**
 * ACF notice
 *
 * Displays a notice that the Advanced Custom
 * Fields Pro plugin must be installed and
 * activated to use a page template.
 *
 * @package WordPress
 * @subpackage Wilbur
 * @since 1.0.0
 */

/**
 * Notice heading
 *
 * This is different than the post or page heading.
 */
if ( is_singular() ) {
	$heading = sprintf(
		'<h2 class="entry-title heading-size-1">%1s</h2>',
		__( 'Content Cannot Be Viewed', 'wilbur' )
	);
} else {
	$heading = sprintf(
		'<h3 class="entry-title heading-size-1">%1s</h3>',
		__( 'Content Cannot Be Viewed', 'wilbur' )
	);
}

?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<?php get_template_part( 'template-parts/entry-header' ); ?>

	<div class="post-inner" id="post-inner">
		<div class="entry-content">

			<?php echo $heading; ?>

			<?php echo sprintf(
				'<p>%1s <a href="%2s"><strong>%3s</strong></a> %4s</p>',
				__( 'The', 'wilbur' ),
				esc_url( 'https://www.advancedcustomfields.com/pro/' ),
				__( 'Advanced Custom Fields Pro', 'wilbur' ),
				__( 'plugin must be installed and activated to view the content of this page. Please contact the website administrator.', 'wilbur' )
			); ?>

		</div><!-- .entry-content -->
	</div><!-- .post-inner -->
</article><!-- .post -->