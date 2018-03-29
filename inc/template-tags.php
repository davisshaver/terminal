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
		echo '<div class="terminal-featured-meta terminal-sidebar-body-font terminal-limit-max-content-width terminal-text-gray-light">';
		if ( ! empty( $meta['caption'] ) ) {
			printf(
				'<div class="terminal-featured-caption">%s</div>',
				wp_kses_post( $meta['caption'] )
			);
		}
		if ( ! empty( $meta['credit'] ) ) {
			ob_start();
			get_template_part( 'partials/svg/camera.svg' );
			$camera = ob_get_contents();
			ob_end_clean();
			printf(
				'<div class="terminal-credit terminal-limit-max-content-width-add-margin">%s %s</div>',
				$camera,
				esc_html( $meta['credit'] )
			);
		}
		echo '</div>';
	}
}


/**
 * Template function to print featured image credit (if available).
 */
function terminal_print_photo_caption() {
	$instance = Terminal\Photos::instance();
	$photographer = $instance->get_photographer();
	if ( ! empty( $photographer ) ) {
		ob_start();
		get_template_part( 'partials/svg/camera.svg' );
		$camera = ob_get_contents();
		ob_end_clean();
		printf(
			'<span>%s %s</span>',
			$camera,
			esc_html( $photographer )
		);
	}
}

/**
 * Template function to print a recirc header.
 */
function terminal_print_author_bio_header() {
	printf(
		'<h2 class="terminal-header terminal-header-font">%s</h2>',
		esc_html__( 'About the Author', 'terminal' )
	);
}

/**
 * Template function to print a comments header.
 */
function terminal_print_comments_header() {
	printf(
		'<h2 class="terminal-comments-header terminal-header terminal-header-font">%s</h2>',
		esc_html__( 'Comments', 'terminal' )
	);
}

/**
 * Template function to print an after article header.
 */
function terminal_print_after_article_header() {
	printf(
		'<h2 class="terminal-after-article-header terminal-header terminal-header-font">%s</h2>',
		esc_html__( 'More Options to Share', 'terminal' )
	);
}

/**
 * Template function to print a recirc header.
 */
