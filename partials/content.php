<?php
/**
 * Main content template.
 *
 * @package Terminal
 */

?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="headline">
		<a href="<?php the_permalink(); ?>">
			<?php the_title(); ?>
		</a>
	</div>
	<div class="dateline">
		<div class="avatar">
			<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) ); ?>">
				<?php terminal_print_avatar(); ?>
			</a>
		</div>
		<?php esc_html_e( 'Posted', 'terminal' ); ?>
		<abbr title="<?php the_time( 'l, F j, Y \a\t g:ia' ); ?>" class="time-1">
			<?php echo esc_html( terminal_time_ago() ); ?>
		</abbr> 
		<?php esc_html_e( 'in', 'terminal' ); ?>
		<div class="category">
			<?php the_category( ' & ' ); ?>
		</div>
		<?php
			/**
			 * See below for @todo after post types are integrated.
			 * esc_html_e( 'about', 'terminal' ); ?>
			 * <div class="topic">
			 * <a href="<?php echo esc_url( get_permalink( $topic_id ) ); ?>">
			 * <?php echo esc_html( $topic_name ); ?>
			 * </a>
			 * </div>
			 */
		?>
		<?php
			/**
			 * Author.
			 * Another @todo, cleanup after bylines installed.
			 */
		?>
		<?php esc_html_e( 'by', 'terminal' ); ?>
		<div class="author">
			<?php the_author_posts_link(); ?>
		</div>
		<?php
		if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
		?>
			<div class="icon-container">
				<div class="icon">
					<a href="<?php echo esc_url( get_edit_post_link() ); ?>">
						<img height="14" width="14" src="<?php echo esc_url( get_template_directory_uri() ); ?>/client/static/images/edit.png" alt="E" />
					</a>
				</div>
			</div>
		<?php
		}
		?>
	</div>
	<div class="post-row">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="image">
				<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
					<?php the_post_thumbnail( 'terminal-primary-thumbnail', array( 'title' => get_the_title() ) ); ?>
				</a>
			</div>
		<?php endif; ?>
		<div class="story-text">
			<?php
				the_excerpt();
			?>
		</div>
		<div class="more">
			<div class="more-facebook">
				<a href='http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&amp;t=<?php the_title_attribute(); ?>' class='facebookShare'>FB</a>
				<span class="fb_like_count share-number"><?php terminal_print_facebook_count_for_post(); ?></span>
			</div>
			<div class="more-twitter">
				<a href="http://twitter.com/share?url=<?php the_permalink(); ?>&amp;counturl=<?php echo esc_url( wp_get_canonical_url() ); ?>&amp;via=OnwardState&amp;text=<?php the_title_attribute(); ?>" class="twitterShare" target=_blank>T</a>
				<span class="twitter_tweet_count share-number"><?php terminal_print_twitter_count_for_post(); ?></span>
			</div>
			<div class="more-comments">
				<a href="<?php the_permalink(); ?>#comments">💬</a> <span class="share-number"><?php terminal_print_comment_count_for_post(); ?></span>
			</div>
			<div class="more-text">
				<a href="<?php the_permalink(); ?>"><strong><?php esc_html_e( 'Read Post', 'terminal' ); ?></strong></a>
			</div>
		</div>
	</div>
</div>
