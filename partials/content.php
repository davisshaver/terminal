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
