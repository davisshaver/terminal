<?php
/**
 * Template tags.
 *
 * @package Terminal
 */

/**
 * Get terminal post type.
 *
 * @param boolean $values_only Return values only.
 * @return array post types
 */
function terminal_get_post_types( $values_only = true ) {
	$post_types = array(
		'post' => 'post',
	);

	if ( getenv( 'TERMINAL_ENABLE_LINK_POST_TYPE' ) ) {
		$links              = Terminal\Links::instance();
		$post_types['link'] = $links->get_link_post_type();
	}

	if ( getenv( 'TERMINAL_ENABLE_BOOK_POST_TYPE' ) ) {
		$books              = Terminal\Books::instance();
		$post_types['book'] = $books->get_book_post_type();
	}

	if ( getenv( 'TERMINAL_ENABLE_COMMUNITY_POST_TYPE' ) ) {
		$community               = Terminal\Community::instance();
		$post_types['community'] = $community->get_community_post_type();
	}

	if ( getenv( 'TERMINAL_ENABLE_HOUSING_POST_TYPE' ) ) {
		$housing               = Terminal\Housing::instance();
		$post_types['housing'] = $housing->get_housing_post_type();
	}

	if ( getenv( 'TERMINAL_ENABLE_DEALS_POST_TYPE' ) ) {
		$deal               = Terminal\Deals::instance();
		$post_types['deal'] = $deal->get_deal_post_type();
	}

	if ( getenv( 'TERMINAL_ENABLE_OBITUARY_POST_TYPE' ) ) {
		$obit                   = Terminal\Obituaries::instance();
		$post_types['obituary'] = $obit->get_obituary_post_type();
	}

	if ( getenv( 'TERMINAL_ENABLE_RELEASES_POST_TYPE' ) ) {
		$obit                  = Terminal\Releases::instance();
		$post_types['release'] = $obit->get_releases_post_type();
	}

	if ( $values_only ) {
		return array_values( $post_types );
	}

	return $post_types;
}

/**
 * Get terminal author post types.
 *
 * @param boolean $values_only Return values only.
 * @return array post types
 */
function terminal_get_author_post_types( $values_only = true ) {
	$post_types = array(
		'post' => 'post',
	);

	if (
		getenv( 'TERMINAL_ENABLE_LINK_POST_TYPE' ) &&
		getenv( 'TERMINAL_ENABLE_LINK_POST_TYPE_ON_AUTHOR' )
	) {
		$links              = Terminal\Links::instance();
		$post_types['link'] = $links->get_link_post_type();
	}

	if (
		getenv( 'TERMINAL_ENABLE_BOOK_POST_TYPE' ) &&
		getenv( 'TERMINAL_ENABLE_BOOK_POST_TYPE_ON_AUTHOR' )
	) {
		$books              = Terminal\Books::instance();
		$post_types['book'] = $books->get_book_post_type();
	}

	if (
		getenv( 'TERMINAL_ENABLE_COMMUNITY_POST_TYPE' ) &&
		getenv( 'TERMINAL_ENABLE_COMMUNITY_POST_TYPE_ON_AUTHOR' )
	) {
		$community               = Terminal\Community::instance();
		$post_types['community'] = $community->get_community_post_type();
	}

	if (
		getenv( 'TERMINAL_ENABLE_HOUSING_POST_TYPE' ) &&
		getenv( 'TERMINAL_ENABLE_HOUSING_POST_TYPE_ON_AUTHOR' )
	) {
		$housing               = Terminal\Housing::instance();
		$post_types['housing'] = $housing->get_housing_post_type();
	}

	if (
		getenv( 'TERMINAL_ENABLE_DEALS_POST_TYPE' ) &&
		getenv( 'TERMINAL_ENABLE_DEALS_POST_TYPE_ON_AUTHOR' )
	) {
		$deal               = Terminal\Deals::instance();
		$post_types['deal'] = $deal->get_deal_post_type();
	}

	if (
		getenv( 'TERMINAL_ENABLE_OBITUARY_POST_TYPE' ) &&
		getenv( 'TERMINAL_ENABLE_OBITUARY_POST_TYPE_ON_AUTHOR' )
	) {
		$obit                   = Terminal\Obituaries::instance();
		$post_types['obituary'] = $obit->get_obituary_post_type();
	}

	if (
		getenv( 'TERMINAL_ENABLE_RELEASES_POST_TYPE' ) &&
		getenv( 'TERMINAL_ENABLE_RELEASES_POST_TYPE_ON_AUTHOR' )
	) {
		$release                  = Terminal\Releases::instance();
		$post_types['release'] = $release->get_releases_post_type();
	}

	if ( $values_only ) {
		return array_values( $post_types );
	}

	return $post_types;
}

