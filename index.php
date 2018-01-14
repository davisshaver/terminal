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
		<div id="content">
			<div id="stories-header">
				<?php terminal_print_index_header(); ?>
			</div>
			<div id="stories">
				<?php
					if ( have_posts() ) :
						while ( have_posts() ) :
							the_post();
							get_template_part( 'partials/content', get_post_type( $post ) );
						endwhile;
					endif;
				?>
			</div>
		</div>
		<div id="sidebar">
			<?php get_sidebar(); ?>	
		</div>
	</div>
	<div class="index-navigation">
		<div class="index-navigation-links">
			<div class="alignleft"><?php previous_posts_link( '<span class="nav_button">&laquo; Now</span>' ); ?></div>
			<div class="alignright"><?php next_posts_link( '<span class="nav_button">Then &raquo;</span>', '' ); ?></div>
		</div>
		<div style="clear: both;"></div>
	</div>
</div>
<?php get_footer(); ?>
