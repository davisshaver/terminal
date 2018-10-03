<?php

$data_layer = wp_json_encode( array(
	'debugMode'     => getenv( 'WP_DEBUG' ),
	'inlineAds'     => array(
		'disabled'    => $data->is_blocker_disabled(),
		'enabled'     => $data->has_inline_ads(),
		'unit'        => $data->get_inline_ads_tag(),
		'noAdID'      => $data->get_no_ad_id(),
		'adblockLink' => $data->get_ad_block_link(),
		'subscribed'  => $data->user_has_no_ad_id(),
	),
	'mailchimpUser' => $data->get_mailchimp_user(),
	'mailchimpList' => $data->get_mailchimp_list(),
	'single'        => $data->get_single_data_layer(),
	'isSearch'      => is_search(),
	'parsely'       => array(
		'enabled'   => (bool) getenv( 'TERMINAL_ENABLE_PARSELY_SEARCH' ),
		'apiKey'    => getenv( 'TERMINAL_PARSELY_API_KEY' ),
		'apiSecret' => getenv( 'TERMINAL_PARSELY_API_SECRET' ),
	),
) );
