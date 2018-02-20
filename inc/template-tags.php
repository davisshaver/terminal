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
 * Template function to print featured image credit (if available).
 */
function terminal_print_featured_image_caption() {
	$data = Terminal\Data::instance();
	$meta = $data->get_post_featured_meta();
	$meta = apply_filters( 'terminal_featured_meta', $meta );
	if ( ! empty( $meta['credit'] ) || ! empty( $meta['caption'] ) ) {
		printf(
			'<div class="featured-meta terminal-sidebar-body-font"><div class="featured-caption text-gray">%s</div><div class="featured-credit text-gray-lighter">%s</div></div>',
			wp_kses_post( $meta['caption'] ),
			esc_html( $meta['credit'] )
		);
	}
}

/**
 * Template function to print a recirc header.
 */
function terminal_print_author_bio_header() {
	printf(
		'<h3 id="author-bio-header" class="loop-header terminal-loop-header-font">%s</h2>',
		esc_html__( 'About the Author', 'terminal' )
	);
}

/**
 * Template function to print a comments header.
 */
function terminal_print_facebook_comments_header() {
	ob_start();
	get_template_part( 'partials/svg/down.svg' );
	$down = ob_get_contents();
	ob_end_clean();
	printf(
		'<h2 id="facebook-comments-header" class="loop-header terminal-loop-header-font">%s %s</h2>',
		esc_html__( 'Facebook Comments', 'terminal' ),
		$down
	);
}
/**
 * Template function to print a comments header.
 */
function terminal_print_comments_header() {
	printf(
		'<h2 id="comments-header" class="loop-header terminal-loop-header-font">%s</h2>',
		esc_html__( 'Comments', 'terminal' )
	);
}

/**
 * Template function to print an after article header.
 */
function terminal_print_after_article_header() {
	printf(
		'<h2 id="after-article-header" class="loop-header terminal-loop-header-font">%s</h2>',
		esc_html__( 'More Options to Share', 'terminal' )
	);
}

/**
 * Template function to print a recirc header.
 */
function terminal_print_recirc_header() {
	printf(
		'<h2 id="recirc-header" class="loop-header terminal-loop-header-font">%s</h2><a name="recirc"></a>',
		esc_html__( 'Other stories', 'terminal' )
	);
}

/**
 * Template function to print an index header. Encapsulates logic.
 */
