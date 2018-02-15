<?php
/**
 * FM Widget integration.
 *
 * @package Terminal
 */

if ( class_exists( '\FM_Widget' ) ) {

	/**
	 * Facebook Widget.
	 */
	class Facebook_Follow_Widget extends \FM_Widget {

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				'terminal-facebook-widget',
				__( 'Facebook Follow', 'terminal' )
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {
			$facebook_handle = ! empty( $instance['account'] ) ? $instance['account'] : false;
			if ( empty( $facebook_handle ) ) {
				return;
			}
			$cta = ! empty( $instance['call_to_action'] ) ? $instance['call_to_action'] : __( 'Like us on Facebook', 'terminal' );
			printf(
				'<div id="%s" class="terminal-follow terminal-facebook terminal-facebook-sidebar terminal-share-button-font"><div class="terminal-custom-sidebar-interior"><a href="%s" target="_blank">%s</a></div></div>',
				esc_attr( 'terminal-facebook-widget-sidebar' ),
				esc_url( "https://facebook.com/$facebook_handle" ),
				esc_html( $cta )
			);
		}

		/**
		 * Define the fields that should appear in the widget.
		 *
		 * @return array Fieldmanager fields.
		 */
		protected function fieldmanager_children() {
			return [
				'account'        => new \Fieldmanager_TextField( __( 'Facebook Page Slug', 'terminal' ) ),
				'call_to_action' => new \Fieldmanager_TextField( array(
					'label'         => __( 'CTA', 'terminal' ),
					'default_value' => __( 'Like us on Facebook', 'terminal' ),
				) ),
			];
		}
	}

	register_widget( '\Facebook_Follow_Widget' );
}
