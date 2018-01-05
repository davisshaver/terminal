<?php
/**
 * The template for displaying the footer.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Terminal
 */

?>
<div id="footer">
	<div id="footer-inside">
		<div id="footer-leaderboard">
		</div>
		<div id="footer-left">
			<div id="footer-rss">
				<a href="<?php echo esc_url( bloginfo( 'rss2_url' ) ); ?>">
					<img height="18" width="18" src="<?php get_template_directory_uri(); ?>/images/rss.png" alt="" />
				</a>
			</div>
			<?php wp_nav_menu( 'menu=footer&menu_class=random-list' ); ?>
		</div>
		<div id="footer-right">
			<p>&copy;<?php echo esc_html( date( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'title' ) ); ?></p>
		</div>
	</div>
</div>
<?php wp_footer(); ?>

</body>
</html>