function terminal_print_index_header() {
	if ( is_home() ) {
		if ( 0 === get_query_var( 'paged', 0 ) ) {
			$theme_mod = get_theme_mod( 'content_stories_header', __( 'Latest Stories', 'terminal' ) );
			if ( empty( $theme_mod ) ) {
				return;
			}
			printf(
				'<h2 id="index-header" class="loop-header terminal-loop-header-font">%s</h2><a name="latest"></a>',
				esc_html( $theme_mod )
			);
		} else {
			printf(
				'<h2 class="loop-header terminal-loop-header-font">%s</h2>',
				esc_html( __( 'Archival Stories', 'terminal' ) )
			);
		}
	} elseif ( is_search() ) {
		$search_query = get_search_query();
		printf(
			'<h2 class="loop-header terminal-loop-header-font">%s "%s"</h2>',
			esc_html( 'Search results for', 'terminal' ),
			esc_html( $search_query )
		);
	} else {
		printf(
			'<h2 class="loop-header terminal-loop-header-font">%s</h2>',
			esc_html( strip_tags( get_the_archive_title() ) )
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
 * @param int      $author_id Author id.
 * @param int|bool $default_author Opt default author.
 */
function terminal_print_avatar_amp( $author_id, $default_author = false ) {
	$data = Terminal\Data::instance();
	$terminal_headshot = $data->get_terminal_headshot( $author_id );
	if ( ! empty( $terminal_headshot ) ) {
		$image_src = wp_get_attachment_image_src( $terminal_headshot, 'terminal-thumbnail', false, array( 'scheme' => 'https' ) );
		if ( empty( $image_src ) ) {
			return;
		}
		return printf(
			'<amp-img src="%s" width="24" height="24" layout="fixed" style="border: none; border-radius: unset;"></amp-img>',
			esc_url( $image_src[0] )
		);
	}
	$local_avatar = $data->get_local_avatar_headshot( $author_id );
	if ( ! empty( $local_avatar ) ) {
		return printf(
			'<amp-img src="%s" width="24" height="24" layout="fixed" style="border: none; border-radius: unset;"></amp-img>',
			esc_url( $local_avatar['full'] )
		);
	}
	$default = null;
	if ( is_int( $default_author ) ) {
		$image = wp_get_attachment_image_src( $default_author, 'terminal-thumbnail' );
		if ( ! empty( $image ) ) {
			$default = $image[0];
		}
	}
	if ( ! empty( $default ) ) {
		return printf(
			'<amp-img src="%s" width="24" height="24" layout="fixed" style="border: none; border-radius: unset;"></amp-img>',
			esc_url( $default )
		);
	}
}

/**
 * Template function to print author avatar.
 *
 * @param int      $size Size.
 * @param int|bool $default_author Opt default author.
 */
function terminal_print_avatar( $size = 32, $default_author = false ) {
	$author_id = get_the_author_meta( 'ID' );
	$data = Terminal\Data::instance();
	$terminal_headshot = $data->get_terminal_headshot( $author_id );
	if ( ! empty( $terminal_headshot ) ) {
		echo wp_get_attachment_image( $terminal_headshot, 'terminal-thumbnail', false, array( 'scheme' => 'https' ) );
		return;
	}
	$local_avatar = $data->get_local_avatar_headshot( $author_id );
	if ( ! empty( $local_avatar ) ) {
		return printf(
			'<img src="%s" class="avatar avatar-full photo" />',
			esc_url( $local_avatar['full'] )
		);
	}
	$default = null;
	if ( is_int( $default_author ) ) {
		$image = wp_get_attachment_image_src( $default_author, 'terminal-thumbnail' );
		if ( ! empty( $image ) ) {
			$default = $image[0];
		}
	}
	if ( ! empty( $default ) ) {
		return printf(
			'<img src="%s" class="avatar avatar-full photo" />',
			esc_url( $default )
		);
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
 * Boolean helper for whether Broadstreet ads are enabled.
 *
 * @return boolean
 */
function terminal_has_broadstreet_enabled() {
	return false;
}

/**
 * Print data layer.
 */
function terminal_print_data_layer() {
	$data = Terminal\Data::instance();
	?>
	<script type="text/javascript">
		var terminal =
		<?php
			echo wp_json_encode( array(
				'debugMode' => getenv( 'WP_DEBUG' ),
				'inlineAds' => array(
					'enabled' => $data->has_inline_ads(),
					'unit'    => $data->get_inline_ads_tag(),
				),
				'single'    => $data->get_single_data_layer(),
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
	if ( isset( $option[ $key ] ) && ! empty( $option[ $key ] ) ) {
		if (
			'typography' === $name &&
			false !== strpos( $key, 'font' ) &&
			class_exists( '\Terminal\FM_Fonts' )
			) {
			$fm_fonts = Terminal\FM_Fonts::instance();
			$stylesheet = null;
			if ( ! empty( $fm_fonts->fonts[ $option[ $key ] ]['google'] ) ) {
				$style_key = str_replace( 'font', 'style', $key );
				$weight_key = str_replace( 'font', 'weight', $key );
				$weight = ! empty( $option[ $weight_key ] ) && 'default' !== $option[ $weight_key ] ? $option[ $weight_key ] : '400';
				$style = ! empty( $option[ $style_key ] ) && 'italic' === $option[ $style_key ] ? 'i' : null;
				$stylesheet = sprintf(
					'https://fonts.googleapis.com/css?family=%s:%s%s',
					esc_attr( $fm_fonts->fonts[ $option[ $key ] ]['google'] ),
					esc_attr( $weight ),
					esc_attr( $style )
				);
			}
			return array(
				'family' => $fm_fonts->fonts[ $option[ $key ] ]['font-family'],
				'stylesheet' => $stylesheet,
			);
		}
		return $option[ $key ];
	}
	return $default;
}


/**
 * Template function to get sidebar data for theme.
 *
 * @param array $default Template default.
 * @return array Prepared sidebar data.
 */
function terminal_get_sidebar_data( $default = array() ) {
	$data = Terminal\Data::instance();
	return $data->get_prepared_sidebar_data( $default );
}

/**
 * Template function to get header data for theme.
 *
 * @param array $default Template default.
 * @return array Prepared header data.
 */
function terminal_get_header_data( $default = array() ) {
	$data = Terminal\Data::instance();
	return $data->get_prepared_header_data( $default );
}

/**
 * Template function to get footer data for theme.
 *
 * @param array $default Template default.
 * @return array Prepared footer data.
 */
function terminal_get_footer_data( $default = array() ) {
	$data = Terminal\Data::instance();
	return $data->get_prepared_footer_data( $default );
}

/**
 * Template function to print stories loop.
 */
function terminal_print_stories_loop() {
	global $post;

	$count = 0;

	$data            = Terminal\Data::instance();
	$has_inline_ads  = $data->has_inline_ads();
	$inline_ads_unit = $data->get_inline_ads_tag();
	if ( have_posts() ) :
		while ( have_posts() ) :
			$count++;
			the_post();
			get_template_part( 'partials/content-loop', get_post_type( $post ) );
			if ( is_home() && ! is_paged() && $has_inline_ads && 0 === $count % 7 && ! empty( $inline_ads_unit ) ) {
				do_action( 'ad_layers_render_ad_unit', $inline_ads_unit );
			}
		endwhile;
	else :
		esc_html_e( 'No posts founds', 'terminal' );
	endif;
}

/**
 * Get byline data.
 *
 * @param array $default Default data.
 * @return array data
 */
function terminal_get_byline_options( $default = array() ) {
	$data = Terminal\Data::instance();
	return $data->get_prepared_byline_data( $default );
}

/**
 * Get layout data.
 *
 * @param array $default Default data.
 * @return array data
 */
function terminal_get_layout_data( $default = array() ) {
	$data = Terminal\Data::instance();
	return $data->get_prepared_layout_data( $default );
}

function terminal_get_users_by_role( $role ) {
	$wp_user_search = new WP_User_Query( array( 'role' => $role ) );
	$users       = $wp_user_search->get_results();
	return $users;
}

function terminal_authors( $user_roles = array( 'author', 'editor', 'administrator' ), $show_fullname = true ) {
	$users = array();
	echo '<ul>';
	foreach ( $user_roles as $role ) {
		$users = array_merge( $users, terminal_get_users_by_role( $role ) );
	}
	$users_with_posts = array();
	foreach ( $users as $user ) {
		$post_count = count_user_posts( $user->ID, 'post' );
		if ( $post_count ) {
			$users_with_posts[ $user->user_login ] = sprintf(
				'<li><a href="%s">%s (%s)</a></li>',
				get_author_posts_url( $user->ID ),
				$user->display_name,
				$post_count
			);
		}
	}
	ksort( $users_with_posts );
	// now we can spit the output out.
	foreach ( $users_with_posts as $user ) {
		echo $user;
	}
	echo '</ul>';
}