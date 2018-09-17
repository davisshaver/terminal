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
	 * Apps data.
	 *
	 * @var array $apps_data Apps data. Not used in customizer so can be cached.
	 */
	private $apps_data = array();

	/**
	 * Setup actions.
	 */
	public function setup() {
		add_action( 'pre_amp_render_post', [ $this, 'author_data_in_amp' ] );
		add_filter( 'rss2_ns', function(){
			echo 'xmlns:media="http://search.yahoo.com/mrss/"';
		});
		add_action('rss2_item', function(){
			global $post;
			if ( has_post_thumbnail( $post->ID ) ) {
				$thumbnail_ID = get_post_thumbnail_id( $post->ID );
				$thumbnail = wp_get_attachment_image_src( $thumbnail_ID, 'terminal-uncut-thumbnail-large' );
				if ( is_array( $thumbnail ) ) {
					$path = parse_url( $thumbnail[0], PHP_URL_PATH );
					if ( false !== strpos( $path, '%' ) ) {
						$src = $thumbnail[0];
					} else {
						$encoded_path = array_map ( 'urlencode', explode( '/', $path ) );
						$src = str_replace( $path, implode( '/', $encoded_path), $thumbnail[0] );
					}
					echo '<media:content medium="image" url="' . esc_url( $src )
						. '" width="' . esc_attr( $thumbnail[1] ) . '" height="' . esc_attr( $thumbnail[2] ) . '" />';
				}
			}
		});
	}

	/**
	 * Add author data to amp.
	 *
	 * See https://github.com/Automattic/amp-wp/issues/981
	 */
	public function author_data_in_amp() {
		global $authordata;
		if ( ! isset ( $authordata ) ) {
				$post = get_post( get_the_ID() );
				$authordata = get_userdata( $post->post_author );
		}
	}

	/**
	 * Get layout data.
	 *
	 * @param array $default Default options.
	 * @return array Data.
	 */
	public function get_prepared_layout_data( $default = array() ) {
		$layout_options = get_option( 'terminal_layout_options', array() );
		if ( empty( $layout_options ) ) {
			return $default;
		}
		return array_merge( $default, $layout_options );
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
		$membership_options = get_option( 'terminal_membership_options', array() );
		if ( empty( $membership_options['restricted_memberships'] ) ) {
			return $restricted_membership;
		}
		foreach( $membership_options['restricted_memberships'] as $membership ) {
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
	public function get_membership_page() {
		$membership_options = $this->get_prepared_membership_data();
		if ( empty( $membership_options['membership_page'] ) ) {
			return false;
		}
		return $membership_options['membership_page'];
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
	 * Get byline data.
	 *
	 * @param array $default Default options.
	 * @return array Data.
	 */
	public function get_prepared_byline_data( $default = array() ) {
		$byline_option = get_option( 'terminal_byline_options', array() );
		if ( empty( $byline_option ) ) {
			return $default;
		}
		return array_merge( $default, $byline_option );
	}

	/**
	 * Get prepared data.
	 *
	 * @param array $default Default options.
	 * @return array Prepared data.
	 */
	public function get_prepared_sidebar_data( $default = array() ) {
		$sidebar_options = get_option( 'terminal_sidebar_options', array() );
		if ( empty( $sidebar_options ) ) {
			return $default;
		}
		return array_merge( $default, $sidebar_options );
	}

	/**
	 * Get mailchimp list.
	 *
	 * @return string mailchimp list
	 */
	public function get_mailchimp_list() {
		return $this->get_ad_data( 'mailchimp_list' );
	}

	/**
	 * Get mailchimp user.
	 *
	 * @return string mailchimp user
	 */
	public function get_mailchimp_user() {
		return $this->get_ad_data( 'mailchimp_user' );
	}

	/**
	 * Get mailchimp URL.
	 *
	 * @return string mailchimp URL
	 */
	public function get_mailchimp_url() {
		return $this->get_ad_data( 'mailchimp_url' );
	}

	/**
	 * Get Ad block link.
	 *
	 * @return string Ad block link
	 */
	public function get_ad_block_link() {
		return $this->get_ad_data( 'adblock_link' );
	}

	/**
	 * Get membership signup text.
	 *
	 * @return string membership signup text
	 */
	public function get_bypass_text() {
		$alert = $this->get_ad_data( 'bypass_text' );
		if ( ! empty( $alert ) ) {
			return $alert;
		}
		return __( 'Already recieving enough emails?', 'terminal' );
	}

	/**
	 * Get membership signup text.
	 *
	 * @return string membership signup text
	 */
	public function get_membership_signup_button_text() {
		$alert = $this->get_ad_data( 'membership_signup_button_text' );
		if ( ! empty( $alert ) ) {
			return $alert;
		}
		return __( 'Purchase a Subscription!', 'terminal' );
	}

	/**
	 * Get membership signup text.
	 *
	 * @return string membership signup text
	 */
	public function get_membership_signup_text() {
		$alert = $this->get_ad_data( 'membership_signup_text' );
		if ( ! empty( $alert ) ) {
			return $alert;
		}
		return __( 'Support quality journalism:', 'terminal' );
	}

	/**
	 * Get email signup text.
	 *
	 * @return string email signup text
	 */
	public function get_email_signup_text() {
		$alert = $this->get_ad_data( 'email_signup_text' );
		if ( ! empty( $alert ) ) {
			return $alert;
		}
		return __( 'Sign up for our e-mail newsletter:', 'terminal' );
	}

	/**
	 * Get Ad block alert.
	 *
	 * @return string Ad block alert
	 */
	public function get_ad_block_header() {
		$alert = $this->get_ad_data( 'adblock_header' );
		if ( ! empty( $alert ) ) {
			return $alert;
		}
		return __( 'Your ad blocker is on.', 'terminal' );
	}

	/**
	 * Get Ad block alert.
	 *
	 * @return string Ad block alert
	 */
	public function get_ad_block_text() {
		$alert = $this->get_ad_data( 'adblock_alert' );
		if ( ! empty( $alert ) ) {
			return $alert;
		}
		return __( 'Your ad blocker is on.', 'terminal' );
	}

	/**
	 * Get prepared data.
	 *
	 * @param string $key Optional key.
	 * @return array Prepared data.
	 */
	public function get_apps_data( $key = false ) {
		if ( empty( $this->apps_data ) ) {
			$this->apps_data = get_option( 'terminal_app_options', array(
				'smart_banner_enable_ios' => false,
				'smart_banner_enable_google' => false,
			) );
		}
		if ( ! $key ) {
			return $this->apps_data;
		} elseif ( isset( $this->apps_data[ $key ] ) ) {
			return $this->apps_data[ $key ];
		}
		return null;
	}

	/**
	 * Get prepared data.
	 *
	 * @param string $key Optional key.
	 * @return array Prepared data.
	 */
	public function get_ad_data( $key = false ) {
		if ( $key && empty( $this->ad_data[ $key ] ) && false !== $this->ad_data[ $key ] ) {
			$this->ad_data[ $key ] = get_option( 'terminal_ad_option_' . $key );
		}
		if ( ! empty( $this->ad_data[ $key ] ) ) {
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
		if ( ! is_singular( terminal_get_post_types() ) ) {
			return array();
		}
		return array(
			'author_name' => get_the_author(),
			'author_id' => get_the_author_meta( 'ID' ),
			'post_categories' => $this->get_post_categories(),
			'post_has_thumbnail' => has_post_thumbnail(),
			'post_title' => the_title_attribute( array( 'echo' => false ) ),
			'post_id' => get_the_ID(),
			'post_type' => terminal_get_post_type( get_post() ),
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
	public function is_blocker_disabled() {
		$value = $this->get_ad_data( 'disable_blocker' );
		if ( empty( $value ) ) {
			return false;
		}
		return true;
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
	 * What AMP header unit should we use?
	 *
	 * @return string Ad tag
	 */
	public function get_amp_header_tag() {
		return $this->get_ad_data( 'amp_header' );
	}

	/**
	 * What AMP post unit should we use?
	 *
	 * @return string Ad tag
	 */
	public function get_amp_post_tag() {
		return $this->get_ad_data( 'amp_post' );
	}

	/**
	 * What AMP footer unit should we use?
	 *
	 * @return string Ad tag
	 */
	public function get_amp_footer_tag() {
		return $this->get_ad_data( 'amp_footer' );
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
			'caption' => '',
			'credit' => '',
			'add_featured_embed' => false,
			'use_featured_embed_on_landing' => false,
			'use_featured_embed_on_single' => false,
			'featured_embed' => '',
			'hide_featured_image' => false,
		);
		$options = array(
			'caption' => get_post_meta( get_the_ID(), 'terminal_featured_meta_caption', true ),
			'credit' => get_post_meta( get_the_ID(), 'terminal_featured_meta_credit', true ),
			'add_featured_embed' => get_post_meta( get_the_ID(), 'terminal_featured_meta_add_featured_embed', true ),
			'use_featured_embed_on_landing' => get_post_meta( get_the_ID(), 'terminal_featured_meta_use_featured_embed_on_landing', true ),
			'use_featured_embed_on_single' => get_post_meta( get_the_ID(), 'terminal_featured_meta_use_featured_embed_on_single', true ),
			'featured_embed'  => get_post_meta( get_the_ID(), 'terminal_featured_meta_featured_embed', true ),
			'hide_featured_image'  => get_post_meta( get_the_ID(), 'terminal_featured_meta_hide_featured_image', true ),
		);
		return array(
			'add_featured_embed' => ! empty( $options['add_featured_embed'] ) ? $options['add_featured_embed'] : $default['add_featured_embed'],
			'use_featured_embed_on_landing' => ! empty( $options['use_featured_embed_on_landing'] ) ? $options['use_featured_embed_on_landing'] : $default['use_featured_embed_on_single'],
			'use_featured_embed_on_single' => ! empty( $options['use_featured_embed_on_single'] ) ? $options['use_featured_embed_on_single'] : $default['use_featured_embed_on_single'],
			'featured_embed' => ! empty( $options['featured_embed'] ) ? $options['featured_embed'] : $default['featured_embed'],
			'caption' => ! empty( $options['caption'] ) ? $options['caption'] : $default['caption'],
			'credit' => ! empty( $options['credit'] ) ? $options['credit'] : $default['credit'],
			'hide_featured_image' => ! empty( $options['hide_featured_image'] ) ? $options['hide_featured_image'] : $default['hide_featured_image'],
		);
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Data', 'instance' ] );
