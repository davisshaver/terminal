<?php
$api_key = getenv( 'TERMINAL_PARSELY_API_KEY' );
$api_secret = getenv( 'TERMINAL_PARSELY_API_SECRET' );
if ( empty( $api_key ) || empty( $api_secret ) ) {
	return;
}
$dehydrated_targets = array(
	array(
		'cache' => 'terminal-parsely-popular-last-day',
		'period_start' => '24h',
		'key' => 'past-day',
		'label' => __( 'Past Day', 'terminal' ),
	),
	array(
		'cache' => 'terminal-parsely-popular-last-two-days',
		'period_start' => '48h',
		'key' => 'past-two-days',
		'label' => __( 'Past 48 Hours', 'terminal' ),
		'initial' => true,
	),
	array(
		'cache' => 'terminal-parsely-popular-last-week',
		'period_start' => '7d',
		'key' => 'past-week',
		'label' => __( 'Past Week', 'terminal' ),
	),
	array(
		'cache' => 'terminal-parsely-popular-last-month',
		'period_start' => '4w',
		'key' => 'past-month',
		'label' => __( 'Past Month', 'terminal' ),
	),
);
$hydrated_targets = array();
foreach ( $dehydrated_targets as $target ) {
	$cached = wp_cache_get( $target['cache'] );
	if ( $cached ) {
		$json = json_decode( wp_remote_retrieve_body( $cached ) );
		if ( ! empty ( $json->data ) ) {
			$new_target = $target;
			$new_target['data'] = $json->data;
			$hydrated_targets[] = $new_target;
		}
	}
	$period = $target['period_start'];
	$result = wp_remote_get(
		"https://api.parsely.com/v2/analytics/posts?apikey=${api_key}&secret=${api_secret}&period_start=${period}&limit=4"
	);
	if ( false !== $result ) {
		wp_cache_set( $target['cache'], $result, '', 3600 );
		$json = json_decode( wp_remote_retrieve_body( $result ) );
		if ( ! empty ( $json->data ) ) {
			$new_target = $target;
			$new_target['data'] = $json->data;
			$hydrated_targets[] = $new_target;
		}
	}
}
if ( empty( $hydrated_targets ) ) {
	return;
}
?>
<div class="terminal-popular-container">
	<h4><?php esc_html_e( 'Read our most popular posts', 'terminal' ); ?></h4>
	<select
		class="terminal-popular-select-filter"
		name="terminal-popular-filter"
		on="change:AMP.setState({terminalPopularFilter: event.value})">
		<?php
		foreach ( $hydrated_targets as $hydrated_target ) {
			printf(
				'<option %s value="%s">%s</option>',
				! empty( $hydrated_target['initial'] ) ? 'selected' : '',
				esc_attr( $hydrated_target['key'] ),
				esc_html( $hydrated_target['label'] )
			);
		}
		?>
	</select>
	<?php
	foreach ( $hydrated_targets as $hydrated_target ) {
		printf(
			'<div [class]="terminalPopularFilter == \'%s\' ? \'terminal-popular-list selected\' : \'terminal-popular-list\'" class="%s">',
			esc_attr( $hydrated_target['key'] ),
			esc_attr( implode( ' ', array(
				'terminal-popular-list',
				! empty( $hydrated_target['initial'] ) ? 'selected' : '',
			) ) )
		);
		$rank = 1;
		foreach( $hydrated_target['data'] as $index => $popular_post ) {
			terminal_print_template_part(
				'popular-list-item',
				array(
					'url' => $popular_post->url,
					'image_url' => $popular_post->image_url,
					'authors' => $popular_post->authors,
					'views' => $popular_post->metrics->views,
					'title' => $popular_post->title,
					'rank' => $rank,
				)
			);
			$rank++;
		}
		echo '</div>';
	}
?>
</div>
