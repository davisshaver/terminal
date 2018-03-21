<?php
/**
 * Post widget template.
 *
 * @package terminal
 */

?>

<div class="terminal-post-widget terminal-card-text">
	<?php
	if ( has_post_thumbnail() ) :
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'terminal-uncut-thumbnail-small' );
	?>
		<div class="terminal-image lazyload" data-bg="<?php echo esc_url( $thumb['0'] ); ?>">
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"></a>
		</div>
	<?php
	endif;
	?>
	<h4 class="terminal-headline-font">
		<a href="<?php the_permalink(); ?>" class="terminal-link-gray-light">
			<?php the_title(); ?>
		</a>
	</h4>
</div>
