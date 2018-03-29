<?php
/**
 * Footer template part.
 *
 * @package AMP
 */

/**
 * Context.
 *
 * @var AMP_Post_Template $this
 */
?>
<footer class="amp-wp-footer terminal-utility-font">
	<?php if ( terminal_has_amp_tag( 'footer' ) ) : ?>
		<div class="terminal-amp-footer-ad">
		<?php terminal_print_amp_tag( 'footer' ); ?>
		</div>
	<?php endif; ?>
	<div id="copyright">
		<p>&copy;&nbsp;
		<?php
		if ( ! empty( $footer_data['copyright_from_year'] ) ) {
			echo esc_html( $footer_data['copyright_from_year'] . ' - ' );
		}
		echo esc_html( date( 'Y' ) );
		?>
		<span id="footer-title"><?php echo esc_html( get_bloginfo( 'title' ) ); ?></span></p>
	</div>
	<div id="ppc-logo">
		<a href="https://phillypublishing.com" title="<?php esc_attr_e( 'Powered by Philadelphia Publishing Company' ); ?>">
			<amp-img src="<?php echo esc_url( get_template_directory_uri() ); ?>/client/static/images/ppc.png" height="25px" width="25px" />
		</a>
	</div>
</footer>

