<?php

if ( empty( terminal_get_sponsor_data( 'enable_sponsors' ) ) ) {
	return;
}
?>
<div id="terminal-sponsors" class="terminal-sponsors-container">
	<?php terminal_print_sponsors_header(); ?>
	<?php
	$tiers = array( 'one', 'two', 'three', 'four', 'five' );
	foreach( $tiers as $tier ) :
		$tier_data = terminal_get_sponsor_data( 'tier_' . $tier . '_sponsors' );
		if ( ! empty( $tier_data ) ) {
			foreach( $tier_data as $sponsor ) :
				if (
					empty( $sponsor['sponsor_link'] ) ||
					empty( $sponsor['sponsor_media'] ) ||
					empty( $sponsor['sponsor_name'] )
				) {
					continue;
				}

				if ( in_array( $tier, array( 'one', 'two' ) ) ) {
					$img_size = 'terminal-uncut-thumbnail-extra-large';
				} else {
					$img_size = 'terminal-uncut-thumbnail-large';
				}
		
				$image = wp_get_attachment_image_src( $sponsor['sponsor_media'], $img_size, false, array( 'scheme' => 'https' ) );
				if ( empty( $image ) ) {
					continue;
				}
				printf(
					'<a href="%s" target="_blank" class="terminal-sponsor terminal-sponsor-tier-%s"><img data-amp-layout="fixed" src="%s" alt="%s">%s</a>',
					esc_url( $sponsor['sponsor_link'] ),
					esc_attr( $tier ),
					esc_url( $image[0] ),
					esc_attr( $sponsor['sponsor_name'] ),
					esc_html( $sponsor['sponsor_name'] )
				);
			endforeach;
		}
	endforeach;
	?>
</div>