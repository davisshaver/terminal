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
	<div id="content-container" class="index_container" style="width: 900px;">
		<div id="content">
			<div id="stories-header">
				<a name="latest"></a>
				<div id="stories-header-filters">
					<a
						id="filter-content-all"
						class="filter-select"
						href="<?php echo esc_url( terminal_home_link( 'latest' ) ); ?>">
						All
					</a>
					<a
						id="filter-content-staff"
						class="filter-select"
						href="<?php echo esc_url( terminal_home_link( 'staff' ) ); ?>">
						Staff
					</a>
					<a
						id="filter-content-community"
						class="filter-select"
						href="<?php echo esc_url( terminal_home_link( 'community' ) ); ?>">
						Community
					</a>
					<a
						id="filter-content-links"
						class="filter-select"
						href="<?php echo esc_url( terminal_home_link( 'links' ) ); ?>">
						Links
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
