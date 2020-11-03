<?php
/**
 * Template for the reservations page with cover image.
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
	// On the cover page template, output the cover header.
	$cover_header_style   = '';
	$cover_header_classes = '';

	$color_overlay_style   = '';
	$color_overlay_classes = '';

	$image_url = ! post_password_required() ? get_the_post_thumbnail_url( get_the_ID(), 'cover-image' ) : '';

	if ( $image_url ) {
		$cover_header_style   = ' style="background-image: url( ' . esc_url( $image_url ) . ' );"';
		$cover_header_classes = ' bg-image';
	}

	// Get the color used for the color overlay.
	$color_overlay_color = get_theme_mod( 'cover_template_overlay_background_color' );
	if ( $color_overlay_color ) {
		$color_overlay_style = ' style="color: ' . esc_attr( $color_overlay_color ) . ';"';
	} else {
		$color_overlay_style = '';
	}

	// Get the fixed background attachment option.
	if ( get_theme_mod( 'cover_template_fixed_background', true ) ) {
		$cover_header_classes .= ' bg-attachment-fixed';
	}

	// Get the opacity of the color overlay.
	$color_overlay_opacity  = get_theme_mod( 'cover_template_overlay_opacity' );
	$color_overlay_opacity  = ( false === $color_overlay_opacity ) ? 50 : $color_overlay_opacity;
	$color_overlay_classes .= ' opacity-' . $color_overlay_opacity;
	?>

	<div class="cover-header <?php echo $cover_header_classes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static output ?>"<?php echo $cover_header_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- We need to double check this, but for now, we want to pass PHPCS ;) ?>>
		<div class="cover-header-inner-wrapper screen-height">
			<div class="cover-header-inner">
				<div class="cover-color-overlay<?php echo esc_attr( $color_overlay_classes ); ?>"<?php echo $color_overlay_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- We need to double check this, but for now, we want to pass PHPCS ;) ?>></div>

					<header class="entry-header has-text-align-center">
						<div class="entry-header-inner section-inner medium">

							<?php

							/**
							 * Allow child themes and plugins to filter the display of the categories in the article header.
							 *
							 * @since 1.0.0
							 *
							 * @param bool Whether to show the categories in article header, Default true.
							 */
							$show_categories = apply_filters( 'wilbur_show_categories_in_entry_header', true );

							if ( true === $show_categories && has_category() ) {
								?>

								<div class="entry-categories">
									<span class="screen-reader-text"><?php _e( 'Categories', 'wilbur' ); ?></span>
									<div class="entry-categories-inner">
										<?php the_category( ' ' ); ?>
									</div><!-- .entry-categories-inner -->
								</div><!-- .entry-categories -->

								<?php
							}

							the_title( '<h1 class="entry-title">', '</h1>' );

							if ( has_excerpt() && is_singular() ) {
								?>

								<div class="intro-text section-inner max-percentage<?php echo $intro_text_width; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static output ?>">
									<?php the_excerpt(); ?>
								</div>

								<?php
							}

							if ( is_page() ) {
								?>

								<div class="to-the-content-wrapper">

									<a href="#post-inner" class="to-the-content fill-children-current-color">
										<?php wilbur_the_theme_svg( 'arrow-down' ); ?>
										<div class="screen-reader-text"><?php _e( 'Scroll Down', 'wilbur' ); ?></div>
									</a><!-- .to-the-content -->

								</div><!-- .to-the-content-wrapper -->

								<?php
							} else {

								$intro_text_width = '';

								if ( is_singular() ) {
									$intro_text_width = ' small';
								} else {
									$intro_text_width = ' thin';
								}

								if ( has_excerpt() ) {
									?>

									<div class="intro-text section-inner max-percentage<?php echo esc_attr( $intro_text_width ); ?>">
										<?php the_excerpt(); ?>
									</div>

									<?php
								}

								wilbur_the_post_meta( get_the_ID(), 'single-top' );

							}
							?>

						</div><!-- .entry-header-inner -->
					</header><!-- .entry-header -->

			</div><!-- .cover-header-inner -->
		</div><!-- .cover-header-inner-wrapper -->
	</div><!-- .cover-header -->

	<div class="post-inner" id="post-inner">

		<div class="entry-content">

		</div><!-- .entry-content -->
		<?php

		// Single bottom post meta.
		wilbur_the_post_meta( get_the_ID(), 'single-bottom' );

		if ( post_type_supports( get_post_type( get_the_ID() ), 'author' ) && is_single() ) {

			get_template_part( 'template-parts/entry-author-bio' );

		}
		?>

	</div><!-- .post-inner -->

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
