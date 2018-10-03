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
 * @param string $template Template path.
 * @param array  $vars Template variables.
 */
function terminal_print_template_part( $template, $vars = array() ) {
	// phpcs:ignore
	echo terminal_get_template_part( $template, $vars );
}
