<?php
/**
 * Pre-load the first page's query response as a JSON object
 * Skips the need for an API query on the initial load of a page
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for data loading.
 */
class Data {

	use Singleton;

	/**
	 * Ad data.
	 *
	 * @var array $ad_data Ad data. Not used in customizer so can be cached.
	 */
	private $ad_data = array();

	/**
	 * Setup actions.
	 */
	public function setup() {
	}

	/**
	 * Get layout data.
	 *
	 * @param array $default Default options.
	 * @return array Data.
	 */
	public function get_prepared_layout_data( $default = array() ) {
		$layout_options = get_option( 'terminal_layout_options', $default );
		if ( empty( $layout_options ) ) {
			return $default;
		}
		return $layout_options;
	}

	/**
	 * Get byline data.
	 *
	 * @param array $default Default options.
	 * @return array Data.
	 */
	public function get_prepared_byline_data( $default = array() ) {
		$byline_option = get_option( 'terminal_byline_options', $default );
		if ( empty( $byline_option ) ) {
			return $default;
		}
		return $byline_option;
	}

	/**
	 * Get prepared data.
	 *
	 * @param array $default Default options.
	 * @return array Prepared data.
	 */
	public function get_prepared_sidebar_data( $default = array() ) {
		return get_option( 'terminal_sidebar_options', $default );
	}

	/**
	 * Get prepared data.
	 *
	 * @param string $key Optional key.
	 * @return array Prepared data.
	 */
	public function get_ad_data( $key = false ) {
		if ( empty( $this->ad_data ) ) {
			$this->ad_data = get_option( 'terminal_ad_options', array(
				'inline_ads'  => false,
				'inline_unit' => '',
				'ios_install' => null,
			) );
		}
		if ( ! $key ) {
			return $this->ad_data;
		} elseif ( isset( $this->ad_data[ $key ] ) ) {
			return $this->ad_data[ $key ];
		}
		return null;
	}

	/**
	 * Get post categories.
	 */
	private function get_post_categories() {
		$post_categories = wp_get_post_categories( get_the_ID() );
		$cats = array();
		foreach ( $post_categories as $c ) {
			$cat = get_category( $c );
			$cats[] = array(
				'name' => $cat->name,
				'slug' => $cat->slug,
			);
		}
		return $cats;
	}

	/**
	 * Get single data layer.
	 */
	public function get_single_data_layer() {
		if ( ! is_singular() ) {
			return array();
		}

		return array(
			'author_name' => get_the_author(),
			'author_id' => get_the_author_meta( 'ID' ),
			'post_categories' => $this->get_post_categories(),
			'post_has_thumbnail' => has_post_thumbnail(),
			'post_title' => get_the_title(),
			'post_id' => get_the_ID(),
		);
	}

	/**
	 * Get local avatar.
	 *
	 * @param int $author_id Author ID.
	 * @return int|null Headshot ID or empty.
	 */
	public function get_local_avatar_headshot( $author_id ) {
		return get_user_meta( $author_id, 'simple_local_avatar', true );
	}

	/**
	 * Get headshot.
	 *
	 * @param int $author_id Author ID.
	 * @return int|null Headshot ID or empty.
	 */
	public function get_terminal_headshot( $author_id ) {
		return get_user_meta( $author_id, 'terminal_author_fields_terminal_author_image', true );
	}

	/**
	 * Are inline stream ads enabled?
	 *
	 * @return boolean Has inline ads
	 */
	public function has_inline_ads() {
		return (bool) $this->get_ad_data( 'inline_ads' );
	}

	/**
	 * What unit should we use?
	 *
	 * @return string Ad unit
	 */
	public function get_inline_ads_tag() {
		return $this->get_ad_data( 'inline_unit' );
	}

	/**
	 * Get prepared data.
	 *
	 * @param array $default Default options.
	 * @return array Prepared data.
	 */
	public function get_prepared_footer_data( $default = array() ) {
		return get_option( 'terminal_footer_options', $default );
	}

	/**
	 * Get prepared data.
	 *
	 * @param array $default Default options.
	 * @return array Prepared data.
	 */
	public function get_prepared_header_data( $default = array() ) {
		return get_option( 'terminal_header_options', $default );
	}

	/**
	 * Get Twitter count for post.
	 *
	 * @return int
	 */
	public function get_twitter_count_for_post() {
		return 0;
	}

	/**
	 * Get comment count for post.
	 *
	 * @return int
	 */
	public function get_comment_count_for_post() {
		return 0;
	}

	/**
	 * Get Facebook count for post.
	 *
	 * @return int
	 */
	public function get_facebook_count_for_post() {
		return 0;
	}

	/**
	 * Get post featured metadata.
	 */
	public function get_post_featured_meta() {
		$default = array(
			'caption' => '',
			'credit' => '',
		);
		$options = array(
			'caption' => get_post_meta( get_the_ID(), 'terminal_featured_meta_caption', true ),
			'credit'  => get_post_meta( get_the_ID(), 'terminal_featured_meta_credit', true ),
		);
		return array(
			'caption' => ! empty( $options['caption'] ) ? $options['caption'] : $default['caption'],
			'credit'  => ! empty( $options['credit'] ) ? $options['credit'] : $default['credit'],
		);
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Data', 'instance' ] );