/**
 * Get terminal recirc post types.
 *
 * @param boolean $values_only Return values only.
 * @return array post types
 */
function terminal_get_recirc_post_types( $values_only = true ) {
	$post_types = array(
		'post' => 'post',
	);

	if (
		getenv( 'TERMINAL_ENABLE_LINK_POST_TYPE' ) &&
		getenv( 'TERMINAL_ENABLE_LINK_POST_TYPE_AS_RECIRC' )
	) {
		$links              = Terminal\Links::instance();
		$post_types['link'] = $links->get_link_post_type();
	}

	if (
		getenv( 'TERMINAL_ENABLE_BOOK_POST_TYPE' ) &&
		getenv( 'TERMINAL_ENABLE_BOOK_POST_TYPE_AS_RECIRC' )
	) {
		$books              = Terminal\Books::instance();
		$post_types['book'] = $books->get_book_post_type();
	}

	if (
		getenv( 'TERMINAL_ENABLE_COMMUNITY_POST_TYPE' ) &&
		getenv( 'TERMINAL_ENABLE_COMMUNITY_POST_TYPE_AS_RECIRC' )
	) {
		$community               = Terminal\Community::instance();
		$post_types['community'] = $community->get_community_post_type();
	}

	if (
		getenv( 'TERMINAL_ENABLE_HOUSING_POST_TYPE' ) &&
		getenv( 'TERMINAL_ENABLE_HOUSING_POST_TYPE_AS_RECIRC' )
	) {
		$housing               = Terminal\Housing::instance();
		$post_types['housing'] = $housing->get_housing_post_type();
	}

	if (
		getenv( 'TERMINAL_ENABLE_DEALS_POST_TYPE' ) &&
		getenv( 'TERMINAL_ENABLE_DEALS_POST_TYPE_AS_RECIRC' )
	) {
		$deal               = Terminal\Deals::instance();
		$post_types['deal'] = $deal->get_deal_post_type();
	}

	if (
		getenv( 'TERMINAL_ENABLE_OBITUARY_POST_TYPE' ) &&
		getenv( 'TERMINAL_ENABLE_OBITUARY_POST_TYPE_AS_RECIRC' )
	) {
		$obit                   = Terminal\Obituaries::instance();
		$post_types['obituary'] = $obit->get_obituary_post_type();
	}

	if (
		getenv( 'TERMINAL_ENABLE_RELEASES_POST_TYPE' ) &&
		getenv( 'TERMINAL_ENABLE_RELEASES_POST_TYPE_AS_RECIRC' )
	) {
		$release                  = Terminal\Releases::instance();
		$post_types['release'] = $release->get_releases_post_type();
	}

	if ( $values_only ) {
		return array_values( $post_types );
	}

	return $post_types;
}

/**
 * Get terminal post type.
 *
 * @param object $post Post.
 * @return string post type mapping
 */
