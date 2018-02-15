<?php
/**
 * FM Widget integration.
 *
 * @package Terminal
 */

if ( class_exists( '\FM_Widget' ) ) {

	/**
	 * Instagram Widget.
	 */
	class Instagram_Follow_Widget extends \FM_Widget {

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				'terminal-instagram-widget',
				__( 'Instagram Follow', 'terminal' )
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
			$instagram_handle = ! empty( $instance['account'] ) ? $instance['account'] : false;
			if ( empty( $instagram_handle ) ) {
				return;
			}
			$cta = ! empty( $instance['call_to_action'] ) ? $instance['call_to_action'] : __( 'Follow us on Instagram', 'terminal' );
			printf(
				'<div id="%s" class="terminal-follow terminal-instagram terminal-instagram-sidebar terminal-share-button-font"><div class="terminal-custom-sidebar-interior"><a href="%s" target="_blank">%s</a></div></div>',
				esc_attr( 'terminal-instagram-widget-sidebar' ),
				esc_url( "https://instagram.com/$instagram_handle" ),
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
				'account'        => new \Fieldmanager_TextField( __( 'Instagram Account', 'terminal' ) ),
				'call_to_action' => new \Fieldmanager_TextField( array(
					'label'         => __( 'CTA', 'terminal' ),
					'default_value' => __( 'Follow us on Instagram', 'terminal' ),
				) ),
			];
		}
	}

	register_widget( '\Instagram_Follow_Widget' );
}
