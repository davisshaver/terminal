<?php
/**
 * Header bar template part.
 *
 * @package AMP
 */

$header = get_custom_header();
$header->url = get_header_image();
if ( ! $header->url ) {
	return '';
}
$width = absint( $header->width );
$height = absint( $header->height );
?>
<header id="header" class="amp-wp-header terminal-amp-header">
	<?php if ( terminal_has_amp_tag( 'header' ) ) : ?>
		<div class="terminal-amp-ad">
			<?php terminal_print_amp_tag( 'header' ); ?>
		</div>
	<?php endif; ?>
	<div class="terminal-amp-header-image">
		<a href="<?php echo esc_url( $this->get( 'home_url' ) ); ?>">
		<amp-img src="<?php header_image(); ?>" layout="responsive" width="<?php echo esc_attr( $width ); ?>" height="<?php echo esc_attr( $height ); ?>"></amp-img>
		</a>
	</div>
</header>

