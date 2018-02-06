<?php
/**
 * Hero template.
 *
 * @package terminal
 */

?>

<div class="hero-secondary-widget">
	<?php
	if ( has_post_thumbnail() ) :
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'terminal-widget-thumbnail' );
	?>
		<div class="image" style="background-image: url('<?php echo esc_url( $thumb['0'] ); ?>')">
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"></a>
		</div>
	<?php
	endif;
	?>
	<h2 class="terminal-headline-font">
		<a href="<?php the_permalink(); ?>" class="link-gray">
			<?php the_title(); ?>
		</a>
	</h2>
	<div class="story-text terminal-sidebar-body-font mobile-hide-1">
		<?php
			the_excerpt();
		?>
	</div>
</div>
