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
			printf(
				'<a href="%s">%s</a>',
				esc_url( get_the_permalink() ),
				esc_attr( get_the_title() )
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
			'<a href="%s">%s</a>',
			esc_url( get_the_permalink() ),
			esc_attr( get_the_title() )
		);
		?>
	</h5>
</div>