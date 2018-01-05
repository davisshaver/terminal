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
				<?php if ( 0 === get_query_var( 'paged', 0 ) ) { ?>
					<h2 id="top-stories-header"><?php echo esc_html( get_theme_mod( 'content_stories_header', __( 'Latest Stories', 'terminal' ) ) ); ?></h2>
					<a name="latest"></a>
				<?php } else { ?>
					<h2 id="top-stories-header"><?php echo esc_html( get_theme_mod( 'content_stories_header', __( 'Archival Stories', 'terminal' ) ) ); ?></h2>
				<?php } ?>
				<div id="stories-header-filters">
					<a
						id="filter-content-all"
						title="<?php esc_attr_e( 'Show all', 'terminal' ); ?>"
						class="filter-select <?php echo esc_attr( terminal_home_filter_class( 'all' ) ); ?>"
						href="<?php echo esc_url( terminal_home_link( 'all' ) ); ?>">
						<?php esc_html_e( 'All', 'terminal' ); ?>
					</a>
					<a
						id="filter-content-staff"
						title="<?php esc_attr_e( 'Filter to staff content', 'terminal' ); ?>"
						class="filter-select <?php echo esc_attr( terminal_home_filter_class( 'staff' ) ); ?>"
						href="<?php echo esc_url( terminal_home_link( 'staff' ) ); ?>">
						<?php esc_html_e( 'Staff', 'terminal' ); ?>
					</a>
					<a
						id="filter-content-community"
						title="<?php esc_attr_e( 'Filter to community content', 'terminal' ); ?>"
						class="filter-select <?php echo esc_attr( terminal_home_filter_class( 'community' ) ); ?>"
						href="<?php echo esc_url( terminal_home_link( 'community' ) ); ?>">
						<?php esc_html_e( 'Community', 'terminal' ); ?>
					</a>
					<a
						id="filter-content-links"
						title="<?php esc_attr_e( 'Filter to links', 'terminal' ); ?>"
						class="filter-select <?php echo esc_attr( terminal_home_filter_class( 'links' ) ); ?>"
						href="<?php echo esc_url( terminal_home_link( 'links' ) ); ?>">
						<?php esc_html_e( 'Links', 'terminal' ); ?>
					</a>
				</div>
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
