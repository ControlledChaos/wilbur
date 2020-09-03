<?php
/**
 * Hindsight theme page
 *
 * @package  Hindsight
 * @category Administration
 * @since    1.0.0
 */

 // Theme file namespace.
namespace Hindsight\Includes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme data as variables.
$get_theme  = wp_get_theme();
$theme_name = $get_theme->get( 'Name' );

/**
 * Panel tabs
 *
 * The customize panel is only available
 * to user who can customize themes.
 */
$tabs = apply_filters( 'hindsight_theme_page_tabs', [

	// Theme dashboard tab.
	sprintf(
		'<li class="ui-tabs-item"><a href="%1s"><span class="dashicons dashicons-dashboard"></span> %2s</a></li>',
		'#theme-dashboard',
		esc_html__( 'Dashboard', 'hindsight' )
	),

    // Theme info tab.
    sprintf(
        '<li class="ui-tabs-item"><a href="%1s"><span class="dashicons dashicons-info"></span> %2s</a></li>',
        '#theme-info',
        esc_html__( 'Info', 'hindsight' )
	)
] );

// Begin page output.
?>

<div class="wrap theme-page">

	<h1><?php echo $theme_name; ?></h1>

	<main id="theme-page-content">
		<div class="tabbed-content"><!-- Begin tabbed content -->

			<ul class="ui-tabs-nav">
				<?php echo implode( $tabs ); ?>
			</ul>

			<?php

			// Theme dashboard tab.
			get_template_part( 'template-parts/admin/theme-page-dashboard' );

			// Theme info tab.
			get_template_part( 'template-parts/admin/theme-page-info' );

			?>

		</div><!-- End jQuery tabbed content -->
	</main>
</div><!-- .wrap -->