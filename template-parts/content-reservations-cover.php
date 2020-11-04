<?php
/**
 * Reservations page content with cover image
 *
 * Requires the Advanced Custom Fields Pro
 * plugin to be installed and activated.
 *
 * @link https://www.advancedcustomfields.com/pro/
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

		<?php
		/**
		 * Tabbed content buttons
		 *
		 * Switches between content views.
		 * Hidden if Javascript is disabled.
		 */

		// Get the tabs repeater field.
		$tabs = get_field( 'reservations_tabs' );

		// Set up counter to give the first tab an active class.
		$count = null;

		// Create a navigation list if tabs are available.
		if ( $tabs ) {

			// Start counter at zero.
			$count = 0;

			// Begin the navigation markup.
			echo '<nav role="tablist"><ul class="content-tabs">';

			// Print a list item for each tab.
			foreach ( $tabs as $tab ) {

				// Count one (1) for each tab.
				$count++;

				// Add active class to the first tab.
				if ( 1 == $count ) {
					$tab_class = 'content-tab tab-active';

				// JavaScript will add the active class as needed.
				} else {
					$tab_class = 'content-tab';
				}

				// Get the text field for the tab.
				$tab_text = $tab['reservations_tab_text'];

				// Create a tab ID from the tab text.
				$tab_id = strtolower( str_replace( ' ', '-', $tab_text ) );

				// Tab list item markup.
				echo sprintf(
					'<li id="%1s-tab" class="%2s" role="tab" aria-controls="%3s-tab"><a href="#%4s" class="content-tab-button">%5s</a></li>',
					$tab_id,
					$tab_class,
					$tab_id,
					$tab_id,
					$tab_text
				);
			}

			/**
			 * Print a list item for the reservations tab if
			 * the form shortcode field is not empty.
			 */
			if ( get_field( 'reservations_form_shortcode' ) ) :
				echo sprintf(
					'<li id="form-tab" class="content-tab" role="tab" aria-controls="form-tab"><a href="#form" class="content-tab-button">%1s</a></li>',
					__( 'Reserve', 'wilbur' )
				);
			endif;

			// Close the navigation markup.
			echo '</ul></nav>';
		}

		/**
		 * Tabbed content
		 *
		 * Displays the content for each tab.
		 * Shown all at once, by sorted order of the tabs repeater field,
		 * if Javascript is disabled.
		 */
		if ( $tabs ) : foreach ( $tabs as $tab ) :

		// Get the text field for the tab.
		$tab_text = $tab['reservations_tab_text'];

		// Create a tab ID from the tab text.
		$tab_id = strtolower( str_replace( ' ', '-', $tab_text ) );

		?>
			<div id="<?php echo $tab_id; ?>" class="tab-content">

				<h2><?php echo $tab['reservations_tab_heading']; ?></h2>

				<?php

				// The full markup of the tab.
				echo $tab['reservations_tab_content']; ?>
			</div>

			<?php
			endforeach;

			/**
			 * Tabbed reservations form
			 *
			 * Displays only is tabs are available from
			 * the tabs repeater field. Otherwise the
			 * form is displayed below.
			 */
			?>
			<div id="form" class="tab-content reservations-form__tabs">

				<h2><?php _e( 'Reservations Form', 'wilbur' ); ?></h2>

				<?php

				// The markup of any content before the reservation form.
				echo get_field( 'reservation_tab_content' );

				// Runs the shortcode of the form plugin.
				echo do_shortcode( get_field( 'reservations_form_shortcode' ), false );
				?>
			</div>

		<?php

		/**
		 * No taabbed content, only the form
		 *
		 * If there is no tabbed content but the form shortode is available
		 * from the shortcode field. This only checks for the shortcode to
		 * make any preceding content optional.
		 */
		elseif ( get_field( 'reservations_form_shortcode' ) ) :

		?>
			<div class="reservations-form__no-tabs">

				<h2><?php _e( 'Reservations Form', 'wilbur' ); ?></h2>

				<?php

				// The markup of any content before the reservation form.
				echo get_field( 'reservation_tab_content' );

				// Runs the shortcode of the form plugin.
				echo do_shortcode( get_field( 'reservations_form_shortcode' ), false );
				?>
			</div>

		<?php

		/**
		 * No data available
		 *
		 * This is displayed if none of the template fields
		 * have been entered.
		 */
		else :

		?>
			<div class="reservations-form__no-tabs">

				<h2><?php _e( 'Reservations Closed', 'wilbur' ); ?></h2>

				<p><?php _e( 'The reservations form is not available. Please contact us directly.', 'wilbur' ); ?></p>
			</div>
		<?php endif; ?>

		</div><!-- .entry-content -->

	</div><!-- .post-inner -->

</article><!-- .post -->
