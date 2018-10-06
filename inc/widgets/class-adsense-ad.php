<?php
/**
 * FM Widget integration.
 *
 * @package Terminal
 */

if ( class_exists( '\FM_Widget' ) ) {

	/**
	 * Demo Widget.
	 */
	class Adense_Widget extends \FM_Widget {

		/**
		 * Track uses as printing.
		 *
		 * @var int Uses.
		 */
		private $uses = 0;

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				'terminal-adsense-widget',
				__( 'Adsense Ad', 'terminal' )
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
			if ( ! empty( $instance['unit'] ) ) {
				$ad_unit = $instance['unit'];
			} else {
				$ad_unit = '7383568527';
			}
			if ( ! empty( $instance['width'] ) ) {
				$ad_width = $instance['width'];
			} else {
				$ad_width = '320';
			}
			if ( ! empty( $instance['height'] ) ) {
				$ad_height = $instance['height'];
			} else {
				$ad_height = '50';
			}
			printf(
				'<div class="terminal-ad"><amp-ad width="%s" height="%s" type="adsense" data-ad-client="ca-pub-0809625376938310" data-ad-slot="%s" layout="fixed"></amp-ad></div>',
				esc_attr( $ad_width ),
				esc_attr( $ad_height ),
				esc_attr( $ad_unit )
			);
		}

		/**
		 * Define the fields that should appear in the widget.
		 *
		 * @return array Fieldmanager fields.
		 */
		protected function fieldmanager_children() {
			return [
				'unit'   => new \Fieldmanager_TextField( __( 'Unit Override', 'terminal' ) ),
				'height' => new \Fieldmanager_TextField( __( 'Height Override', 'terminal' ) ),
				'width'  => new \Fieldmanager_TextField( __( 'Width Override', 'terminal' ) ),
			];
		}
	}

	register_widget( '\Adense_Widget' );
}
