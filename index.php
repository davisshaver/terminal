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

get_header();
echo '<div class="terminal-filter terminal-link-gray terminal-scroll-show terminal-viewing-popular-active">';
echo '<div class="terminal-filter-inside">';
printf(
	'<a href="#featured" class="terminal-viewing-featured-active">%s</a>',
	esc_html( __( 'Featured', 'terminal' ) )
);
printf(
	'<a href="#popular" class="terminal-popular-link terminal-viewing-popular-active">%s</a>',
	esc_html( __( 'Trending', 'terminal' ) )
);
printf(
	'<a href="#recent" class="terminal-viewing-content-active">%s</a>',
	esc_html( __( 'Recent', 'terminal' ) )
);
echo '</div>';
echo '</div>';
echo '<a id="terminal-featured" name="featured"></a>';
?>
<div id="terminal-container" class="terminal-container">
	<?php
	if ( is_home() && ! is_paged() ) {
		echo '<div class="terminal-top-container">';
		get_template_part( 'partials/featured' );
		terminal_print_template_part(
			'main-sidebar'
		);
		echo '</div>';
		get_template_part( 'partials/breakout' );
		get_template_part( 'partials/popular' );
		echo '<div id="terminal-content-container" class="terminal-content-container">';
		terminal_print_index_header();
		if ( is_author() ) {
			echo '<div class="terminal-card terminal-card-single terminal-post-card">';
			get_template_part( 'partials/author-snippet' );
			echo '</div>';
		}
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
		echo '</div>';
	} else {
		echo '<div class="terminal-top-container">';
		echo '<div class="terminal-hero-container">';
			terminal_print_index_header();
			if ( is_author() ) {
				echo '<div class="terminal-card terminal-card-double terminal-post-card">';
				get_template_part( 'partials/author-snippet' );
				echo '</div>';
			}
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
		echo '</div>';
		terminal_print_template_part(
			'main-sidebar'
		);
		echo '</div>';
		echo '<div id="terminal-content-container" class="terminal-content-container">';
		echo '</div>';
	}

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
