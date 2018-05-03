<?php
/**
 * JvWT integrations.
 *
 * @package Terminal
 */


namespace Terminal;

use \Firebase\JWT\JWT as JWT_Wrapper;

/**
 * Class wrapper for JWT tokens.
 */
class JWT {

	use Singleton;

  private $key;

  private $token;

  public $encoded;

	/**
	 * Setup actions.
	 */
	public function setup() {
    if ( ! is_user_logged_in() ) {
      return;
    }
    $this->secret = getenv( 'TERMINAL_TALK_JWT_SECRET' );
    if ( ! empty ( $this->secret ) ) {
      $current_user = wp_get_current_user();
      $this->token = array(
        'iss' => 'https://talk.phillypublishing.com',
        'aud' => 'talk',
        'user' => array(
          'id' => $current_user->ID,
          'name' => $current_user->display_name,
          'username' => $current_user->user_login,
          'email' => $current_user->user_email,
        )
      );
      $this->encoded = JWT_Wrapper::encode(
        $this->token,
        $this->secret
      );
      add_filter(
        'coral-auth-token',
        function() {
          return $this->encoded;
        }
      );
    }
  }
}


add_action( 'after_setup_theme', [ '\Terminal\JWT', 'instance' ] );
