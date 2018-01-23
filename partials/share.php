<?php
/**
 * Share partial.
 *
 * @package Terminal
 */

?>

<div class="terminal-share-footer terminal-follow">
	<div class="terminal-share-footer-content">
		<div class="terminal-share-footer-link terminal-twitter">
			<?php
			printf(
				'<a href="%s" target="_blank">',
				esc_url( 'https://twitter.com/intent/tweet/
				?url=' . rawurlencode( get_the_permalink() ) )
			);
			get_template_part( 'partials/svg/twitter.svg' );
			esc_html_e( ' Tweet', 'terminal' );
			?>
			</a>
		</div>
		<div class="terminal-share-footer-link terminal-facebook">
			<?php
			printf(
				'<a href="%s" target="_blank">',
				esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( get_the_permalink() ) )
			);
			get_template_part( 'partials/svg/facebook.svg' );
			esc_html_e( ' Share', 'terminal' );
			?>
			</a>
		</div>
	</div>
</div>
