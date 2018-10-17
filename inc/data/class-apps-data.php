<?php
/**
 * Apps data helper.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for data loading.
 */
class Apps_Data {

	use Singleton;

	/**
	 * Apps data.
	 *
	 * @var array $apps_data Apps data.
	 */
	private $apps_data = array();

	/**
	 * Setup actions.
	 */
	public function setup() {
		add_action( 'wp_head', [ $this, 'print_app_tags' ] );
		add_filter( 'web_app_manifest', [ $this, 'filter_web_app_manifest' ] );
	}

	/**
	 * Get app banner text.
	 */
	public function get_app_banner_text() {
		return ! empty( $this->get_apps_data( 'app_banner_text' ) ) ? $this->get_apps_data( 'app_banner_text' ) : __( 'Try our app for a native experience.', 'terminal' );
	}

	/**
	 * Get app banner view.
	 */
	public function get_app_banner_view() {
		return ! empty( $this->get_apps_data( 'app_banner_view' ) ) ? $this->get_apps_data( 'app_banner_view' ) : __( 'Download', 'terminal' );
	}

	/**
	 * Print app tags.
	 */
	public function apps_enabled() {
		return (
			! empty( $this->get_apps_data( 'apple_app_id' ) ) ||
			! empty( $this->get_apps_data( 'apple_app_medium' ) )
		);
	}

	/**
	 * Print app tags.
	 */
	public function print_app_tags() {
		var_dump( $this->apps_enabled() );
		if ( ! $this->apps_enabled() ) {
			return;
		}
		printf(
			'<meta name="apple-itunes-app" content="app-id=%s, app-argument=%s">',
			esc_attr( $this->get_apps_data( 'apple_app_id' ) ),
			esc_attr( $this->get_apps_data( 'apple_app_medium' ) )
		);
	}

	/**
	 * Filter Web App manifest
	 *
	 * @param array $manifest Manifest object.
	 * @return array Filtered manifest object.
	 */
	public function filter_web_app_manifest( $manifest ) {
		if (
			! empty( $this->get_apps_data( 'android_app_id' ) ) ||
			! empty( $this->get_apps_data( 'android_app_link' ) )
		) {
			return;
		}
		$android_id  = $this->get_apps_data( 'android_app_id' );
		$android_url = $this->get_apps_data( 'android_app_link' );
		if ( ! empty( $android_id ) ) {
			$manifest['prefer_related_applications'] = true;
			$manifest['related_applications']        = [
				[
					'platform' => 'play',
					'id'       => $android_id,
					'url'      => $android_url,
				],
			];
		}
		return $manifest;
	}

	/**
	 * Get prepared data.
	 *
	 * @param string $key Optional key.
	 * @return array Prepared data.
	 */
	public function get_apps_data( $key = false ) {
		if ( empty( $this->apps_data ) ) {
			$this->apps_data = get_option( 'terminal_app_options', array() );
		}
		if ( ! $key ) {
			return $this->apps_data;
		} elseif ( isset( $this->apps_data[ $key ] ) ) {
			return $this->apps_data[ $key ];
		}
		return null;
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Apps_Data', 'instance' ] );
