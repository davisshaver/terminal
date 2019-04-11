<?php
/**
 * Popular post partial.
 *
 * @package Terminal
 */
$post = get_post();
if ( ! $post ) {
	return '';
}

$post_thumbnail_id = get_post_thumbnail_id( $post );

echo '<div class="terminal-list-item">';
	if ( has_post_thumbnail() ) : ?>
		<figure class="entry__thumbnail">
			<?php
			$components = parse_url( get_the_permalink() );
			$off_site = ! empty( $components['host'] ) && strcasecmp( $components['host'], gethostname() );
			if ( $off_site ) {
				$post_target = '_blank';
			} else {
				$post_target = '_self';
			}
			printf(
				'<a href="%s" title="%s" target="%s">',
				esc_url( get_the_permalink() ),
				esc_attr( get_the_title() ),
				esc_attr( $post_target )
			);
			?>
				<?php the_post_thumbnail( 'ampnews-1040x585', array(
					'data-amp-layout' => 'intrinsic',
				) ); ?>
			</a>
		</figure>
	<?php endif; ?>
	<h5 class="entry__title entry__title-sidebar">
		<?php
		printf(
			'<a href="%s" target="%s">%s</a>',
			esc_url( get_the_permalink() ),
			esc_attr( $post_target ),
			esc_attr( get_the_title() )
		);
		?>
	</h5>
</div>