<?php
	if ( empty( terminal_get_sponsor_data( 'enable_sponsors' ) ) ) {
		return;
	}
?>
<div id="terminal-sponsors" class="terminal-sponsors-container">
	<?php terminal_print_sponsors_header(); ?>
	<div class="terminal-card terminal-card-featured terminal-card-single terminal-alignment-center">
		<?php
		$tiers = array( 'one', 'two', 'three', 'four', 'five' );
		foreach( $tiers as $tier ) :
			$tier_data = terminal_get_sponsor_data( 'tier_' . $tier . '_sponsors' );
			foreach( $tier_data as $sponsor ) :
				if (
					empty( $sponsor['sponsor_link'] ) ||
					empty( $sponsor['sponsor_media'] ) ||
					empty( $sponsor['sponsor_name'] )
				) {
					continue;
				}
				$image = wp_get_attachment_image_src( $sponsor['sponsor_media'], 'terminal-uncut-thumbnail', false, array( 'scheme' => 'https' ) );
				if ( empty( $image ) ) {
					continue;
				}
				printf(
					'<a href="%s" target="_blank" class="terminal-sponsor terminal-sponsor-tier-%s"><img src="%s" alt="%s">%s</a>',
					esc_url( $sponsor['sponsor_link'] ),
					esc_attr( $tier ),
					esc_url( $image[0] ),
					esc_attr( $sponsor['sponsor_name'] ),
					esc_html( $sponsor['sponsor_name'] )
				);
			endforeach;
		endforeach;
		?>
	</div>
</div>