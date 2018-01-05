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

<div id="master-content">
	<div id="master-content-container">
		<div id="content">
			<div id="stories-header">
				<h2 id="top-stories-header"><?php echo esc_html( get_theme_mod( 'content_stories_header', __( 'Latest Stories', 'terminal' ) ) ); ?></h2>
				<a name="latest"></a>
				<div id="stories-header-filters">
					<a
						id="filter-content-all"
						title="<?php esc_attr_e( 'Just show the latest', 'terminal' ); ?>"
						class="filter-select <?php echo esc_attr( terminal_home_filter_class( 'latest' ) ); ?>"
						href="<?php echo esc_url( terminal_home_link( 'latest' ) ); ?>">
						<?php esc_html_e( 'Latest', 'terminal' ); ?>
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
				<?php get_template_part( 'loop' ); ?>
			</div>
		</div>
	</div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
