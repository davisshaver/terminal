<form name="submit" class="terminal-contact hide-inputs"
	method="post"
	action-xhr="<?php echo admin_url( 'admin-ajax.php' ); ?>"
	target="_top">
		<div class="terminal-contact-inputs">
			<label for="email"><?php _e( 'Email (Optional)', 'terminal' ); ?></label>
			<input id="email" type="email"
			name="email"
			placeholder="email@domain.com">
			<label for="email"><?php _e( 'Name (Optional)', 'terminal' ); ?></label>
			<input type="textfield"
			name="name"
			placeholder="Your Name">
			<textarea
			name="message"
			rows="5"
			required />
			<input name="action" type="hidden" value="contact">
			<input name="security" type="hidden" value="<?php echo wp_create_nonce( 'contact-nonce' ); ?>">
			<input type="submit" value="Submit">
		</div>
		<div submit-success>
			<template type="amp-mustache">{{data}}</template>
		</div>
</form>