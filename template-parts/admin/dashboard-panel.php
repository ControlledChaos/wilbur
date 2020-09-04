<?php
/**
 * Dashboard panel
 *
 * @package    Wilbur
 * @subpackage Template Parts
 * @category   Administration
 * @since      1.0.0
 */

/**
 * Panel tabs
 *
 * The customize panel is only available
 * to user who can customize themes.
 */
if ( current_user_can( 'customize' ) ) {
	$theme_tab = sprintf(
        '<li class="ui-tabs-item"><a href="%1s"><span class="dashicons dashicons-art"></span> %2s</a></li>',
		'#theme',
        esc_html__( 'Customize', 'wilbur' )
	);
} else {
	$theme_tab = null;
}

$tabs = apply_filters( 'wilbur_dashboard_panel_tabs', [

    // Welcome tab.
    sprintf(
        '<li class="ui-tabs-item"><a href="%1s"><span class="dashicons dashicons-welcome-learn-more"></span> %2s</a></li>',
        '#welcome',
        esc_html__( 'Welcome', 'wilbur' )
	),

	// Theme tab.
    $theme_tab
] );

?>
<!-- Default WordPress/ClassicPress page wrapper -->
<div id="dashboard-panel" class="welcome-panel dashboard-panel">
	<div class="tabbed-content"><!-- Begin tabbed content -->

		<ul class="ui-tabs-nav">
			<?php echo implode( $tabs ); ?>
		</ul>

		<?php
		// Welcome tab panel.
		get_template_part( 'template-parts/admin/welcome-dashboard-tab' );

		// Customize tab panel.
		if ( current_user_can( 'customize' ) ) {
			get_template_part( 'template-parts/admin/customize-dashboard-tab' );
		}
		?>

	</div><!-- End jQuery tabbed content -->
</div>