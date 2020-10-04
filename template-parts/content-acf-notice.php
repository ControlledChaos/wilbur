<?php
/**
 * ACF notice
 *
 * Displays a notice that the Advanced Custom
 * Fields Pro plugin must be instaled and
 * activated to use a page template.
 *
 * @package WordPress
 * @subpackage Wilbur
 * @since 1.0.0
 */

?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<div class="post-inner" id="post-inner">

		<div class="entry-content">

			<p><?php _e( 'The Advanced Custom Fields Pro plugin must be instaled and activated to view the content of this page. Please contact the website administrator.' ); ?></p>

		</div><!-- .entry-content -->

	</div><!-- .post-inner -->

</article><!-- .post -->