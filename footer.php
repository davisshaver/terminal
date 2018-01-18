<?php
/**
 * The template for displaying the footer.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Terminal
 */

?>
		<div id="footer" class="terminal-utility-font">
			<div id="footer-inside">
				<div id="footer-leaderboard">
					<broadstreet-zone zone-id="64592"></broadstreet-zone>
				</div>
				<div id="footer-split">
					<div id="footer-left">
						<div id="footer-rss">
							<a href="<?php echo esc_url( bloginfo( 'rss2_url' ) ); ?>">
								<img height="18" width="18" src="<?php echo esc_url( get_template_directory_uri() ); ?>/client/static/images/rss.png" alt="<?php esc_attr_e( 'RSS logo', 'terminal' ); ?>" />
							</a>
						</div>
						<?php
							wp_nav_menu( array(
								'theme_location' => 'footer',
							) );
						?>
					</div>
					<div id="footer-right">
						<p>&copy;&nbsp;<?php echo esc_html( date( 'Y' ) ); ?> <span id="footer-title"><?php echo esc_html( get_bloginfo( 'title' ) ); ?></span></p>
					</div>
				</div>
				<div id="ppc-logo">
					<a href="https://phillypublishing.com" title="<?php esc_attr_e( 'Philadelphia Publishing Company' ); ?>">
						<img draggable="false" src="<?php echo esc_url( get_template_directory_uri() ); ?>/client/static/images/ppc.png" alt="<?php esc_attr_e( 'Philadelphia Publishing Company logo' ); ?>" />
					</a>
				</div>
			</div>
		</div>
		<?php wp_footer(); ?>
	</body>
</html>
