<?php
/**
 * FM Widget integration.
 *
 * @package Terminal
 */

if ( class_exists( '\FM_Widget' ) ) {

	/**
	 * CTA Widget.
	 */
	class CTA_Widget extends \FM_Widget {

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				'terminal-cta-widget',
				__( 'CTA', 'terminal' )
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
			$link = ! empty( $instance['link'] ) ? $instance['link'] : false;
			if ( empty( $link ) ) {
				return;
			}
			$cta = ! empty( $instance['call_to_action'] ) ? $instance['call_to_action'] : __( 'Love what we do? Become a member today.', 'terminal' );
			$button = ! empty( $instance['button_text'] ) ? $instance['button_text'] : __( 'Support our work.', 'terminal' );
			printf(
				'<div class="terminal-cta-widget"><p>%s</p><div class="terminal-button"><a href="%s">%s</a></div></div>',
        esc_html( $cta ),
				esc_url( $link ),
        esc_html( $button )
			);
		}

		/**
		 * Define the fields that should appear in the widget.
		 *
		 * @return array Fieldmanager fields.
		 */
		protected function fieldmanager_children() {
			return [
				'link'        => new \Fieldmanager_Link( __( 'CTA URL', 'terminal' ) ),
				'call_to_action' => new \Fieldmanager_TextField( array(
					'label'         => __( 'CTA', 'terminal' ),
					'default_value' => __( 'Love what we do? Become a member today.', 'terminal' ),
        ) ),
				'button_text'    => new \Fieldmanager_TextField( array(
					'label'         => __( 'Button Text', 'terminal' ),
					'default_value' => __( 'Support our work.', 'terminal' ),
				) ),
			];
		}
	}

	register_widget( '\CTA_Widget' );
}
