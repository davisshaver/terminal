<?php
/**
 * Page template file
 *
 * @package Terminal
 */

get_header(); ?>
<div id="container">
	<div id="body">
		<div id="content">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
		?>
			<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
				<div id="single">
					<h1><?php the_title(); ?></h1>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="image">
							<?php the_post_thumbnail( 'terminal-primary-thumbnail' ); ?>
						</div>
					<?php endif; ?>
					<div class="story-text">
						<?php the_content( '<p>Read the rest of this entry &raquo;</p>' ); ?>
					</div>
				</div>
			</article>
		<?php
			endwhile;
		else :
		?>
			<p><?php esc_html_e( 'Sorry, no posts matched your criteria.', 'terminal' ); ?></p>
		<?php
		endif;
		?>
		</div>
		<div id="sidebar">
			<?php get_sidebar(); ?>	
		</div>
	</div>
</div>
<?php get_footer(); ?>
