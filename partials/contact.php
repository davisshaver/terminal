<?php
if ( is_user_logged_in() ) {
	$user = get_userdata( get_current_user_id() );
	global $wp;
	$redirect = home_url( $wp->request );
} else {
	$user = false;
}
?>
<form name="submit" class="terminal-contact hide-inputs"
	method="post"
	action-xhr="<?php echo admin_url( 'admin-ajax.php' ); ?>"
	target="_top">
		<div class="terminal-contact-inputs">
			<?php if ( ! empty( $user ) && ( empty( $hide_logout ) ) ) : ?>
				<?php printf(
					'<em>%s <a href="%s">%s</a></em>',
					esc_html( __( 'You are currently logged in, so we\'ll automatically record your name and email address.' ) ),
					esc_attr( wp_logout_url( $redirect ) ),
					esc_html( __( 'Logout to use this form anonymously.' ) )
				); ?>
			<?php endif; ?>
			<?php if ( empty( $user ) ) : ?>
				<label for="email"><?php _e( 'Email (Optional)', 'terminal' ); ?></label>
				<input id="email" type="email"
				name="email"
				placeholder="email@domain.com">
			<?php else : ?>
				<input id="email" type="hidden"
					name="email"
					value="<?php echo esc_attr( $user->user_email ); ?>" />
			<?php endif; ?>
			<?php if ( empty( $user ) ) : ?>
				<label for="name"><?php _e( 'Name (Optional)', 'terminal' ); ?></label>
				<input type="textfield"
				name="name"
				placeholder="Your Name">
			<?php else : ?>
				<input id="name" type="hidden"
					name="name"
					value="<?php echo esc_attr( "{$user->first_name} {$user->last_name}" ); ?>" />
			<?php endif; ?>
			<textarea
			name="message"
			rows="5"
			required></textarea>
			<input name="action" type="hidden" value="contact">
			<input name="security" type="hidden" value="<?php echo wp_create_nonce( 'contact-nonce' ); ?>">
			<input type="submit" value="Submit">
		</div>
		<div submit-success>
			<template type="amp-mustache">{{data}}</template>
		</div>
</form>