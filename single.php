<?php
/**
 * Single template file
 *
 * @package Terminal
 */

get_header(); ?>
<div id="container">
	<div id="body">
		<div class="content">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
		?>
			<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
				<div id="single">
					<div id="single-topbar" class="terminal-single-meta-font">
						<div class="single-avatar">
							<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) ); ?>">
								<?php terminal_print_avatar( 50 ); ?>
							</a>
						</div>
						<div id="single-author-and-date">
							<abbr id="single-date" title="<?php the_time( 'l, F j, Y \a\t g:ia' ); ?>"><?php echo esc_html( terminal_time_ago() ); ?></abbr>
							<div id="single-author"><?php the_author_posts_link(); ?></div>
							<div id="single-category"><?php the_category( ', ' ); ?></div>
							<?php
							if ( comments_open( get_the_ID() ) ) :
							?>
								<div id="single-numberofcomments"><a href="<?php comments_link(); ?>"><strong>Comments</strong></a><span class="share-number">&nbsp;<?php terminal_print_comment_count_for_post(); ?></span></div>
							<?php
							endif;
							if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
							?>
								<a href="<?php echo esc_url( get_edit_post_link() ); ?>"><img  height="14" width="14" src="<?php echo esc_url( get_template_directory_uri() ); ?>/client/static/images/edit.png" alt="E" /></a>
							<?php
							}
							?>
						</div>
					</div>
					<h1 class="terminal-headline-font"><?php the_title(); ?></h1>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="image">
							<?php the_post_thumbnail( 'terminal-primary-thumbnail' ); ?>
						</div>
					<?php endif; ?>
					<div class="story-text terminal-body-font">
						<?php the_content( '<p>Read the rest of this entry &raquo;</p>' ); ?>
						<?php wp_link_pages(); ?> 
					</div>
				</div>
				<a name="respond"></a>
				<?php
				if ( ! post_password_required() && comments_open( get_the_ID() ) ) :
					comments_template();
				endif;
				?>
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
		<?php
			get_template_part( 'partials/sidebar' );
		?>
	</div>
</div>
<?php get_footer(); ?>
