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

<div id="terminal-container" class="terminal-container">
	<?php
	if ( is_home() && ! is_paged() ) {
		echo '<div class="terminal-top-container">';
		get_template_part( 'partials/featured' );
		terminal_print_template_part(
			'main-sidebar'
		);
		echo '</div>';
	} else {
		terminal_print_template_part(
			'sidebar',
			array(
				'sidebar' => 'terminal-primary-sidebar'
			)
		);
	}

	?>
	<?php
	terminal_print_index_header();
	if ( is_author() ) {
		echo '<div class="terminal-card terminal-card-single terminal-post-card">';
		get_template_part( 'partials/author-snippet' );
		echo '</div>';
	}
	?>
	<?php
		terminal_print_template_part(
			'sidebar',
			array(
				'sidebar' => 'terminal-stream-start'
			)
		);
		terminal_print_stories_loop();
		terminal_print_template_part(
			'sidebar',
			array(
				'sidebar' => 'terminal-stream-end'
			)
		);
	?>
	<?php if ( ! empty( get_previous_posts_link() ) || ! empty( get_next_posts_link() ) ) : ?>
		<div class="terminal-pagination terminal-card terminal-card-full">
			<?php if ( ! empty( get_previous_posts_link() ) ) : ?>
				<?php previous_posts_link( '&laquo; Previous' ); ?>
			<?php endif; ?>
			<?php if ( ! empty( get_next_posts_link() ) ) : ?>
				<?php next_posts_link( 'Next &raquo;', '' ); ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
<?php get_footer(); ?>
