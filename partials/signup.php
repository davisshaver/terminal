<form name="submit" class="terminal-signup hide-inputs"
	method="post"
	action-xhr="<?php echo admin_url( 'admin-ajax.php' ); ?>"
	target="_top">
		<h4><?php terminal_newsletter_signup_header(); ?></h4>
		<p><?php terminal_newsletter_signup_message(); ?></p>
		<div class="terminal-signup-inputs">
			<input type="email"
			name="email"
			placeholder="email@domain.com"
			required>
			<input name="action" type="hidden" value="email_signup">
			<input name="security" type="hidden" value="<?php echo wp_create_nonce( 'email-signup-nonce' ); ?>">
			<input type="submit" value="Subscribe!">
		</div>
		<div submit-success>
			<template type="amp-mustache">{{data}}</template>
		</div>
</form>