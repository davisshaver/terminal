<?php
/**
 * Parse.ly integration
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for Parsely integration.
 */
class Parsely {

	use Singleton;
  private $api_key;

  private $api_secret;

	/**
	 * Setup actions.
	 */
	public function setup() {
    $this->api_key = getenv( 'TERMINAL_PARSELY_API_KEY' );
    $this->api_secret = getenv( 'TERMINAL_PARSELY_API_SECRET' );
  }

  private function get_analytics_for_post( $post_id ) {
    if ( empty( $this->api_key ) || empty( $this->api_secret ) ) {
			return;
    }
    $json = wp_cache_get( 'terminal-analytics-post-' . $post_id );
		if ( false === $json ) {
			$time_since_publish = current_time( 'timestamp' ) - get_the_time( 'U' );
			// If published more than a week ago, cache longer.
			if ( $time_since_publish > 6048000) {
				$cache_length = 864000;
			} elseif ( $time_since_publish > 604800) {
				$cache_length = 86400;
			} else {
				$cache_length = 8640;
      }
      $url = get_the_permalink( $post_id );
      $rest_target = sprintf(
        'https://api.parsely.com/v2/analytics/post/detail?apikey=%s&secret=%s&url=%s',
        $this->api_key,
        $this->api_secret,
        $url
      );
      $result = wp_remote_get( $rest_target );
      if ( empty( $result ) ) {
        return;
      }
      $json = json_decode( wp_remote_retrieve_body( $result ) );
      if (
        ! empty( $json->data ) &&
        ! empty( $json->data[0]->metrics ) &&
        ! empty( $json->data[0]->metrics )
      ) {
        foreach( $json->data[0]->metrics as $metric => $value ) {
          update_post_meta( $post_id, 'terminal_parsely_analytics_' . $metric, $value );
        }
        wp_cache_set( 'terminal-analytics-post-' . $post_id, $json, '', $cache_length );
      }
    }
    return $json;
  }

  private function parsely_metric_to_name( $metric ) {
    switch( $metric ) {
      case 'tw':
        return __( 'Twitter', 'terminal' );
      case 'fb':
        return __( 'Facebook', 'terminal' );
      case 'go':
        return __( 'Google', 'terminal' );
      case 'li':
        return __( 'LinkedIn', 'terminal' );
      case 'pi':
        return __( 'Pinterest', 'terminal' );
      default:
        return null;
    }
  }
  public function print_social_for_post( $post_id ) {
    if ( empty( $post_id ) ) {
      $post_id = get_the_ID();
    }
    if ( empty( $post_id ) ) {
      return;
    }
    $json = $this->get_social_for_post( $post_id );
    if (
      ! empty( $json->data ) &&
      ! empty( $json->data[0] )
    ) {
      echo '<ul>';
      foreach( $json->data[0] as $metric => $value ) {
        if ( 'total' === $metric ) {
          break;
        }
        $name = $this->parsely_metric_to_name( $metric );
        printf(
          '<li><strong>%s %s: </strong> %s</li>',
          esc_html( __( 'shares', 'terminal' ) ),
          esc_html( $name ),
          esc_html( $value )
        );
      }
      echo '</ul>';
    }
  }
  public function print_analytics_for_post( $post_id ) {
    if ( empty( $post_id ) ) {
      $post_id = get_the_ID();
    }
    if ( empty( $post_id ) ) {
      return;
    }
    $json = $this->get_analytics_for_post( $post_id );
    if (
      ! empty( $json->data ) &&
      ! empty( $json->data[0]->metrics ) &&
      ! empty( $json->data[0]->metrics )
    ){
      $metrics = $json->data[0]->metrics;
      echo '<ul>';
      foreach( $metrics as $metric => $value ) {
        printf(
          '<li><strong>%s: </strong> %s</li>',
          esc_html( ucfirst( $metric ) ),
          esc_html( $value )
        );
      }
      echo '</ul>';
    }
  }

  private function get_social_for_post( $post_id ) {
		if ( empty( $this->api_key ) || empty( $this->api_secret ) ) {
			return;
    }
    $url = get_the_permalink( $post_id );
    $json = wp_cache_get( 'terminal-social-' . $post_id );
		if ( false === $json ) {
			$time_since_publish = current_time( 'timestamp' ) - get_the_time( 'U' );
			// If published more than a week ago, cache longer.
			if ( $time_since_publish > 6048000) {
				$cache_length = 864000;
			} elseif ( $time_since_publish > 604800) {
				$cache_length = 86400;
			} else {
				$cache_length = 8640;
      }
      $rest_target = sprintf(
        'https://api.parsely.com/v2/shares/post/detail?apikey=%s&secret=%s&url=%s',
        $this->api_key,
        $this->api_secret,
        $url
      );
      $result = wp_remote_get( $rest_target );
      if ( empty( $result ) ) {
        return;
      }
      $json = json_decode( wp_remote_retrieve_body( $result ) );
      wp_cache_set( 'terminal-social-' . $post_id, $json, '', $cache_length );
      if ( ! empty( $json->data[0]->fb ) ) {
        update_post_meta( $post_id, 'terminal_parsely_facebook_shares', $json->data[0]->fb );
      }
      if ( ! empty( $json->data[0]->tw ) ) {
        update_post_meta( $post_id, 'terminal_parsely_twitter_shares', $json->data[0]->tw );
      }
      if ( ! empty( $json->data[0]->pi ) ) {
        update_post_meta( $post_id, 'terminal_parsely_pinterest_shares', $json->data[0]->pi );
      }
      if ( ! empty( $json->data[0]->li ) ) {
        update_post_meta( $post_id, 'terminal_parsely_linked_in_shares', $json->data[0]->li );
      }
    }
    return $json;
  }
  /**
   * Filter ESSB4 share counts.
   *
   * @param array $current Current counts.
   * @param int   $post_id Post ID (optional).
   * @return array Filtered counts.
   */
  public function filter_essb4_counters( $current, $post_id = null) {
		if ( empty( $post_id ) ) {
      $post_id = get_the_ID();
			return;
    }
    if ( empty( $post_id ) ) {
			return;
		}
    $json = $this->get_social_for_post( $post_id );
		$updated = $current;
		$updated['facebook'] = ! empty( $json->data[0]->fb ) ? $json->data[0]->fb : $current['facebook'];
		$updated['twitter'] = ! empty( $json->data[0]->tw ) ? $json->data[0]->tw : $current['twitter'];
		$updated['linkedin'] = ! empty( $json->data[0]->li ) ? $json->data[0]->li : $current['linkedin'];
		$updated['pinterest'] = ! empty( $json->data[0]->pi ) ? $json->data[0]->pi : $current['pinterest'];
		$updated['total'] = ! empty( $json->data[0]->total ) ?
			$json->data[0]->total + $current['mail'] :
			$current['total'];
		return $updated;
  }
}

add_action( 'after_setup_theme', [ '\Terminal\Parsely', 'instance' ] );
