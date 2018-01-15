<?php
/**
 * Single template file
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
					<div id="single-topbar">
						<div id="single-avatar">
						</div>
						<div id="single-author-and-date">
							<abbr id="single-date" title="<?php the_time( 'l, F j, Y \a\t g:ia' ); ?>"><?php echo esc_html( terminal_time_ago() ); ?></abbr>
							<div id="single-author"><?php the_author_posts_link(); ?></div>
							<div id="single-category"><?php the_category( ', ' ); ?></div>
							<div id="single-numberofcomments"><a href="<?php comments_link(); ?>"><strong>Comments</strong></a><span class="share-number">&nbsp;<?php terminal_print_comment_count_for_post(); ?></span></div>
							<?php
							if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
							?>
								<a href="<?php echo esc_url( get_edit_post_link() ); ?>"><img  height="14" width="14" src="<?php echo esc_url( get_template_directory_uri() ); ?>/client/static/images/edit.png" alt="E" /></a>
							<?php
							}
							?>
						</div>
					</div>
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
				<a name="respond"></a>
				<?php comments_template(); ?>
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
