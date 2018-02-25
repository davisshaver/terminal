<?php
/**
 * Hero template.
 *
 * @package terminal
 */

?>
<div 
	id="hero-secondary-post-<?php the_ID(); ?>"
	class="terminal-post-tracking hero-secondary-widget"
	data-terminal-post-id="<?php the_ID(); ?>"
	data-terminal-has-image="<?php echo has_post_thumbnail(); ?>"
	data-terminal-author="<?php esc_attr( the_author_meta( 'user_nicename' ) ); ?>"
	data-terminal-title="<?php the_title_attribute(); ?>"
	data-terminal-view="featured-hero-secondary"
>
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
