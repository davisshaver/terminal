<?php
/**
 * FM Widget integration.
 *
 * @package Terminal
 */

if ( class_exists( '\FM_Widget' ) ) {

	/**
	 * Snapchat Widget.
	 */
	class Snapchat_Follow_Widget extends \FM_Widget {

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				'terminal-snapchat-widget',
				__( 'Snapchat Follow', 'terminal' )
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
			$snapchat_url = ! empty( $instance['account'] ) ? $instance['account'] : false;
			if ( empty( $snapchat_url ) ) {
				return;
			}
			$cta = ! empty( $instance['call_to_action'] ) ? $instance['call_to_action'] : __( 'Follow our Snapchat story', 'terminal' );
			printf(
				'<div id="%s" class="terminal-follow terminal-snapchat terminal-snapchat-sidebar terminal-share-button-font"><div class="terminal-custom-sidebar-interior"><a href="%s" target="_blank">%s</a></div></div>',
				esc_attr( 'terminal-snapchat-widget-sidebar' ),
				esc_url( $snapchat_url ),
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
				'account'        => new \Fieldmanager_Link( __( 'Snapchat URL', 'terminal' ) ),
				'call_to_action' => new \Fieldmanager_TextField( array(
					'label'         => __( 'CTA', 'terminal' ),
					'default_value' => __( 'Follow our Snapchat story', 'terminal' ),
				) ),
			];
		}
	}

	register_widget( '\Snapchat_Follow_Widget' );
}
