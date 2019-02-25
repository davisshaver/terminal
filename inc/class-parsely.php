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
		if ( ! empty( $this->api_key ) && ! empty( $this->api_secret ) && current_user_can( 'edit_others_posts' ) ) {
			add_filter( 'manage_post_posts_columns', [ $this, 'add_parsely_columns' ] );
			add_action( 'manage_post_posts_custom_column' , [ $this, 'handle_parsely_columns' ], 10, 2 );
			add_filter( 'manage_edit-post_sortable_columns', [ $this, 'add_parsely_columns_orderby' ] );
			add_action( 'pre_get_posts', [$this, 'parsely_custom_orderby' ] );
		}
		add_action( 'retrieve_social_data', 'retrieve_social_data', 10, 1 );
		add_action( 'check_cached_analytics_values', 'check_cached_analytics_values', 10 );
		add_action( 'retrieve_analytics_data', 'retrieve_analytics_data', 10, 1 );
		add_action( 'retrieve_referral_data', 'retrieve_referral_data', 10, 1 );
		add_action( 'retrieve_data', 'retrieve_data', 10, 1 );
		add_action( 'publish_post', [ $this, 'schedule_some_retrievals' ], 10, 2 );
		add_action( 'wp', [ $this, 'possibly_schedule_analytics_update' ] );
	}

	public function possibly_schedule_analytics_update() {
		if ( ! wp_next_scheduled( 'terminal_check_cached_analytics_values' ) ) {
			wp_schedule_single_event( current_time( 'timestamp' ) + HOUR_IN_SECONDS * 3, 'terminal_check_cached_analytics_values' );
		}
	}

	public function schedule_some_retrievals( $post_id, $post ) {
		$timestamp = get_post_time( 'U', false, $post );
		$hours = array( 1 );
		foreach( $hours as $hour ) {
			$target = $timestamp + ( $hour * HOUR_IN_SECONDS );
			$this->possibly_schedule_event(
				'retrieve_data',
				$post_id,
				$target
			);
		}
	}

	public function parsely_custom_orderby( $query ) {
		if ( in_array(
			$query->get( 'orderby' ),
			array(
				'terminal_parsely_analytics_views',
				'terminal_parsely_referrer_social',
				'terminal_parsely_referrer_search',
				'terminal_parsely_referrer_direct',
				'terminal_parsely_referrer_internal',
				'terminal_parsely_referrer_other',
				'terminal_parsely_analytics_visitors',
				'terminal_parsely_facebook_shares',
				'terminal_parsely_twitter_shares',
			)
		) ) { 
			$query->set( 'meta_key', $query->get( 'orderby' ) );
			$query->set( 'orderby','meta_value_num' );
			add_filter( 'get_meta_sql', [ $this, 'workaround_19653' ] );
		}
		return;
	}

	public function workaround_19653( $clauses ) {
		remove_filter( 'get_meta_sql', [ $this, 'workaround_19653' ] );
		$clauses['join']  = str_replace( 'INNER JOIN', 'LEFT JOIN', $clauses['join'] ) . $clauses['where'];
		$clauses['where'] = '';
		return $clauses;
	}

	public function possibly_schedule_event( $event, $post_id, $target = false ) {
		if ( is_preview() ) {
			return;
		}
		if ( ! $target ) {
			$target = time();
		}
		if (
			false === wp_next_scheduled(
				$event,
				array( $post_id )
			)
		) {
			wp_schedule_single_event(
				$target,
				$event,
				array( $post_id )
			);
		}
	}

	public function handle_parsely_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'terminal-pageviews' :
				$views = $this->get_cached_meta( $post_id, 'terminal_parsely_analytics_views', true, 'analytics' );
				if ( false !== $views && is_numeric( $views ) ) {
					echo number_format( $views );
				}
				break;
			case 'terminal-visitors' :
				$visitors = $this->get_cached_meta( $post_id, 'terminal_parsely_analytics_visitors', true, 'analytics' );
				if ( false !== $visitors && is_numeric( $visitors ) ) {
					echo number_format( $visitors );
				}
				break;
			case 'terminal-facebook-shares' :
				$shares = $this->get_cached_meta( $post_id, 'terminal_parsely_facebook_shares', true, 'shares' );
				if ( false !== $shares && is_numeric( $shares ) ) {
					echo number_format( $shares );
				}
				break;
			case 'terminal-twitter-shares' :
				$shares = $this->get_cached_meta( $post_id, 'terminal_parsely_twitter_shares', true, 'shares' );
				if ( false !== $shares && is_numeric( $shares ) ) {
					echo number_format( $shares );
				}
				break;
			case 'terminal-pageviews-social' :
				$pageviews = $this->get_cached_meta( $post_id, 'terminal_parsely_referrer_social', true, 'referrer' );
				if ( false !== $pageviews && is_numeric( $pageviews ) ) {
					echo number_format( $pageviews );
				}
				break;
			case 'terminal-pageviews-search' :
				$pageviews = $this->get_cached_meta( $post_id, 'terminal_parsely_referrer_search', true, 'referrer' );
				if ( false !== $pageviews && is_numeric( $pageviews ) ) {
					echo number_format( $pageviews );
				}
				break;
			case 'terminal-pageviews-direct' :
				$pageviews = $this->get_cached_meta( $post_id, 'terminal_parsely_referrer_direct', true, 'referrer' );
				if ( false !== $pageviews && is_numeric( $pageviews ) ) {
					echo number_format( $pageviews );
				}
				break;
			case 'terminal-pageviews-internal' :
				$pageviews = $this->get_cached_meta( $post_id, 'terminal_parsely_referrer_internal', true, 'referrer' );
				if ( false !== $pageviews && is_numeric( $pageviews ) ) {
					echo number_format( $pageviews );
				}
				break;
			case 'terminal-pageviews-other' :
				$pageviews = $this->get_cached_meta( $post_id, 'terminal_parsely_referrer_other', true, 'referrer' );
				if ( false !== $pageviews && is_numeric( $pageviews ) ) {
					echo number_format( $pageviews );
				}
				break;
			}
	}

	public function add_parsely_columns( $columns ) {
		$columns['terminal-pageviews'] = __( 'Pageviews', 'terminal' );
		$columns['terminal-pageviews-social'] = __( 'PV Social', 'terminal' );
		$columns['terminal-pageviews-search'] = __( 'PV Search', 'terminal' );
		$columns['terminal-pageviews-direct'] = __( 'PV Direct', 'terminal' );
		$columns['terminal-pageviews-internal'] = __( 'PV Internal', 'terminal' );
		$columns['terminal-pageviews-other'] = __( 'PV Other', 'terminal' );
		$columns['terminal-visitors'] = __( 'Visitors', 'terminal' );
		$columns['terminal-facebook-shares'] = __( 'Facebook Shares', 'terminal' );
		$columns['terminal-twitter-shares'] = __( 'Twitter Shares', 'terminal' );
		return $columns;
	}

	public function add_parsely_columns_orderby( $columns ) {
		$columns['terminal-pageviews'] = 'terminal_parsely_analytics_views';
		$columns['terminal-pageviews-social'] = 'terminal_parsely_referrer_social';
		$columns['terminal-pageviews-search'] = 'terminal_parsely_referrer_search';
		$columns['terminal-pageviews-direct'] = 'terminal_parsely_referrer_direct';
		$columns['terminal-pageviews-internal'] = 'terminal_parsely_referrer_internal';
		$columns['terminal-pageviews-other'] = 'terminal_parsely_referrer_other';
		$columns['terminal-visitors'] = 'terminal_parsely_analytics_visitors';
		$columns['terminal-facebook-shares'] = 'terminal_parsely_facebook_shares';
		$columns['terminal-twitter-shares'] = 'terminal_parsely_twitter_shares';
		return $columns;
	}

	private function set_cached( $key, $value, $post_id, $length ) {
		update_post_meta( $post_id, $key, json_encode( $value ) );
		update_post_meta( $post_id, $key . '_cache_length', time() + $length );
	}

	private function get_cached_meta( $post_id, $key, $single, $group ) {
		switch( $group ) {
			case 'analytics':
				$analytics = $this->is_cached( 'terminal_analytics', $post_id );
				if ( $analytics ) {
					return get_post_meta( $post_id, $key, $single );
				}
			case 'shares':
				$shares = $this->is_cached( 'terminal_social', $post_id );
				if ( $shares ) {
					return get_post_meta( $post_id, $key, $single );
				}
			case 'referrer':
				$referrer = $this->is_cached( 'terminal_referrers', $post_id );
				if ( $referrer ) {
					return get_post_meta( $post_id, $key, $single );
				}
		}
	}

	private function is_cached( $key, $post_id ) {
		$expiration = get_post_meta( $post_id, $key . '_cache_length', true );
		if ( ! empty( $value ) || ! empty( $expiration ) ) {
			return true;
		}
		return false;
	}

	private function is_json( $string, $return_data = false ) {
		$data = json_decode( $string );
		return ( json_last_error() == JSON_ERROR_NONE ) ? ( $return_data ? $data : TRUE ) : FALSE;
	}

	private function get_cached( $key, $post_id ) {
		$value = get_post_meta( $post_id, $key, true );
		$expiration = get_post_meta( $post_id, $key . '_cache_length', true );
		if ( time() > $expiration ) {
			switch ( $key ) {
				case 'terminal_analytics':
					$this->possibly_schedule_event( 'retrieve_analytics_data', $post_id );
					break;
				case 'terminal_social':
					$this->possibly_schedule_event( 'retrieve_social_data', $post_id );
					break;
				case 'terminal_referrers':
					$this->possibly_schedule_event( 'retrieve_referral_data', $post_id );
					break;
			}
		}
		if ( empty( $value ) ) {
			return false;
		}
		if ( is_string( $value ) && $this->is_json( $value ) ) {
			return json_decode( $value );
		}
		return $value;
	}

	private function get_cached_referrers_for_post( $post_id ) {
		if ( empty( $this->api_key ) || empty( $this->api_secret ) ) {
			return;
		}
		return $this->get_cached( 'terminal_referrers', $post_id );
	}

	private function get_cached_analytics_for_post( $post_id ) {
		if ( empty( $this->api_key ) || empty( $this->api_secret ) ) {
			return;
		}
		return $this->get_cached( 'terminal_analytics', $post_id );
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
		$json = $this->get_cached_social_data( $post_id );
		if (
			! empty( $json->data ) &&
			! empty( $json->data[0] )
		) {
			echo '<ul>';
			foreach( $json->data[0] as $metric => $value ) {
				if ( 'total' === $metric ) {
					continue;
				}
				$name = $this->parsely_metric_to_name( $metric );
				printf(
					'<li><strong>%s %s:</strong> %s</li>',
					esc_html( $name ),
					esc_html( __( 'shares', 'terminal' ) ),
					esc_html( number_format( $value ) )
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
		$json = $this->get_cached_analytics_for_post( $post_id );
		if (
			! empty( $json->data ) &&
			! empty( $json->data[0]->metrics ) &&
			! empty( $json->data[0]->metrics )
		){
			$metrics = $json->data[0]->metrics;
			echo '<ul>';
			foreach( $metrics as $metric => $value ) {
				printf(
					'<li><strong>%s:</strong> %s</li>',
					esc_html( ucfirst( $metric ) ),
					esc_html( number_format( $value ) )
				);
			}
			echo '</ul>';
		}
	}

	public function print_referrers_for_post( $post_id ) {
		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}
		if ( empty( $post_id ) ) {
			return;
		}
		$json = $this->get_cached_referrers_for_post( $post_id );
		if (
			! empty( $json->data )
		){
			echo '<ol>';
			foreach( $json->data as $key => $referrer ) {
				printf(
					'<li><strong>%s:</strong> %s</li>',
					esc_html( ucfirst( $referrer->type ) ),
					esc_html( number_format( $referrer->metrics->referrers_views ) )
				);
			}
			echo '</ol>';
		}
	}
	private function get_cache_length( $post_id ) {
		$time_since_publish = current_time( 'timestamp' ) - get_the_time( 'U', $post_id );
		if ( $time_since_publish > ( 3 * MONTH_IN_SECONDS ) ) {
			$cache_length = DAY_IN_SECONDS * 3;
		} elseif ( $time_since_publish > MONTH_IN_SECONDS) {
			$cache_length = DAY_IN_SECONDS * 2;
		} elseif ( $time_since_publish > WEEK_IN_SECONDS) {
			$cache_length = DAY_IN_SECONDS;
		} elseif ( $time_since_publish > DAY_IN_SECONDS )  {
			$cache_length = HOUR_IN_SECONDS;
		} else {
			$cache_length = MINUTE_IN_SECONDS * 30;
		}
		return $cache_length;
	}

	public function store_referral_data( $post_id ) {
		$cache_length = $this->get_cache_length( $post_id );
		$url = get_the_permalink( $post_id );
		$rest_target = sprintf(
			'https://api.parsely.com/v2/referrers/post/detail?apikey=%s&secret=%s&url=%s&period_start=90d',
			$this->api_key,
			$this->api_secret,
			$url
		);
		$result = false;
		try {
			$result = wp_remote_get( $rest_target, array( 'timeout' => 20 ) );
		} catch( Exception $ex ) {
			error_log( $ex->getMessage() );
		}

		if ( false === $result || is_wp_error( $result ) ) {
			return;
		}
		if ( empty( $result ) ) {
			return;
		}
		$json = json_decode( wp_remote_retrieve_body( $result ) );
		if (
			! empty( $json->data ) &&
			! empty( $json->data[0] ) &&
			! empty( $json->data[0] )
		) {
			$this->set_cached( 'terminal_referrers', $json, $post_id, $cache_length );
			foreach( $json->data as $key => $referrer ) {
				update_post_meta( $post_id, 'terminal_parsely_referrer_' . $referrer->type, $referrer->metrics->referrers_views );
			}
		}
	}

	public function store_analytics_data( $post_id ) {
		$cache_length = $this->get_cache_length( $post_id );
		$url = get_the_permalink( $post_id );
		$rest_target = sprintf(
			'https://api.parsely.com/v2/analytics/post/detail?apikey=%s&secret=%s&url=%s',
			$this->api_key,
			$this->api_secret,
			$url
		);
		$result = false;
		try {
			$result = wp_remote_get( $rest_target, array( 'timeout' => 20 ) );
		} catch( Exception $ex ) {
			error_log( $ex->getMessage() );
		}

		if ( false === $result || is_wp_error( $result ) ) {
			return;
		}

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
			$this->set_cached( 'terminal_analytics', $json, $post_id, $cache_length );
		}
	}

	public function store_social_data( $post_id ) {
		$cache_length = $this->get_cache_length( $post_id );
		$url = get_the_permalink( $post_id );
		$rest_target = sprintf(
			'https://api.parsely.com/v2/shares/post/detail?apikey=%s&secret=%s&url=%s',
			$this->api_key,
			$this->api_secret,
			$url
		);
		$result = false;
		try {
			$result = wp_remote_get( $rest_target, array( 'timeout' => 20 ) );
		} catch( Exception $ex ) {
			error_log( $ex->getMessage() );
		}

		if ( false === $result || is_wp_error( $result ) ) {
			return;
		}
		if ( empty( $result ) ) {
			return;
		}
		$json = json_decode( wp_remote_retrieve_body( $result ) );
		$this->set_cached( 'terminal_social', $json, $post_id, $cache_length );
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

	private function get_cached_social_data( $post_id ) {
		if ( empty( $this->api_key ) || empty( $this->api_secret ) ) {
			return;
		}
		$url = get_the_permalink( $post_id );
		return $this->get_cached( 'terminal_social', $post_id );
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
		$json = $this->get_cached_social_data( $post_id );
		$updated = $current;
		$updated['facebook'] = ! empty( $json->data[0]->fb ) ? $json->data[0]->fb : $current['facebook'];
		$updated['twitter'] = ! empty( $json->data[0]->tw ) ? $json->data[0]->tw : $current['twitter'];
		$updated['linkedin'] = ! empty( $json->data[0]->li ) ? $json->data[0]->li : $current['linkedin'];
		$updated['pinterest'] = ! empty( $json->data[0]->pi ) ? $json->data[0]->pi : $current['pinterest'];
		$updated['total'] = ! empty( $json->data[0]->total ) ?
			intval( $json->data[0]->total ) + intval( $current['mail'] ) :
			$current['total'];
		return $updated;
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Parsely', 'instance' ] );
