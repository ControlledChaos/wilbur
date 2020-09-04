<?php
/**
 * Wilbur theme page info content
 *
 * @package  Wilbur
 * @category Administration
 * @since    1.0.0
 */

 // Theme file namespace.
namespace Wilbur\Includes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme data as variables.
$get_theme        = wp_get_theme();
$get_theme_name   = $get_theme->get( 'Name' );
$get_template     = $get_theme->get( 'Template' );
$php_version      = $get_theme->get( 'Requires PHP' );
$wp_version       = $get_theme->get( 'Requires at least' );
$get_parent       = wp_get_theme( get_template() );
$parent_name      = $get_parent->get( 'Name' );
$get_theme_uri    = $get_theme->get( 'ThemeURI' );
$get_author       = $get_theme->get( 'Author' );
$get_author_uri   = $get_theme->get( 'AuthorURI' );
$theme_desc       = $get_theme->get( 'Description' );
$get_theme_vers   = $get_theme->get( 'Version' );
$get_theme_domain = $get_theme->get( 'TextDomain' );
$get_theme_tags   = $get_theme->get( 'Tags' );
$screenshot_src   = $get_theme->get_screenshot();

wp_enqueue_style( 'theme-page', get_theme_file_uri( 'assets/css/theme-page.min.css' ), [], $get_theme_vers, 'all' );

// Text if data is not provided by the theme.
$not_provided = __( 'Not provided in the stylesheet header', 'wilbur' );

// PHP version.
if ( $php_version ) {
	$php_version = $php_version;
} else {
	$php_version = WILBUR_PHP_VERSION;
}

// WordPress version.
if ( $wp_version ) {
	$wp_version = $wp_version;
} else {
	$wp_version = '4.7';
}

// Theme description.
if ( $theme_desc ) {
	$description = $theme_desc;
} else {
	$description = $not_provided;
}

// Theme link.
if ( $get_theme_uri ) {
	$theme_uri = '<a href="' . $get_theme_uri . '" target="_blank">' . $get_theme_uri . '</a>';
} else {
	$theme_uri = $not_provided;
}

// Theme author.
if ( $get_author ) {
	$author = $get_author;
} else {
	$author = $not_provided;
}

// Theme author link.
if ( $get_author_uri ) {
	$author_uri = '<a href="' . $get_author_uri . '" target="_blank">' . $get_author_uri . '</a>';
} else {
	$author_uri = $not_provided;
}

// Theme version.
if ( $get_theme_vers ) {
	$version = $get_theme_vers;
} else {
	$version = $not_provided;
}

// Theme text domain;
if ( $get_theme_domain ) {
	$domain = $get_theme_domain;
} else {
	$domain = $not_provided;
}

// Theme tags.
if ( $get_theme_tags ) {
	$tags = $get_theme_tags;
} else {
	$tags = $not_provided;
}

// Begin page output.
?>
<div id="theme-info" class="ui-tabs-panel">
	<h2><?php esc_html_e( 'Theme Requirements', 'wilbur' ); ?></h2>

	<ul class="theme-details">
		<?php if ( $get_template ) : ?>
		<li><strong><?php _e( 'Parent Theme: ', 'wilbur' ); ?></strong><?php echo $parent_name; ?></li>
		<?php endif; ?>
		<li><strong><?php esc_html_e( 'Minimum PHP Version: ', 'wilbur' ); ?></strong><?php echo $php_version; ?></li>
		<li><strong><?php esc_html_e( 'Minimum WordPress Version: ', 'wilbur' ); ?></strong><?php echo $wp_version; ?></li>
	</ul>

	<h2><?php esc_html_e( 'Theme Details', 'wilbur' ); ?></h2>

	<ul class="theme-details">
		<li><strong><?php esc_html_e( 'Theme Website: ', 'wilbur' ); ?></strong><?php echo $theme_uri; ?></li>
		<li><strong><?php esc_html_e( 'Author: ', 'wilbur' ); ?></strong><?php echo $author; ?></li>
		<li><strong><?php esc_html_e( 'Author Website: ', 'wilbur' ); ?></strong><?php echo $author_uri; ?></li>
		<li><strong><?php esc_html_e( 'Version: ', 'wilbur' ); ?></strong><?php echo $version; ?></li>
		<li><strong><?php esc_html_e( 'Text Domain: ', 'wilbur' ); ?></strong><?php echo $domain; ?></li>
		<li><strong><?php esc_html_e( 'Tags: ', 'wilbur' ); ?></strong><?php echo implode( ', ', $tags ); ?></li>

	<?php if ( $screenshot_src ) : ?>
		<li class="theme-cover-image"><strong><?php _e( 'Cover image: ', 'wilbur' ); ?></strong><br />
		<img src="<?php echo esc_url( $screenshot_src ); ?>" /></li>
	<?php endif; ?>
	</ul>
</div>