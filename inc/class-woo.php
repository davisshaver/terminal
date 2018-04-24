<?php
/**
 * Woo integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for Woo integration.
 */
class Woo {

	use Singleton;

  private $restriction_messages;
	/**
	 * Setup actions.
	 */
	public function setup() {
    $this->restriction_messages = array(
			'post_content_restricted_message',
			'post_content_restricted_message_no_products',
			'page_content_restricted_message',
			'page_content_restricted_message_no_products',
			'content_restricted_message',
			'content_restricted_message_no_products',
			'product_viewing_restricted_message',
			'product_viewing_restricted_message_no_products',
			'product_purchasing_restricted_message',
			'product_purchasing_restricted_message_no_products',
			'product_category_viewing_restricted_message',
			'product_category_viewing_restricted_message_no_products',
		);
    add_action( 'wp', [ $this, 'add_woo_social_login' ] );
  }
  
  public function add_woo_social_login() {
    if ( function_exists( 'woocommerce_social_login_buttons' ) && function_exists( 'wc_memberships' ) ) {
      foreach ( $this->restriction_messages as $code ) {
        add_filter( "wc_memberships_{$code}",[ $this, 'add_social_login_buttons' ], 10, 2 );
      }
    }
  }

  /**
   * Renders Social Login buttons in restriction message HTML
   *
   * @param string $message the message content
   * @param string[] $args {
   *	@type string $context whether the message appears in the context of a notice or elsewhere
  *	@type \WP_Post $post the post object
  *	@type int $post_id the ID of the restricted post
  *	@type int $access_time the user access timestamp
  *	@type int[] $products the product IDs that grant access
  *	@type string $rule_type the related rule type
  *	@type string|string[] $classes optional message CSS classes
  * }
  * @return string updated message html
  */
  public function add_social_login_buttons( $message, $args ) {
    add_filter( 'pre_option_wc_social_login_text_non_checkout', '__return_empty_string' );
    // you could limit login buttons to certain posts or post types using the post ID
    // e.g., if ( 'projects' === get_post_type( $args['post_id'] ) ) { // only output buttons for project restriction messages }
    // or by choosing which filters you hook this function into
    ob_start();
    woocommerce_social_login_buttons();
    return $message . ob_get_clean();
  }
  
}


add_action( 'after_setup_theme', [ '\Terminal\Woo', 'instance' ] );
