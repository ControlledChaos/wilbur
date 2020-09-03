<?php
/**
 * Customize dashboard tab
 *
 * @package    Hindsight
 * @subpackage Template Parts
 * @category   Administration
 * @since      1.0.0
 */

?>
<div id="theme" class="ui-tabs-panel">
	<div class="dashboard-panel-content theme-panel-content">
		<h2><?php _e( 'Customize Your Site', 'hindsight' ); ?></h2>
		<p class="description"><?php _e( 'Choose layout options, color schemes, and more.', 'hindsight' ); ?></p>
		<div class="welcome-panel-column-container">

			<div class="welcome-panel-column">
				<h3><?php _e( 'More Options', 'hindsight' ); ?></h3>
				<div class="dashboard-panel-section-intro dashboard-panel-theme-greeting">

					<figure>
						<a href="<?php echo esc_url( wp_customize_url() ); ?>">
							<img class="avatar" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/hindsight-avatar.jpg' ) ); ?>" alt="<?php _e( 'Hindsight theme', 'hindsight' ); ?>" width="64" height="64" />
						</a>
						<figcaption class="screen-reader-text"><?php _e( 'Hindsight theme image', 'hindsight' ); ?></figcaption>
					</figure>

					<div>
						<h4><?php _e( 'Hindsight', 'hindsight' ); ?></h4>
						<p><?php _e( 'This theme extends the Twenty Twenty theme and offers many options, available in the Customizer.', 'hindsight' ); ?></p>
						<p class="dashboard-panel-call-to-action"><a class="button button-primary button-hero load-customize hide-if-no-customize" href="<?php echo esc_url( wp_customize_url() . '?url=' . site_url() . '&return=' . site_url() ); ?>"><?php _e( 'Website Customizer' ); ?></a></p>
						<p class="description"><?php _e( 'Manage site identity & theme options.', 'hindsight' ); ?></p>
					</div>

				</div>
			</div>

			<div class="welcome-panel-column">
				<h3><?php _e( 'Content Options', 'hindsight' ); ?></h3>
				<ul>
					<li><a class="welcome-icon customize-icon-site" href="<?php echo esc_url( wp_customize_url() . '?url=' . site_url() . '&autofocus[section]=title_tagline&return=' . site_url() ); ?>"><?php _e( 'Site identity', 'hindsight' ); ?></a></li>
					<li><a class="welcome-icon customize-icon-layout" href="<?php echo esc_url( wp_customize_url() ); ?>"><?php _e( 'Page layouts', 'hindsight' ); ?></a></li>
					<li><a class="welcome-icon customize-icon-blog" href="<?php echo esc_url( wp_customize_url() ); ?>"><?php _e( 'Blog & archives content', 'hindsight' ); ?></a></li>
					<li><a class="welcome-icon customize-icon-bio" href="<?php echo esc_url( wp_customize_url() ); ?>"><?php _e( 'Author Biographies', 'hindsight' ); ?></a></li>
				</ul>
			</div>

			<div class="welcome-panel-column welcome-panel-last">
				<h3><?php _e( 'Appearance Options', 'hindsight' ); ?></h3>
				<ul>
					<li><a class="welcome-icon customize-icon-schemes" href="<?php echo esc_url( wp_customize_url() ); ?>"><?php _e( 'Choose color schemes', 'hindsight' ); ?></a></li>
					<li><a class="welcome-icon customize-icon-headers" href="<?php echo esc_url( wp_customize_url() ); ?>"><?php _e( 'Set site & page headers', 'hindsight' ); ?></a></li>
					<li><a class="welcome-icon customize-icon-typography" href="<?php echo esc_url( wp_customize_url() . '?url=' . site_url() . '&autofocus[section]=typography_options&return=' . site_url() ); ?>"><?php _e( 'Design your typography', 'hindsight' ); ?></a></li>
					<li><a class="welcome-icon customize-icon-background" href="<?php echo esc_url( wp_customize_url() . '?url=' . site_url() . '&autofocus[section]=background_image&return=' . site_url() ); ?>"><?php _e( 'Site background', 'hindsight' ); ?></a></li>
				</ul>
			</div>

		</div>
	</div>
</div>