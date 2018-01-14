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