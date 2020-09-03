<?php
/**
 * Template tags
 *
 * @package    Hindsight
 * @subpackage Includes
 * @category   Functions
 * @since      1.0.0
 */

 // Theme file namespace.
namespace Hindsight\Tags;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
function twentynineteen_post_thumbnail() {

	if ( ! twentynineteen_can_show_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) :
		?>

		<figure class="post-thumbnail">
			<?php the_post_thumbnail(); ?>
		</figure><!-- .post-thumbnail -->

		<?php
	else :
		?>

	<figure class="post-thumbnail">
		<a class="post-thumbnail-inner" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php the_post_thumbnail( 'post-thumbnail' ); ?>
		</a>
	</figure>

		<?php
	endif; // End is_singular().
}