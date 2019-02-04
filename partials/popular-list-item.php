<?php
/**
 * Popular post partial.
 *
 * @package Terminal
 */
if ( empty( $url ) || empty( $title ) ) {
	return;
}
if ( empty( $rank ) ) {
	$rank = null;
}

if ( empty( $image_class ) ) {
	$image_class = null;
}

printf(
	'<div class="terminal-popular-list-item %s" data-rank="%s">',
	esc_attr( $image_class ),
	esc_attr( $rank )
);
	if ( $image_url ) {
		printf(
			'<a href="%s" class="terminal-popular-list-item-image"><img alt="%s" src="%s" /></a>',
			esc_url( $url ),
			esc_attr( $title ),
			esc_url( $image_url )
		);
	}
	?>
	<h5>
		<?php
		printf(
			'<a href="%s">%s</a>',
			esc_url( $url ),
			$title
		);
		?>
	</h5>
	<div class="terminal-popular-author">
		<?php
		printf(
			'%s %s',
			esc_html( __( 'By', 'terminal' ) ),
			esc_html( implode( ', ', $authors ) )
		);
		?>
	</div>
</div>