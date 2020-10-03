<?php
/**
 * Welcome dashboard tab
 *
 * @package    Wilbur
 * @subpackage Template Parts
 * @category   Administration
 * @since      1.0.0
 */

// Get the current user data for the greeting.
$current_user = wp_get_current_user();
$user_id      = get_current_user_id();
$user_name    = $current_user->display_name;
$avatar       = get_avatar(
	$user_id,
	64,
	'',
	$current_user->display_name,
	[
		'class'         => 'dasnboard-panel-avatar alignnone',
		'force_display' => true
		]
);

?>
<div id="welcome" class="ui-tabs-panel">
	<div class="dashboard-panel-content welcome-panel-content">
		<?php echo sprintf(
			'<h2>%1s %2s</h2>',
			__( 'Welcome to', 'wilbur' ),
			get_bloginfo( 'name' )
		); ?>
		<p class="description"><?php _e( 'We\'ve assembled some links to get you started.', 'wilbur' ); ?></p>
		<div class="welcome-panel-column-container">

			<div id="dashboard-get-started" class="welcome-panel-column">
				<h3><?php _e( 'Get Started', 'wilbur' ); ?></h3>
				<div class="dashboard-panel-section-intro dashboard-panel-user-greeting">

					<figure>
						<a href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>">
							<?php echo $avatar; ?>
						</a>
						<figcaption class="screen-reader-text"><?php echo $user_name; ?></figcaption>
					</figure>

					<div>
						<?php echo sprintf(
							'<h4>%1s %2s.</h4>',
							esc_html__( 'Howdy,', 'wilbur' ),
							$user_name
						); ?>
						<p><?php _e( 'This site may display your profile in posts that you author, and it offers user-defined color schemes.', 'wilbur' ); ?></p>
						<p class="dashboard-panel-call-to-action"><a class="button button-primary button-hero" href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>"><?php _e( 'Manage Your Profile' ); ?></a></p>
						<p class="description"><?php _e( 'Edit your display name & bio.', 'wilbur' ); ?></p>
					</div>

				</div>
			</div>

			<div id="dashboard-next-steps" class="welcome-panel-column">
				<h3><?php _e( 'Next Steps', 'wilbur' ); ?></h3>
				<ul>

				<?php if ( 'page' == get_option( 'show_on_front' ) && ! get_option( 'page_for_posts' ) ) : ?>
					<li><?php printf( '<a href="%s" class="welcome-icon welcome-edit-page">' . __( 'Edit your front page', 'wilbur' ) . '</a>', get_edit_post_link( get_option( 'page_on_front' ) ) ); ?></li>

					<li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __( 'Add additional pages', 'wilbur' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>

				<?php elseif ( 'page' == get_option( 'show_on_front' ) ) : ?>
					<li><?php printf( '<a href="%s" class="welcome-icon welcome-edit-page">' . __( 'Edit your front page', 'wilbur' ) . '</a>', get_edit_post_link( get_option( 'page_on_front' ) ) ); ?></li>

					<li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __( 'Add additional pages', 'wilbur' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>

					<li><?php printf( '<a href="%s" class="welcome-icon welcome-write-blog">' . __( 'Add a blog post', 'wilbur' ) . '</a>', admin_url( 'post-new.php' ) ); ?></li>

				<?php else : ?>
					<li><?php printf( '<a href="%s" class="welcome-icon welcome-write-blog">' . __( 'Write your first blog post', 'wilbur' ) . '</a>', admin_url( 'post-new.php' ) ); ?></li>

					<li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __( 'Add an About page', 'wilbur' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>

					<li><?php printf( '<a href="%s" class="welcome-icon welcome-setup-home">' . __( 'Set up your homepage', 'wilbur' ) . '</a>', current_user_can( 'customize' ) ? add_query_arg( 'autofocus[section]', 'static_front_page', admin_url( 'customize.php' ) ) : admin_url( 'options-reading.php' ) ); ?></li>
				<?php endif; ?>

				<?php if ( current_user_can( 'manage_options' ) ) : ?>
					<li><?php printf( '<a href="%s" class="welcome-icon welcome-settings">' . __( 'Manage your settings', 'wilbur' ) . '</a>', admin_url( 'options-general.php' ) ); ?></li>
				<?php endif; ?>
				</ul>
			</div>

			<div id="dashboard-more-actions" class="welcome-panel-column welcome-panel-last">
				<h3><?php _e( 'More Actions', 'wilbur' ); ?></h3>
				<ul>

				<?php if ( current_user_can( 'upload_files' ) ) : ?>
					<li><?php printf( '<a href="%s" class="welcome-icon welcome-media">' . __( 'Manage media', 'wilbur' ) . '</a>', admin_url( 'upload.php' ) ); ?></li>
				<?php endif; ?>

				<?php if ( current_theme_supports( 'widgets' ) ) : ?>
					<li><?php printf( '<a href="%s" class="welcome-icon welcome-widgets">' . __( 'Manage widgets', 'wilbur' ) . '</a>', admin_url( 'widgets.php' ) ); ?></li>
				<?php endif; ?>

				<?php if ( current_theme_supports( 'menus' ) ) : ?>
					<li><?php printf( '<a href="%s" class="welcome-icon welcome-menus">' . __( 'Manage menus', 'wilbur' ) . '</a>', admin_url( 'nav-menus.php' ) ); ?></li>
				<?php endif; ?>
				</ul>
			</div>

		</div>
	</div>
</div>