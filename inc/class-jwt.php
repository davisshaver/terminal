<?php
/**
 * JWT integrations.
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

	/**
	 * JWT key.
	 *
	 * @var string $key JWT Key.
	 */
	private $key;

	/**
	 * JWT token.
	 *
	 * @var string $token JWT token.
	 */
	private $token;

	/**
	 * Encoded.
	 *
	 * @var string $encoded JWT encoded.
	 */
	public $encoded;

	/**
	 * Setup actions.
	 */
	public function setup() {
		if ( ! is_user_logged_in() ) {
			return;
		}
		add_filter(
			'coral_auth_token',
			function() {
				return $this->get_jwt();
			}
		);
	}

	/**
	 * Get JWT token.
	 *
	 * @return string JWT token.
	 */
	private function get_jwt() {
		$this->secret   = getenv( 'TERMINAL_TALK_JWT_SECRET' );
		$this->audience = getenv( 'TERMINAL_TALK_JWT_AUD' );
		$this->prefix   = getenv( 'TERMINAL_TALK_JWT_USER_PREFIX' );
		$this->iss      = getenv( 'TERMINAL_TALK_JWT_ISS' );
		if ( ! empty( $this->secret ) && ! empty( $this->iss ) && ! empty( $this->audience ) ) {
			$current_user  = wp_get_current_user();
			$site          = get_bloginfo();
			$this->token   = array(
				'jti'   => uniqid(),
				'iss'   => $this->iss,
				'email' => $current_user->user_email,
				'aud'   => $this->audience,
				'sub'   => $this->prefix . '-' . $current_user->user_email,
				'exp'   => time() + 60 * 60,
				'un'    => $current_user->user_login,
				'id'    => $current_user->ID,
				'site'  => sanitize_title_with_dashes( $site ),
			);
			$this->encoded = JWT_Wrapper::encode(
				$this->token,
				$this->secret
			);
			return $this->encoded;
		}
	}
}


add_action( 'after_setup_theme', [ '\Terminal\JWT', 'instance' ] );