function terminal_print_recirc_header() {
	printf(
		'<h2 class="terminal-header terminal-header-font">%s</h2>',
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
				'<div class="terminal-header terminal-header-font"><h2><a name="recent">%s</a></h2></div>',
				esc_html( $theme_mod )
			);
		} else {
			printf(
			'<div class="terminal-header terminal-header-font"><h2>%s</h2></div>',
				esc_html( __( 'Archival Stories', 'terminal' ) )
			);
		}
	} elseif ( is_search() ) {
		$search_query = get_search_query();
		printf(
			'<div class="terminal-header terminal-header-font"><h2>%s %s</h2></div>',
			esc_html( 'Search results for', 'terminal' ),
			esc_html( $search_query )
		);
	} else {
		printf(
			'<div class="terminal-header terminal-header-font"><h2>%s</h2></div>',
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
 * Template function to check if AMP tag exists.
 *
 * @param string $amp_tag string AMP tag.
 * @return boolean whether has tag
 */
function terminal_has_amp_tag( $amp_tag ) {
	if ( empty( $amp_tag ) || ! is_string( $amp_tag ) ) {
		return false;
	}
	$data = Terminal\Data::instance();
	$tag = $data->get_ad_data( 'amp_' . $amp_tag );
	if ( empty( $tag ) ) {
		return false;
	}
	return true;
}

/**
 * Template function to print AMP tag.
 *
 * @param string $amp_tag string AMP tag.
 */
function terminal_print_amp_tag( $amp_tag ) {
	$data = Terminal\Data::instance();
	if ( empty( $amp_tag ) || ! is_string( $amp_tag ) ) {
		return;
	}
	$tag = $data->get_ad_data( 'amp_' . $amp_tag );
	$tag = str_replace( '<p>', '', $tag );
	$tag = str_replace( '</p>', '', $tag );
	echo $tag;
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
			'<amp-img src="%s" width="24" height="24" layout="fixed"></amp-img>',
			esc_url( $image_src[0] )
		);
	}
	$local_avatar = $data->get_local_avatar_headshot( $author_id );
	if ( ! empty( $local_avatar ) ) {
		return printf(
			'<amp-img src="%s" width="24" height="24" layout="fixed"></amp-img>',
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
			'<amp-img src="%s" width="24" height="24" layout="fixed"></amp-img>',
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
		echo wp_get_attachment_image( $terminal_headshot, 'terminal-thumbnail', false, array( 'scheme' => 'https', 'class' => 'avatar avatar-full photo' ) );
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
 * Print data layer.
 */
function terminal_print_data_layer() {
	printf(
		'<script type="text/javascript">var terminal = %s; dataLayer = [{ terminal }];</script>',
		terminal_print_data_layer_json( false )
	);
}

/**
 * Print unified JSON for data layers.
 */
function  terminal_print_data_layer_json( $echo = true ) {
	$data = Terminal\Data::instance();
	$data_layer = wp_json_encode( array(
		'debugMode' => getenv( 'WP_DEBUG' ),
			'inlineAds' => array(
				'enabled' => $data->has_inline_ads(),
				'unit'    => $data->get_inline_ads_tag(),
			),
			'single'    => $data->get_single_data_layer(),
			'isSearch'    => is_search(),
			'parsely'     => array(
				'enabled'     => (bool) getenv( 'TERMINAL_ENABLE_PARSELY_SEARCH' ),
				'apiKey'      => getenv( 'TERMINAL_PARSELY_API_KEY' ),
			),
	) );
	if ( ! $echo ) {
		return $data_layer;
	}
	echo $data_layer;
}

/**
 * Print data layer for AMP.
 */
function terminal_print_data_layer_amp() {
	printf(
		'<script type="application/json">{ "vars": %s }</script>',
		terminal_print_data_layer_json( false )
	);
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
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			terminal_print_template_part( 'content-loop', array(
				'post_type' => terminal_get_post_type( $post )
			) );
		endwhile;
	else :
		echo '<div class="terminal-card terminal-card-single terminal-post-card terminal-limit-max-content-width-add-margins">';
		echo '<div class="terminal-card-title terminal-no-select">';
		esc_html_e( 'No posts founds', 'terminal' );
		echo '</div><div class="terminal-card-text terminal-body-font terminal-limit-max-content-width"><p>';
		esc_html_e( 'Try a different page.', 'terminal' );
		echo '</p></div></div>';
		get_template_part( 'partials/recirc' );
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

/**
 * Get terminal post type.
 *
 * @param object $post
 * @return string post type mapping
 */
function terminal_get_post_type( $post = false ) {
	$post_types = array(
		'post' => 'post',
	);
	if ( getenv( 'TERMINAL_ENABLE_LINK_POST_TYPE' ) ) {
		$links = Terminal\Links::instance();
		$post_types['link'] = $links->get_link_post_type();
	}
	if ( getenv( 'TERMINAL_ENABLE_BOOK_POST_TYPE' ) ) {
		$books = Terminal\Books::instance();
		$post_types['book'] = $books->get_book_post_type();
	}
	if ( getenv( 'TERMINAL_ENABLE_PHOTO_POST_TYPE' ) ) {
		$photos = Terminal\Photos::instance();
		$post_types['photo'] = $photos->get_photo_post_type();
	}
	if ( getenv( 'TERMINAL_ENABLE_COMMUNITY_POST_TYPE' ) ) {
		$community = Terminal\Community::instance();
		$post_types['community'] = $community->get_community_post_type();
	}
	$post_types = array_flip(
		array_filter(
			$post_types,
			'boolval'
		)
	);
	return $post_types[ $post->post_type ];
}

/**
 * Return the photo photographer.
 */
function terminal_the_photo_photographer() {
	$photos = Terminal\Photos::instance();
	return $photos->get_photographer();
}

/**
 * Return the name of the category.
 */
function terminal_get_nav_menu_title( $slug ) {
	$locations = get_nav_menu_locations();
	if ( ! isset( $locations[ $slug ] ) ) {
		return false;
	}
	$menu_obj = get_term( $locations[$slug], 'nav_menu' );
	return $menu_obj->name;
}

/**
* Get a rendered template part
*
* @param string $template
* @param array $vars
* @return string
*/
function terminal_get_template_part( $template, $vars = array() ) {
 $full_path = get_template_directory() . '/partials/' . sanitize_file_name( $template ) . '.php';
 if ( ! file_exists( $full_path ) ) {
	 return '';
 }
 ob_start();
 // @codingStandardsIgnoreStart
 if ( ! empty( $vars ) ) {
	 extract( $vars );
 }
 // @codingStandardsIgnoreEnd
 include $full_path;
 return ob_get_clean();
}


/**
* Print a rendered template part
*
* @param string $template
* @param array $vars
* @return string
*/
function terminal_print_template_part( $template, $vars = array() ) {
	echo terminal_get_template_part( $template, $vars );
 }
 