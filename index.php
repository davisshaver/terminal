<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Terminal
 */

get_header(); ?>

<div id="container">
	<div id="body">
		<div class="content">
			<?php
				get_template_part( 'partials/featured' );
				terminal_print_index_header();
			?>
			<div id="stories">
				<?php terminal_print_stories_loop(); ?>
			</div>
		</div>
		<?php
			get_template_part( 'partials/sidebar' );
		?>
	</div>
	<?php if ( ! empty( get_previous_posts_link() ) || ! empty( get_next_posts_link() ) ) : ?>
		<div class="index-navigation">
			<div class="index-navigation-links">
				<?php if ( ! empty( get_previous_posts_link() ) ) : ?>
					<div class="alignleft"><?php previous_posts_link( '<span class="nav_button">&laquo; Previous</span>' ); ?></div>
				<?php endif; ?>
				<?php if ( ! empty( get_next_posts_link() ) ) : ?>
					<div class="alignright"><?php next_posts_link( '<span class="nav_button">Next &raquo;</span>', '' ); ?></div>
					<?php endif; ?>
			</div>
			<div style="clear: both;"></div>
		</div>
	<?php endif; ?>
</div>
<?php get_footer(); ?>
