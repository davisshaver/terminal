<?php
/**
 * Warnings.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for Warnings.
 */
class Warnings {
	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {
		add_action( 'after_switch_theme', [ $this, 'terminal_switch_theme' ] );
		add_action( 'load-customize.php', [ $this, 'terminal_customize' ] );
		add_action( 'template_redirect', [ $this, 'terminal_preview' ] );
	}

	/**
	 * Switch back to default theme if not supported.
	 */
	public function terminal_switch_theme() {
		switch_theme( WP_DEFAULT_THEME );
		unset( $_GET['activated'] );
		add_action( 'admin_notices', [ $this, 'terminal_upgrade_notice' ] );
	}

	/**
	 * Print Terminal upgrade notice.
	 */
	public function terminal_upgrade_notice() {
		$message = __( 'Terminal requires WordPress 4.9 or higher. Please update your site and try again.', 'terminal' );
		printf( '<div class="error"><p>%s</p></div>', $message ); /* WPCS: xss ok. */
	}

	/**
	 * Print Terminal upgrade notice (customize).
	 */
	public function terminal_customize() {
		wp_die( esc_html__( 'Terminal requires WordPress 4.9 or higher. Please update your site and try again.', 'terminal' ), '', array(
			'back_link' => true,
		) );
	}

	/**
	 * Print Terminal upgrade notice (preview).
	 */
	public function terminal_preview() {
		if ( isset( $_GET['preview'] ) ) {
			wp_die( esc_html__( 'Terminal requires WordPress 4.9 or higher. Please update your site and try again.', 'terminal' ) );
		}
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Warnings', 'instance' ] );