function terminal_get_post_type( $post = false ) {
	if ( ! $post ) {
		$post = get_the_post();
	}
	$post_types = array(
		'post' => 'post',
	);
	if ( getenv( 'TERMINAL_ENABLE_LINK_POST_TYPE' ) ) {
		$links              = Terminal\Links::instance();
		$post_types['link'] = $links->get_link_post_type();
	}
	if ( getenv( 'TERMINAL_ENABLE_BOOK_POST_TYPE' ) ) {
		$books              = Terminal\Books::instance();
		$post_types['book'] = $books->get_book_post_type();
	}

	if ( getenv( 'TERMINAL_ENABLE_COMMUNITY_POST_TYPE' ) ) {
		$community               = Terminal\Community::instance();
		$post_types['community'] = $community->get_community_post_type();
	}
	if ( getenv( 'TERMINAL_ENABLE_HOUSING_POST_TYPE' ) ) {
		$housing               = Terminal\Housing::instance();
		$post_types['housing'] = $housing->get_housing_post_type();
	}
	if ( getenv( 'TERMINAL_ENABLE_DEALS_POST_TYPE' ) ) {
		$deal               = Terminal\Deals::instance();
		$post_types['deal'] = $deal->get_deal_post_type();
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
 * Get a rendered template part
 *
 * @param string $template Template path.
 * @param array  $vars Template variables.
 * @return string
 */
function terminal_get_template_part( $template, $vars = array() ) {
	$full_path = dirname( __DIR__ ) . '/partials/' . sanitize_file_name( $template ) . '.php';
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
 * @param string $template Template path.
 * @param array  $vars Template variables.
 */
function terminal_print_template_part( $template, $vars = array() ) {
	// phpcs:ignore
	echo terminal_get_template_part( $template, $vars );
}

/**
 * Get sponsor data.
 *
 * @param string $key Key.
 * @return array data
 */
function terminal_get_sponsor_data( $key ) {
	$data = Terminal\Data::instance();
	return $data->get_sponsor_data( $key );
}

/**
 * Template function to print sponsors header..
 */
function terminal_print_sponsors_header( $title = '', $heading_tag = 'h2' ) {
	if ( empty( $title ) ) {
		$title = __( 'Sponsors', 'terminal' );
	}
	printf(
		'<%s>%s</%s>',
		esc_html( $heading_tag ),
		esc_html( $title ),
		esc_html( $heading_tag )
	);
}
/**
 * Str_replace() from the end of a string that can also be limited e.g. replace only the last instance of '</div>' with ''
 *
 * @param string $find String to find.
 * @param string $replace String to replace.
 * @param string $subject String to consider.
 * @param int    $replacement_limit | -1 to replace all references.
 *
 * @return string
 */
function terminal_str_replace( $find, $replace, $subject, $replacement_limit = -1 ) {
	$find_pattern = str_replace( '/', '\/', $find );
	return preg_replace( '/' . $find_pattern . '/', $replace, $subject, $replacement_limit );
}

/**
 * Str_replace() from the end of a string that can also be limited e.g. replace only the last instance of '</div>' with ''
 *
 * @param string $find String to find.
 * @param string $replace String to replace.
 * @param string $subject String to consider.
 * @param int    $replacement_limit | -1 to replace all references.
 *
 * @return string
 */
function str_rreplace( $find, $replace, $subject, $replacement_limit = -1 ) {
	return strrev( str_replace( strrev( $find ), strrev( $replace ), strrev( $subject ), $replacement_limit ) );
}

/**
 * Template function to print featured image credit (if available).
 */
function terminal_print_featured_image_caption() {
	$data = Terminal\Data::instance();
	$meta = $data->get_post_featured_meta();
	if ( ! empty( $meta['credit'] ) || ! empty( $meta['caption'] ) ) {
		$caption = ! empty( $meta['caption'] ) ? $meta['caption'] : '';
		$caption = sprintf(
			'<div class="terminal-featured-caption">%s</div>',
			wp_kses_post( $caption )
		);
		if ( ! empty( $meta['credit'] ) ) {
			$caption = str_rreplace(
				'</p>',
				sprintf(
					' <span class="terminal-featured-credit">%s</span></p>',
					esc_html( $meta['credit'] )
				),
				$caption,
				1
			);
		}
		// phpcs:ignore
		echo $caption;
	}
}

/**
 * Print signup message
 */
function terminal_newsletter_signup_message() {
	$data = Terminal\Ad_Data::instance();
	echo esc_html( $data->get_email_signup_text() );
}

/**
 * Print signup header
 */
function terminal_newsletter_signup_header() {
	$data = Terminal\Ad_Data::instance();
	echo esc_html( $data->get_email_signup_header() );
}

/**
 * Print unified JSON for data layers.
 *
 * @param boolean $echo Whether to echo JSON or just return.
 * @return mixed void or an encoded JSON string.
 */
function terminal_print_data_layer_json( $echo = true ) {
	$data       = Terminal\Data::instance();
	$data_layer = wp_json_encode( array(
		'single'   => $data->get_single_data_layer(),
		'isSearch' => is_search(),
	) );
	if ( ! $echo ) {
		return $data_layer;
	}
	// phpcs:ignore
	echo $data_layer;
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


function retrieve_social_data( $post_id ) {
	$parsely = Terminal\Parsely::instance();
	return $parsely->store_social_data( $post_id );
}

function retrieve_analytics_data( $post_id ) {
	$parsely = Terminal\Parsely::instance();
	return $parsely->store_analytics_data( $post_id );
}

function retrieve_referral_data( $post_id ) {
	$parsely = Terminal\Parsely::instance();
	return $parsely->store_referral_data( $post_id );
}

function retrieve_data( $post_id ) {
	$parsely = Terminal\Parsely::instance();
	return array(
		'referral' => $parsely->store_referral_data( $post_id ),
		'analytics' => $parsely->store_analytics_data( $post_id ),
		'social' => $parsely->store_social_data( $post_id )
	);
}

/**
 * Run a routine to update analytics data.
 */
function terminal_check_cached_analytics_values() {
	$parsely = Terminal\Parsely::instance();
	$today   = getdate();
	// phpcs:ignore
	$posts = get_posts( [
		'post_type'      => \terminal_get_post_types(),
		// phpcs:ignore
		'posts_per_page' => -1, // getting all posts of a post type.
		'no_found_rows'  => true, // speeds up a query significantly and can be set to 'true' if we don't use pagination.
		'fields'         => 'ids', // again, for performance.
		'date_query'     => array(
			array(
				'after' => '90 days ago',
			),
		),
	] );
	foreach ( $posts as $post_id ) {
		$parsely->possibly_schedule_event(
			'retrieve_all_data',
			$post_id
		);
	}
}

/**
 * Run retrieval methods for a post.
 *
 * @param int $post_id Post ID.
 */
function retrieve_all_data( $post_id ) {
	retrieve_referral_data( $post_id );
	retrieve_analytics_data( $post_id );
	retrieve_social_data( $post_id );
}

/**
 * Generate a Broadstreet ad unit.
 *
 * @param int $height Ad height.
 * @param int $width Ad width.
 * @param int $unit Ad unit.
 * @return string Ad unit markup.
 */
function terminal_broadstreet_ad( $height, $width, $regular_unit, $amp_unit, $header = false, $amp_disable = false, $keywords = '' ) {
	if ( ! is_amp_endpoint() ) {
		if ( ! empty( $header ) ) {
			return sprintf(
				'<div class="terminal-inline-ad"><div class="terminal-ad-header">%s</div><broadstreet-zone zone-id="%s" keywords="%s"></broadstreet-zone></div>',
				esc_html( $header ),
				esc_attr( $regular_unit ),
				esc_attr( $keywords )
			);
		}
		return sprintf(
			'<broadstreet-zone zone-id="%s" keywords="%s"></broadstreet-zone>',
			esc_attr( $regular_unit ),
			esc_attr( $keywords )
		);
	}
	if ( $amp_disable ) {
		return;
	}
	if ( ! empty( $header ) ) {
		return sprintf(
			'<div class="terminal-inline-ad"><div class="terminal-ad-header">%s</div><amp-ad width="%s" height="%s" type="broadstreetads" data-network="5918" data-zone="%s" data-keywords="%s"></amp-ad></div>',
			esc_html( $header ),
			esc_attr( $width ),
			esc_attr( $height ),
			esc_attr( $amp_unit ),
			esc_attr( $keywords )
		);
	}
	return sprintf(
		'<amp-ad width="%s" height="%s" type="broadstreetads" data-network="5918" data-zone="%s" data-keywords="%s"></amp-ad>',
		esc_attr( $width ),
		esc_attr( $height ),
		esc_attr( $amp_unit ),
		esc_attr( $keywords )
	);
}