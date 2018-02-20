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
<footer class="amp-wp-footer">
	<div>
		<h2><?php echo esc_html( wptexturize( $this->get( 'blog_name' ) ) ); ?></h2>
		<p>
			<?php esc_html_e( 'Powered by ', 'terminal' ); ?><a href="https://phillypublishing.com" title="<?php esc_attr_e( 'Powered by Philadelphia Publishing Company', 'terminal' ); ?>"><?php esc_html_e( 'Philadelphia Publishing Company', 'terminal' ); ?></a>
		</p>
		<a href="#top" class="back-to-top"><?php esc_html_e( 'Back to top', 'amp' ); ?></a>
	</div>
</footer>
<div style="text-align: center; margin-bottom: 2px;">
	<amp-ad width=320 height=50
		type="doubleclick"
		data-slot="/4144372/OS_leaderboard_Bottom">
	</amp-ad>
</div>
