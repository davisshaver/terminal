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
	<div class="terminal-amp-footer-ad">
		<amp-ad
			width=320
			height=50
			type="adsense"
			data-ad-client="ca-pub-0809625376938310"
			data-ad-slot="1249883947"
			layout="fixed"
		>
		</amp-ad>
	</div>
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

