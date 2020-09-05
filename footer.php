<?php
/**
 * The template for displaying the footer
 *
 * Contains the opening of the #site-footer div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Wilbur
 * @since Wilbur 1.0
 */

?>
			<footer id="site-footer" role="contentinfo" class="header-footer-group">

				<div class="section-inner">

					<div class="footer-credits">

						<p class="footer-copyright">&copy;
							<?php
							echo date_i18n(
								/* translators: Copyright date format, see https://www.php.net/date */
								_x( 'Y', 'copyright date format', 'wilbur' )
							);
							?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
						</p><!-- .footer-copyright -->
					</div><!-- .footer-credits -->

					<a class="to-the-top" href="#site-header">
						<span class="to-the-top-long">
							<span class="screen-reader-text"><?php _e( 'To the top', 'wilbur' ); ?></span>
							<span class="arrow" aria-hidden="true"><svg class="svg-icon" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 22 24"><polygon points="721.105 856 721.105 874.315 728.083 867.313 730.204 869.41 719.59 880 709 869.41 711.074 867.313 718.076 874.315 718.076 856" transform="translate(-709 -856)" /></svg></span>
						</span><!-- .to-the-top-long -->
						<span class="to-the-top-short">
							<span class="screen-reader-text"><?php _e( 'Up', 'wilbur' ); ?></span>
							<span class="arrow" aria-hidden="true"><svg class="svg-icon" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 22 24"><polygon points="721.105 856 721.105 874.315 728.083 867.313 730.204 869.41 719.59 880 709 869.41 711.074 867.313 718.076 874.315 718.076 856" transform="translate(-709 -856)" /></svg></span>
						</span><!-- .to-the-top-short -->
					</a><!-- .to-the-top -->

				</div><!-- .section-inner -->

			</footer><!-- #site-footer -->

		<?php wp_footer(); ?>

	</body>
</html>
