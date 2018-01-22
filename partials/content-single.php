<?php
/**
 * Main single template.
 *
 * @package Terminal
 */

$single_data = terminal_get_layout_data( array(
	'single_meta_position' => 'top',
) );
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<div id="single">
		<?php
		if ( 'top' === $single_data['single_meta_position'] ) :
			get_template_part( 'partials/byline', get_post_type( $post ) );
		endif;
		?>
		<h1 class="terminal-headline-font"><?php the_title(); ?></h1>
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="image">
				<?php the_post_thumbnail( 'terminal-primary-thumbnail' ); ?>
			</div>
		<?php endif; ?>
		<?php
		if ( 'middle' === $single_data['single_meta_position'] ) :
			get_template_part( 'partials/byline', get_post_type( $post ) );
		endif;
		?>
		<div class="story-text terminal-body-font">
			<?php the_content( '<p>Read the rest of this entry &raquo;</p>' ); ?>
			<?php wp_link_pages(); ?> 
		</div>
		<?php
		if ( 'bottom' === $single_data['single_meta_position'] ) :
			get_template_part( 'partials/byline', get_post_type( $post ) );
		endif;
		?>
	</div>
	<a name="respond"></a>
	<?php
	if ( ! post_password_required() && comments_open( get_the_ID() ) ) :
		comments_template();
	endif;
	?>
</article>