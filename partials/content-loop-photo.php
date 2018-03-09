<?php
/**
 * Main content template.
 *
 * @package Terminal
 */
$terminal_no_photo_class = ! has_post_thumbnail() ? 'terminal-no-photo' : '';
?>

<div 
	id="photo-<?php the_ID(); ?>"
	<?php post_class( array( 'terminal-post-tracking', 'terminal-card', 'terminal-post-card', 'terminal-card-single', esc_html( $terminal_no_photo_class ) ) ); ?>
	data-terminal-post-id="<?php the_ID(); ?>"
	data-terminal-has-image="<?php echo has_post_thumbnail(); ?>"
	data-terminal-author="<?php esc_attr( the_author_meta( 'user_nicename' ) ); ?>"
	data-terminal-title="<?php the_title_attribute(); ?>"
	data-terminal-view="loop"
>
<?php
printf(
  '<div class="terminal-card-title terminal-no-select">%s</div>',
    esc_html( __( 'Featured Photo', 'terminal' ) )
);
if ( has_post_thumbnail() ) :
	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'terminal-uncut-thumbnail-large' );
?>
	<a
		id="post-image-link-<?php the_ID(); ?>"
		href="<?php the_permalink(); ?>"
		rel="bookmark"
		class="terminal-tracking terminal-card-image"
		title="<?php the_title_attribute(); ?>"
		data-terminal-post-id="link-<?php the_ID(); ?>"
		data-terminal-has-image="<?php has_post_thumbnail(); ?>"
		data-terminal-author="<?php esc_attr( the_author_meta( 'user_nicename' ) ); ?>"
		data-terminal-title="<?php the_title_attribute(); ?>"
		data-terminal-view="loop image"
	>
	<?php
		the_post_thumbnail( 'terminal-uncut-thumbnail' );
	?>
	</a>
<?php
endif;
?>
	<div class="terminal-card-text">
		<h1 class="terminal-headline-font terminal-stream-headline">
			<a 
				id="post-headline-link-<?php the_ID(); ?>"
				href="<?php the_permalink(); ?>" 
				class="terminal-tracking terminal-link-gray" 
				data-terminal-post-id="<?php the_ID(); ?>"
				data-terminal-has-image="<?php has_post_thumbnail(); ?>"
				data-terminal-author="<?php esc_attr( the_author_meta( 'user_nicename' ) ); ?>"
				data-terminal-title="<?php the_title_attribute(); ?>"
				data-terminal-view="loop headline"
			>
				<?php the_title(); ?>
			</a>
		</h1>
		<p class="terminal-credit">
			<?php terminal_print_photo_caption(); ?>
		</p>
	</div>
</div>
