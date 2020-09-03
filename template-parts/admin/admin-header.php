<?php
/**
 * Admin header template
 *
 * @package    Hindsight
 * @subpackage Template Parts
 * @category   Administration
 * @since      1.0.0
 */

// Get the site name.
$name = get_bloginfo( 'name' );

// Get the site description.
$description = get_bloginfo( 'description' );

// Return null if no site title.
if ( ! empty( $name ) ) {
    $name = sprintf(
		'<p class="site-title admin-site-title faux-heading" itemprop="name"><a href="%1s">%2s</a></p>',
		esc_url( site_url() ),
		$name
	);
} else {
    $name = null;
}

// Return null if no site description.
if ( ! empty( $description ) ) {
    $description = sprintf(
		'<p class="site-description admin-site-description">%1s</p>',
		$description
	);
} else {
    $description = null;
}

/**
 * Admin header menu arguments
 *
 * Uses the fallback method from the `Admin_Pages` class.
 *
 * @see classes/class-admin-pages.php
 */
$menu_args = apply_filters(
	'hindsight_admin_header_menu',
	[
		'theme_location' => 'admin-header',
		'container'      => false,
		'menu_id'        => 'admin-header-menu',
		'menu_class'     => 'primary-menu admin-header-menu reset-list-style',
		'before'         => '',
		'after'          => '',
		'fallback_cb'    => [ 'Hindsight\Classes\Admin_Pages', 'admin_menu_fallback' ]
	]
);

?>
<?php do_action( 'hindsight_before_admin_header' ); ?>
<header id="admin-site-header" class="site-header admin-site-header header-footer-group" role="banner">
	<div class="header-inner section-inner admin-header-inner">
		<div class="header-titles-wrapper admin-header-titles-wrapper">
			<div class="header-titles admin-header-titles">
				<?php echo apply_filters( 'hindsight_admin_header_name', $name ); ?>
				<?php echo apply_filters( 'hindsight_admin_header_description', $description ); ?>
			</div>
		</div>
		<div class="header-navigation-wrapper admin-header-navigation-wrapper">
			<nav class="primary-menu-wrapper admin-menu-wrapper" aria-label="Horizontal" role="navigation">
				<?php wp_nav_menu( $menu_args ); ?>
			</nav>
			<?php if ( get_option( 'hindsight_admin_menu' ) ) : ?>
			<div class="header-toggles hide-no-js">
				<div class="toggle-wrapper nav-toggle-wrapper has-expanded-menu">
					<button class="toggle nav-toggle desktop-nav-toggle" data-toggle-target=".menu-modal" data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".close-nav-toggle">
						<span class="toggle-inner">
							<span class="toggle-text">Menu</span>
							<span class="toggle-icon">
								<svg class="svg-icon" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" width="26" height="7" viewBox="0 0 26 7"><path fill-rule="evenodd" d="M332.5,45 C330.567003,45 329,43.4329966 329,41.5 C329,39.5670034 330.567003,38 332.5,38 C334.432997,38 336,39.5670034 336,41.5 C336,43.4329966 334.432997,45 332.5,45 Z M342,45 C340.067003,45 338.5,43.4329966 338.5,41.5 C338.5,39.5670034 340.067003,38 342,38 C343.932997,38 345.5,39.5670034 345.5,41.5 C345.5,43.4329966 343.932997,45 342,45 Z M351.5,45 C349.567003,45 348,43.4329966 348,41.5 C348,39.5670034 349.567003,38 351.5,38 C353.432997,38 355,39.5670034 355,41.5 C355,43.4329966 353.432997,45 351.5,45 Z" transform="translate(-329 -38)"></path></svg>
							</span>
						</span>
					</button><!-- .nav-toggle -->
				</div><!-- .nav-toggle-wrapper -->
			</div>
			<?php endif; // End get menu option. ?>
		</div>
	</div>
</header>
<?php do_action( 'hindsight_after_admin_header' ); ?>