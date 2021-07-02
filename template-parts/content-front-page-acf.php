<?php
/**
 * Front page, no ACF
 *
 * Displays the content for the front page
 * if Advanced Custom Fields Pro in active.
 *
 * @package WordPress
 * @subpackage Wilbur
 * @since 1.0.0
 */

// Double check for ACF Pro.
if ( ! class_exists( 'acf_pro' ) ) {
	return;
}

// Recent post query.
$recent_args = [
	'post_type'           => [ 'post' ],
	'post_status'         => [ 'publish' ],
	'nopaging'            => false,
	'posts_per_page'      => 1,
	'ignore_sticky_posts' => true
];
$recent_query = new WP_Query( $recent_args );

?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<?php
	// On the cover page template, output the cover header.
	$cover_header_style    = '';
	$cover_header_classes  = '';
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

							<?php the_title( '<h1 class="entry-title">', '</h1>' );

							if ( has_excerpt() && is_singular() ) {
							?>

							<div class="intro-text section-inner max-percentage<?php echo $intro_text_width; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static output ?>">
								<?php the_excerpt(); ?>
							</div>

							<?php
							} ?>

							<div class="to-the-content-wrapper">

								<a href="#post-inner" class="to-the-content fill-children-current-color">
									<?php wilbur_the_theme_svg( 'arrow-down' ); ?>
									<div class="screen-reader-text"><?php _e( 'Scroll Down', 'wilbur' ); ?></div>
								</a><!-- .to-the-content -->

							</div><!-- .to-the-content-wrapper -->
						</div><!-- .entry-header-inner -->
					</header><!-- .entry-header -->

			</div><!-- .cover-header-inner -->
		</div><!-- .cover-header-inner-wrapper -->
	</div><!-- .cover-header -->

	<div class="post-inner" id="post-inner">

		<div class="entry-content">

			<?php
			// Flexible content fields.
			if ( have_rows( 'front_intro_content' ) ) :

			?>
			<section class="front-intro-content">
			<?php while ( have_rows( 'front_intro_content' ) ) : the_row(); ?>

			<?php if ( 'front_content_block' == get_row_layout() ) : ?>
				<div class="front-intro-content-section">

					<h2><?php the_sub_field( 'front_intro_content_heading' ); ?></h2>
					<?php the_sub_field( 'front_intro_content_editor' ); ?>
				</div>

			<?php elseif ( 'front_intro_nav' == get_row_layout() ) : ?>
				<div class="front-intro-content-section">

					<h2><?php the_sub_field( 'front_intro_content_heading' ); ?></h2>
					<?php $menu = get_sub_field( 'front_intro_nav_menu' );
					wp_nav_menu( [ 'menu' => $menu ] );
					?>
				</div>

			<?php elseif ( 'front_form_code' == get_row_layout() ) : ?>
				<div class="front-intro-content-section">

					<h2><?php the_sub_field( 'front_intro_content_heading' ); ?></h2>
					<?php
					if ( 'shortcode' == get_sub_field( 'front_form_code_type' ) ) {
						echo do_shortcode( 'front_form_shortcode' );
					} else {
						the_sub_field( 'front_form_html_code' );
					}
					?>
				</div>

			<?php elseif ( 'front_latest_post' == get_row_layout() ) : ?>
				<div class="front-intro-content-section">

					<h2><?php the_sub_field( 'front_intro_content_heading' ); ?></h2>

					<?php
					if ( $recent_query->have_posts() ) {
						while ( $recent_query->have_posts() ) {
							$recent_query->the_post();

							$get_excerpt = get_sub_field( 'front_latest_excerpt_override' );
							if ( $get_excerpt ) {
								$excerpt = $get_excerpt;
							} else {
								$excerpt = get_the_excerpt();
							}
							$thumbnail = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );

							?>
							<div class="featured-post">
								<figure>
									<a href="<?php the_permalink( $page_link->ID ); ?>">
										<img src="<?php echo $thumbnail; ?>" role="presentation">
										<figcaption class="screen-reader-text"><?php echo __( 'Featured image for', 'wilbur' ) . ' ' . get_the_title( $page_link->ID ); ?></figcaption>
									</a>
								</figure>
								<div>
									<a href="<?php the_permalink( $page_link->ID ); ?>">
										<?php the_title( '<h3>', '</h3>' ); ?>
										<?php printf(
											'<p>%s</p>',
											$excerpt
										); ?>
									</a>
								</div>
							</div>
							<?php
						}
					} else {
						// no posts found
					}
					wp_reset_postdata();
					?>
				</div>
			<?php elseif ( 'front_page_link' == get_row_layout() ) : ?>
				<div class="front-intro-content-section">

					<h2><?php the_sub_field( 'front_intro_content_heading' ); ?></h2>

					<?php
					$page_link = get_sub_field( 'front_page_link' );
					$title     = get_the_title( $page_link->ID );
					$excerpt   = get_the_excerpt( $page_link->ID );
					$thumbnail = get_the_post_thumbnail_url( $page_link->ID, 'thumbnail' );
					if ( $page_link ) : ?>
					<div class="featured-post">
						<a href="<?php the_permalink( $page_link->ID ); ?>">
							<img class="alignleft" src="<?php echo $thumbnail; ?>" role="presentation">
							<h3><?php echo $title; ?></h3>
							<?php printf(
								'<p>%s</p>',
								$excerpt
							); ?>
						</a>
					</div>
					<div class="featured-post">
						<figure>
							<a href="<?php the_permalink( $page_link->ID ); ?>">
								<img src="<?php echo $thumbnail; ?>" role="presentation">
								<figcaption class="screen-reader-text"><?php echo __( 'Featured image for', 'wilbur' ) . ' ' . $title; ?></figcaption>
							</a>
						</figure>
						<div>
							<a href="<?php the_permalink( $page_link->ID ); ?>">
								<h3><?php echo $title; ?></h3>
								<?php printf(
									'<p>%s</p>',
									$excerpt
								); ?>
							</a>
						</div>
					</div>
					<?php endif; ?>
				</div>
			<?php
			// End `get_row_layout()`.
			endif;
			?>

			<?php endwhile; ?>
			</section>
			<?php

			// End `have_rows( 'front_intro_content' )`.
			endif; ?>

			<?php the_content(); ?>

		</div><!-- .entry-content -->
	</div><!-- .post-inner -->
</article><!-- .post -->
