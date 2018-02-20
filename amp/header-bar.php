<?php
/**
 * Header bar template part.
 *
 * @package AMP
 */

?>
<header id="top" class="amp-wp-header">
	<div style="text-align: center;">
		<amp-ad width=320 height=50
			type="doubleclick"
			data-slot="/4144372/OS_Leaderboard_top">
		</amp-ad>
	</div>
	<div>
		<a href="<?php echo esc_url( $this->get( 'home_url' ) ); ?>">
			<?php $site_icon_url = $this->get( 'site_icon_url' ); ?>
			<?php if ( $site_icon_url ) : ?>
				<amp-img src="<?php echo esc_url( $site_icon_url ); ?>" width="32" height="32" class="amp-wp-site-icon"></amp-img>
			<?php endif; ?>
			<span class="amp-site-title">
				<?php echo esc_html( wptexturize( $this->get( 'blog_name' ) ) ); ?>
			</span>
		</a>
	</div>
</header>
