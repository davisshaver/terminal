<?php
/**
 * Post widget template.
 *
 * @package terminal
 */

?>

<div class="post-widget post-widget-loop">
	<?php
	if ( has_post_thumbnail() ) :
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'terminal-thumbnail' );
	?>
		<div class="image" style="background-image: url('<?php echo esc_url( $thumb['0'] ); ?>')">
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"></a>
		</div>
	<?php
	endif;
	?>
	<h4 class="terminal-headline-font story-text">
		<a href="<?php the_permalink(); ?>" class="link-gray">
			<?php the_title(); ?>
		</a>
	</h4>
</div>
