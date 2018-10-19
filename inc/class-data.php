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
		add_action( 'pre_amp_render_post', [ $this, 'author_data_in_amp' ] );
		add_filter( 'rss2_ns', function() {
			echo 'xmlns:media="http://search.yahoo.com/mrss/"';
		});
		add_action('rss2_item', function() {
			global $post;
			if ( has_post_thumbnail( $post->ID ) ) {
				$thumbnail_id = get_post_thumbnail_id( $post->ID );
				$thumbnail    = wp_get_attachment_image_src( $thumbnail_id, 'terminal-uncut-thumbnail-large' );
				if ( is_array( $thumbnail ) ) {
					$path = wp_parse_url( $thumbnail[0], PHP_URL_PATH );
					if ( false !== strpos( $path, '%' ) ) {
						$src = $thumbnail[0];
					} else {
						$encoded_path = array_map( 'urlencode', explode( '/', $path ) );
						$src          = str_replace( $path, implode( '/', $encoded_path ), $thumbnail[0] );
					}
					echo '<media:content medium="image" url="' . esc_url( $src )
						. '" width="' . esc_attr( $thumbnail[1] ) . '" height="' . esc_attr( $thumbnail[2] ) . '" />';
				}
			}
		});
	}

	/**
	 * Get post categories.
	 */
	private function get_post_categories() {
		$post_categories = wp_get_post_categories( get_the_ID() );
		$cats            = array();
		foreach ( $post_categories as $c ) {
			$cat    = get_category( $c );
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
		if ( ! is_singular( terminal_get_post_types() ) ) {
			return array();
		}
		return array(
			'author_name'        => get_the_author(),
			'author_id'          => get_the_author_meta( 'ID' ),
			'post_categories'    => $this->get_post_categories(),
			'post_has_thumbnail' => has_post_thumbnail(),
			'post_title'         => the_title_attribute( array( 'echo' => false ) ),
			'post_id'            => get_the_ID(),
			'post_type'          => terminal_get_post_type( get_post() ),
		);
	}

	/**
	 * Add author data to amp.
	 *
	 * See https://github.com/Automattic/amp-wp/issues/981
	 */
	public function author_data_in_amp() {
		global $authordata;
		if ( ! isset( $authordata ) ) {
				$post = get_post( get_the_ID() );
				// phpcs:ignore
				$authordata = get_userdata( $post->post_author );
		}
	}

	/**
	 * Get local avatar.
	 *
	 * @param int $author_id Author ID.
	 * @return int|null Headshot ID or empty.
	 */
	public function get_local_avatar_headshot( $author_id ) {
		// phpcs:ignore
		return get_user_meta( $author_id, 'simple_local_avatar', true );
	}

	/**
	 * Get headshot.
	 *
	 * @param int $author_id Author ID.
	 * @return int|null Headshot ID or empty.
	 */
	public function get_terminal_headshot( $author_id ) {
		// phpcs:ignore
		return get_user_meta( $author_id, 'terminal_author_fields_terminal_author_image', true );
	}


	/**
	 * Get prepared data.
	 *
	 * @param array $default Default options.
	 * @return array Prepared data.
	 */
	public function get_prepared_footer_data( $default = array() ) {
		$footer_options = get_option( 'terminal_footer_options', array() );
		if ( empty( $footer_options ) ) {
			return $default;
		}
		return array_merge( $default, $footer_options );
	}

	/**
	 * Get prepared data.
	 *
	 * @param array $default Default options.
	 * @return array Prepared data.
	 */
	public function get_prepared_header_data( $default = array() ) {
		$header_options = get_option( 'terminal_header_options', array() );
		if ( empty( $header_options ) ) {
			return $default;
		}
		return array_merge( $default, $header_options );
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
			'caption'                       => '',
			'credit'                        => '',
			'add_featured_embed'            => false,
			'use_featured_embed_on_landing' => false,
			'use_featured_embed_on_single'  => false,
			'featured_embed'                => '',
			'hide_featured_image'           => false,
		);
		$options = array(
			'caption'                       => get_post_meta( get_the_ID(), 'terminal_featured_meta_caption', true ),
			'credit'                        => get_post_meta( get_the_ID(), 'terminal_featured_meta_credit', true ),
			'add_featured_embed'            => get_post_meta( get_the_ID(), 'terminal_featured_meta_add_featured_embed', true ),
			'use_featured_embed_on_landing' => get_post_meta( get_the_ID(), 'terminal_featured_meta_use_featured_embed_on_landing', true ),
			'use_featured_embed_on_single'  => get_post_meta( get_the_ID(), 'terminal_featured_meta_use_featured_embed_on_single', true ),
			'featured_embed'                => get_post_meta( get_the_ID(), 'terminal_featured_meta_featured_embed', true ),
			'hide_featured_image'           => get_post_meta( get_the_ID(), 'terminal_featured_meta_hide_featured_image', true ),
		);
		return array(
			'add_featured_embed'            => ! empty( $options['add_featured_embed'] ) ? $options['add_featured_embed'] : $default['add_featured_embed'],
			'use_featured_embed_on_landing' => ! empty( $options['use_featured_embed_on_landing'] ) ? $options['use_featured_embed_on_landing'] : $default['use_featured_embed_on_single'],
			'use_featured_embed_on_single'  => ! empty( $options['use_featured_embed_on_single'] ) ? $options['use_featured_embed_on_single'] : $default['use_featured_embed_on_single'],
			'featured_embed'                => ! empty( $options['featured_embed'] ) ? $options['featured_embed'] : $default['featured_embed'],
			'caption'                       => ! empty( $options['caption'] ) ? $options['caption'] : $default['caption'],
			'credit'                        => ! empty( $options['credit'] ) ? $options['credit'] : $default['credit'],
			'hide_featured_image'           => ! empty( $options['hide_featured_image'] ) ? $options['hide_featured_image'] : $default['hide_featured_image'],
		);
	}

	/**
	 * Get a sponsor data.
	 *
	 * @param string $key Optional key.
	 * @return string Sponsor data.
	 */
	public function get_sponsor_data( $key ) {
		if ( empty( $this->sponsor_data ) ) {
			$this->sponsor_data = get_option( 'terminal_sponsor_options' );
		}
		if ( ! empty( $this->sponsor_data[ $key ] ) ) {
			return $this->sponsor_data[ $key ];
		}
		return null;
	}

	/**
	 * Does user have membership id.
	 *
	 * @return boolean Whether user has Membership ID.
	 */
	 public function user_has_membership_id() {
		$membership_id = $this->get_membership_id();
		if ( current_user_can( sprintf(
			'memberpress_product_authorized_%s',
			$membership_id
		) ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Get no AD id.
	 *
	 * @return int No AD id.
	 */
	public function user_has_no_ad_id() {
		$no_ad_id = $this->get_no_ad_id();
		if ( ! $no_ad_id ) {
			return current_user_can( 'edit_posts' );
		}
		if ( current_user_can( sprintf(
			'memberpress_product_authorized_%s',
			$no_ad_id
		) ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Get membership ID.
	 *
	 * @return int Membership ID for general subscription.
	 */
	 public function get_membership_id() {
		$membership_options = get_option( 'terminal_membership_options', array() );
		if ( ! empty( $membership_options['member_subscription'] ) ) {
			return $membership_options['member_subscription'];
		}
		return false;
	}

	/**
	 * Get ad block membership ID.
	 *
	 * @return int Membership ID for ad block subscription.
	 */
	public function get_no_ad_id() {
		$membership_options = get_option( 'terminal_membership_options', array() );
		if ( ! empty( $membership_options['ad_free_subscription'] ) ) {
			return $membership_options['ad_free_subscription'];
		}
		return false;
	}

	/**
	 * Get membership data.
	 *
	 * @param array $default Default options.
	 * @return array Data.
	 */
	public function get_restricted_domains_by_membership() {
		$restricted_membership = array();
		$membership_options    = get_option( 'terminal_membership_options', array() );
		if ( empty( $membership_options['restricted_memberships'] ) ) {
			return $restricted_membership;
		}
		foreach ( $membership_options['restricted_memberships'] as $membership ) {
			$restricted_membership[ $membership['membership_id'] ] = explode( ',', $membership['domains'] );
		}
		return $restricted_membership;
	}

	/**
	 * Get membership data.
	 *
	 * @param array $default Default options.
	 * @return array Data.
	 */
	public function get_prepared_membership_data( $default = array() ) {
		$membership_options = get_option( 'terminal_membership_options', array() );
		if ( empty( $membership_options ) ) {
			return $default;
		}
		return array_merge( $default, $membership_options );
	}

	/**
	 * Get membership text.
	 *
	 * @return array Data.
	 */
	public function get_membership_text() {
		$membership_options = $this->get_prepared_membership_data();
		if ( empty( $membership_options['membership_text'] ) ) {
			return false;
		}
		return $membership_options['membership_text'];
	}

	/**
	 * Get membership data.
	 *
	 * @return array Data.
	 */
	public function get_membership_page_always() {
		$membership_options = $this->get_prepared_membership_data();
		if ( empty( $membership_options['membership_page'] ) ) {
			return false;
		}
		return $membership_options['membership_page'];
	}

	/**
	 * Membership page for folks who haven't explicitly opted out.
	 *
	 * @return array Data.
	 */
	public function get_membership_page() {
		$membership_options = $this->get_prepared_membership_data();
		if ( empty( $membership_options['membership_page'] ) ) {
			return false;
		}
		$data    = Data::instance();
		$disable = $data->user_has_membership_id();
		if ( $disable ) {
			return false;
		}
		return $membership_options['membership_page'];
	}

}

add_action( 'after_setup_theme', [ '\Terminal\Data', 'instance' ] );
