<?php
/**
 * Template tags.
 *
 * @package Terminal
 */

/**
 * Template function for home link.
 *
 * @param string $type Type.
 *
 * @return string home link.
 */
function terminal_home_link( $type ) {
	if ( terminal_home_has_filter( $type ) ) {
		return '#';
	}
	if ( is_single() ) {
		// Bail if we're in single!
		$home_url = home_url();
	} else {
		global $wp;
		$home_url = home_url( $wp->request );
	}
	switch ( $type ) {
		case 'all':
			return $home_url;
		case 'staff':
			return $home_url . '?filter=staff';
		case 'community':
			return $home_url . '?filter=community';
		case 'links':
			return $home_url . '?filter=links';
		default:
			return $home_url;
	}
}

/**
 * Determine whether home has filter.
 *
 * @param string $type Type.
 * @return boolean Whether has filter.
 */
function terminal_home_has_filter( $type ) {
	$filter = get_query_var( 'filter', 'all' );
	switch ( $type ) {
		case 'all':
			if ( 'all' === $filter ) {
				return true;
			}
			break;
		case 'staff':
			if ( 'staff' === $filter ) {
				return true;
			}
			break;
		case 'community':
			if ( 'community' === $filter ) {
				return true;
			}
			break;
		case 'links':
			if ( 'links' === $filter ) {
				return true;
			}
			break;
		default:
			return false;
	}
}

/**
 * Template function to print class for active link.
 *
 * @param string $type Type.
 * @return string Class.
 */
function terminal_home_filter_class( $type ) {
	if ( terminal_home_has_filter( $type ) ) {
		return 'active-filter';
	}
	return 'inactive-filter';
}

/**
 * Template tag to get time ago for post in loop.
 *
 * @return string Time ago.
 */
function terminal_time_ago() {
	return human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . ' ago';
}

/**
 * Template function to print an index header. Encapsulates logic.
 */
function terminal_print_index_header() {
	if ( is_home() ) {
		if ( 0 === get_query_var( 'paged', 0 ) ) {
			printf(
				'<h2 id="top-stories-header">%s</h2><a name="latest"></a>',
				esc_html( get_theme_mod( 'content_stories_header', __( 'Latest Stories', 'terminal' ) ) )
			);
		} else {
			printf(
				'<h2 id="top-stories-header">%s</h2>',
				esc_html( __( 'Archival Stories', 'terminal' ) )
			);
		}
	} elseif ( is_search() ) {
		$search_query = get_search_query();
		printf(
			'<h2 id="top-stories-header">%s "%s"</h2>',
			esc_html( 'Search results for', 'terminal' ),
			esc_html( $search_query )
		);
	} else {
		printf(
			'<h2 id="top-stories-header">%s</h2>',
			esc_html( the_archive_title() )
		);
	}
}

/**
 * Template function to get Twitter count for a post.
 */
function terminal_print_twitter_count_for_post() {
	$data  = Terminal\Data::instance();
	$count = $data->get_twitter_count_for_post();
	if ( 0 !== $count ) {
		echo esc_html( $count );
	}
}

/**
 * Template function to get Comment count for a post.
 */
function terminal_print_comment_count_for_post() {
	$data  = Terminal\Data::instance();
	$count = $data->get_comment_count_for_post();
	if ( 0 !== $count ) {
		echo esc_html( $count );
	}
}

/**
 * Template function to get Facebook count for a post.
 */
function terminal_print_facebook_count_for_post() {
	$data  = Terminal\Data::instance();
	$count = $data->get_facebook_count_for_post();
	if ( 0 !== $count ) {
		echo esc_html( $count );
	}
}

/**
 * Template function to print author avatar.
 *
 * @param int $size Size.
 */
function terminal_print_avatar( $size = 32 ) {
	if ( function_exists( 'get_simple_local_avatar' ) ) {
		echo wp_kses_post( get_simple_local_avatar( get_the_author_meta( 'ID' ), $size, null, false, array( 'scheme' => 'https' ) ) );
	} else {
		echo wp_kses_post( get_avatar( get_the_author_meta( 'ID' ), $size, null, false, array( 'scheme' => 'https' ) ) );
	}
}

/**
 * Boolean helper for whether ads enabled.
 *
 * @return boolean
 */
function terminal_has_ads_enabled() {
	return true;
}

/**
 * Print data layer.
 */
function terminal_print_data_layer() { ?>
	<script type="text/javascript">
		var terminal =
		<?php
			echo wp_json_encode( array(
				// @todo Move these to a setting.
				'debugMode' => WP_DEBUG,
				'clientProfiles' => array(
					// OS Main.
					'UA-10930536-1',
					// OS site + app.
					'UA-10930536-4',
					// SC.com and OS combined.
					'UA-1249139-15',
				),
			) );
		?>
		;
		dataLayer = [{
			terminal
		}];
	</script>
<?php
}

/**
 * Template helper for FM Customizer options.
 *
 * @param string      $name    FM group name.
 * @param string      $key  FM option name.
 * @param bool|string $default Default.
 * @return string
 */
function terminal_get_fm_theme_mod( $name, $key, $default = false ) {
	$option = get_theme_mod( $name, array() );
	if ( isset( $option[ $key ] ) ) {
		return $option[ $key ];
	}
	return $default;
}
